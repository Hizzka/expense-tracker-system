<?php
require_once 'config.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Check if expense ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$expense_id = sanitize($_GET['id']);

// Verify expense belongs to user and delete
$query = "DELETE FROM expenses WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $expense_id, $user_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    $_SESSION['delete_success'] = true;
} else {
    $_SESSION['delete_error'] = true;
}

$stmt->close();
header('Location: index.php');
exit();
?>
