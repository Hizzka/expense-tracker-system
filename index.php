<?php
require_once 'config.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Get current month stats
$current_month = date('Y-m');
$query = "SELECT SUM(amount) as total FROM expenses WHERE user_id = ? AND DATE_FORMAT(expense_date, '%Y-%m') = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $current_month);
$stmt->execute();
$result = $stmt->get_result();
$month_total = $result->fetch_assoc()['total'] ?? 0;

// Get total expenses count
$query = "SELECT COUNT(*) as count FROM expenses WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$total_expenses = $result->fetch_assoc()['count'];

// Get category-wise spending
$query = "SELECT c.name, c.color, c.icon, SUM(e.amount) as total 
          FROM expenses e 
          JOIN categories c ON e.category_id = c.id 
          WHERE e.user_id = ? AND DATE_FORMAT(e.expense_date, '%Y-%m') = ?
          GROUP BY c.id, c.name, c.color, c.icon
          ORDER BY total DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $current_month);
$stmt->execute();
$category_data = $stmt->get_result();

$categories = [];
$amounts = [];
$colors = [];
while ($row = $category_data->fetch_assoc()) {
    $categories[] = $row['icon'] . ' ' . $row['name'];
    $amounts[] = $row['total'];
    $colors[] = $row['color'];
}

// Get recent expenses
$query = "SELECT e.*, c.name as category_name, c.icon 
          FROM expenses e 
          JOIN categories c ON e.category_id = c.id 
          WHERE e.user_id = ? 
          ORDER BY e.expense_date DESC, e.created_at DESC 
          LIMIT 10";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent_expenses = $stmt->get_result();

// Get last 6 months data for line chart
$monthly_data = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $query = "SELECT SUM(amount) as total FROM expenses WHERE user_id = ? AND DATE_FORMAT(expense_date, '%Y-%m') = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $user_id, $month);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = $result->fetch_assoc()['total'] ?? 0;
    $monthly_data[] = [
        'month' => date('M Y', strtotime($month)),
        'total' => $total
    ];
}

$page_title = 'Dashboard - Expense Tracker';
include 'includes/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">Dashboard</h1>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-calendar-month"></i> This Month</h5>
                <h2 class="mb-0">₱<?php echo number_format($month_total, 2); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-receipt"></i> Total Expenses</h5>
                <h2 class="mb-0"><?php echo $total_expenses; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-graph-up"></i> Categories</h5>
                <h2 class="mb-0"><?php echo count($amounts); ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-pie-chart"></i> Category-wise Spending (This Month)</h5>
            </div>
            <div class="card-body">
                <?php if (count($amounts) > 0): ?>
                <canvas id="categoryChart"></canvas>
                <?php else: ?>
                <p class="text-muted text-center py-5">No expenses recorded for this month.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-graph-up-arrow"></i> Monthly Trend (Last 6 Months)</h5>
            </div>
            <div class="card-body">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Expenses -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-clock-history"></i> Recent Expenses</h5>
                <a href="add_expense.php" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Add Expense
                </a>
            </div>
            <div class="card-body">
                <?php if ($recent_expenses->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
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
                            <?php while ($expense = $recent_expenses->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($expense['expense_date'])); ?></td>
                                <td>
                                    <span class="badge" style="background-color: #6c757d;">
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
                    </table>
                </div>
                <?php else: ?>
                <p class="text-muted text-center py-5">No expenses recorded yet. <a href="add_expense.php">Add your first expense</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Category Pie Chart
<?php if (count($amounts) > 0): ?>
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($categories); ?>,
        datasets: [{
            data: <?php echo json_encode($amounts); ?>,
            backgroundColor: <?php echo json_encode($colors); ?>,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ₱' + context.parsed.toFixed(2);
                    }
                }
            }
        }
    }
});
<?php endif; ?>

// Monthly Trend Line Chart
const trendCtx = document.getElementById('trendChart').getContext('2d');
new Chart(trendCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($monthly_data, 'month')); ?>,
        datasets: [{
            label: 'Monthly Spending',
            data: <?php echo json_encode(array_column($monthly_data, 'total')); ?>,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Total: ₱' + context.parsed.y.toFixed(2);
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₱' + value;
                    }
                }
            }
        }
    }
});
</script>

<?php include 'includes/footer.php'; ?>
