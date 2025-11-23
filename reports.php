<?php
require_once 'config.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Get filter parameters
$month = isset($_GET['month']) ? sanitize($_GET['month']) : date('Y-m');
$category_filter = isset($_GET['category']) ? sanitize($_GET['category']) : '';

// Build query based on filters
$where_clause = "e.user_id = ? AND DATE_FORMAT(e.expense_date, '%Y-%m') = ?";
$params = [$user_id, $month];
$types = "is";

if (!empty($category_filter)) {
    $where_clause .= " AND e.category_id = ?";
    $params[] = $category_filter;
    $types .= "i";
}

// Get total for the month
$query = "SELECT SUM(amount) as total FROM expenses e WHERE $where_clause";
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$total_month = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

// Get category-wise breakdown
$query = "SELECT c.id, c.name, c.icon, c.color, SUM(e.amount) as total, COUNT(e.id) as count
          FROM expenses e 
          JOIN categories c ON e.category_id = c.id 
          WHERE e.user_id = ? AND DATE_FORMAT(e.expense_date, '%Y-%m') = ?
          GROUP BY c.id, c.name, c.icon, c.color
          ORDER BY total DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $month);
$stmt->execute();
$category_breakdown = $stmt->get_result();

// Get all expenses for the month
$query = "SELECT e.*, c.name as category_name, c.icon, c.color 
          FROM expenses e 
          JOIN categories c ON e.category_id = c.id 
          WHERE $where_clause
          ORDER BY e.expense_date DESC, e.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$expenses = $stmt->get_result();

// Get all categories for filter
$categories_query = "SELECT * FROM categories ORDER BY name";
$all_categories = $conn->query($categories_query);

$page_title = 'Monthly Reports - Expense Tracker';
include 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h1><i class="bi bi-bar-chart"></i> Monthly Reports</h1>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="" class="row g-3">
                    <div class="col-md-4">
                        <label for="month" class="form-label">Select Month</label>
                        <input type="month" class="form-control" id="month" name="month" value="<?php echo $month; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="category" class="form-label">Filter by Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">All Categories</option>
                            <?php while ($cat = $all_categories->fetch_assoc()): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($category_filter == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo $cat['icon'] . ' ' . htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-filter"></i> Apply Filter
                        </button>
                        <a href="reports.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Total Summary -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body text-center">
                <h5 class="card-title">Total Spent</h5>
                <h2 class="mb-0">₱<?php echo number_format($total_month, 2); ?></h2>
                <small><?php echo date('F Y', strtotime($month)); ?></small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body text-center">
                <h5 class="card-title">Total Transactions</h5>
                <h2 class="mb-0"><?php echo $expenses->num_rows; ?></h2>
                <small><?php echo date('F Y', strtotime($month)); ?></small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body text-center">
                <h5 class="card-title">Export Data</h5>
                <a href="export_csv.php?month=<?php echo $month; ?><?php echo !empty($category_filter) ? '&category=' . $category_filter : ''; ?>" 
                   class="btn btn-light mt-2">
                    <i class="bi bi-download"></i> Download CSV
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Category Breakdown -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-pie-chart"></i> Category-wise Breakdown</h5>
            </div>
            <div class="card-body">
                <?php if ($category_breakdown->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Transactions</th>
                                <th>Total Amount</th>
                                <th>Percentage</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $category_breakdown->data_seek(0);
                            while ($cat = $category_breakdown->fetch_assoc()): 
                                $percentage = ($total_month > 0) ? ($cat['total'] / $total_month * 100) : 0;
                            ?>
                            <tr>
                                <td>
                                    <span class="badge" style="background-color: <?php echo $cat['color']; ?>;">
                                        <?php echo $cat['icon'] . ' ' . htmlspecialchars($cat['name']); ?>
                                    </span>
                                </td>
                                <td><?php echo $cat['count']; ?></td>
                                <td><strong>₱<?php echo number_format($cat['total'], 2); ?></strong></td>
                                <td><?php echo number_format($percentage, 1); ?>%</td>
                                <td>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: <?php echo $percentage; ?>%; background-color: <?php echo $cat['color']; ?>;" 
                                             aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                            <?php echo number_format($percentage, 1); ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-muted text-center py-4">No expenses found for the selected period.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Expense List -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-list-ul"></i> Detailed Expense List</h5>
            </div>
            <div class="card-body">
                <?php if ($expenses->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $expenses->data_seek(0);
                            while ($expense = $expenses->fetch_assoc()): 
                            ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($expense['expense_date'])); ?></td>
                                <td>
                                    <span class="badge" style="background-color: <?php echo $expense['color']; ?>;">
                                        <?php echo $expense['icon'] . ' ' . htmlspecialchars($expense['category_name']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($expense['description']); ?></td>
                                <td><strong>₱<?php echo number_format($expense['amount'], 2); ?></strong></td>
                                <td>
                                    <a href="edit_expense.php?id=<?php echo $expense['id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="delete_expense.php?id=<?php echo $expense['id']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Are you sure you want to delete this expense?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td colspan="2"><strong>₱<?php echo number_format($total_month, 2); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-muted text-center py-4">No expenses found for the selected period.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
