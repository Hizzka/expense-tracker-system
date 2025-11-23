<?php
require_once 'config.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Get expense ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$expense_id = sanitize($_GET['id']);

// Get expense details
$query = "SELECT * FROM expenses WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $expense_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: index.php');
    exit();
}

$expense = $result->fetch_assoc();

// Get all categories
$categories_query = "SELECT * FROM categories ORDER BY name";
$categories = $conn->query($categories_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = sanitize($_POST['category_id']);
    $amount = sanitize($_POST['amount']);
    $description = sanitize($_POST['description']);
    $expense_date = sanitize($_POST['expense_date']);
    
    // Validation
    if (empty($category_id) || empty($amount) || empty($expense_date)) {
        $error = 'Please fill in all required fields.';
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $error = 'Please enter a valid amount.';
    } else {
        $query = "UPDATE expenses SET category_id = ?, amount = ?, description = ?, expense_date = ? WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("idssii", $category_id, $amount, $description, $expense_date, $expense_id, $user_id);
        
        if ($stmt->execute()) {
            $success = 'Expense updated successfully!';
            // Refresh expense data
            $stmt = $conn->prepare("SELECT * FROM expenses WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $expense_id, $user_id);
            $stmt->execute();
            $expense = $stmt->get_result()->fetch_assoc();
        } else {
            $error = 'Failed to update expense. Please try again.';
        }
        $stmt->close();
    }
}

$page_title = 'Edit Expense - Expense Tracker';
include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="bi bi-pencil"></i> Edit Expense</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php 
                            $categories->data_seek(0);
                            while ($category = $categories->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $category['id']; ?>" 
                                        <?php echo ($expense['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo $category['icon'] . ' ' . htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (₱) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0.01" required
                               value="<?php echo htmlspecialchars($expense['amount']); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="expense_date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="expense_date" name="expense_date" required
                               value="<?php echo htmlspecialchars($expense['expense_date']); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($expense['description']); ?></textarea>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Expense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
