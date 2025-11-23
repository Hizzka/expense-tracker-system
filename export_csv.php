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

// Get expenses
$query = "SELECT e.*, c.name as category_name, c.icon 
          FROM expenses e 
          JOIN categories c ON e.category_id = c.id 
          WHERE $where_clause
          ORDER BY e.expense_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$expenses = $stmt->get_result();

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=expenses_' . $month . '.csv');

// Create output stream
$output = fopen('php://output', 'w');

// Add BOM for Excel UTF-8 support
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Add CSV headers
fputcsv($output, ['Date', 'Category', 'Description', 'Amount']);

// Add data rows
$total = 0;
while ($row = $expenses->fetch_assoc()) {
    fputcsv($output, [
        date('Y-m-d', strtotime($row['expense_date'])),
        $row['icon'] . ' ' . $row['category_name'],
        $row['description'],
        number_format($row['amount'], 2)
    ]);
    $total += $row['amount'];
}

// Add total row
fputcsv($output, ['', '', 'Total:', number_format($total, 2)]);

fclose($output);
exit();
?>
