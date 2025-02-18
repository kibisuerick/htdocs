<?php
require 'db.php'; // Include your database connection

$filter = $_GET['filter'] ?? '';

// Define date filters
$dateFilters = [
    "today" => "DATE(sale_date) = CURDATE()",
    "yesterday" => "DATE(sale_date) = CURDATE() - INTERVAL 1 DAY",
    "last7" => "sale_date >= CURDATE() - INTERVAL 7 DAY",
    "this_month" => "MONTH(sale_date) = MONTH(CURDATE()) AND YEAR(sale_date) = YEAR(CURDATE())",
    "last_month" => "MONTH(sale_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(sale_date) = YEAR(CURDATE() - INTERVAL 1 MONTH)"
];

// Construct SQL query based on filter
$sql = "SELECT salesperson_name, property_name, sales_type, price, sale_date FROM sales_report";
if (!empty($filter) && isset($dateFilters[$filter])) {
    $sql .= " WHERE " . $dateFilters[$filter];
}

$stmt = $pdo->query($sql);
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generate table rows
foreach ($sales as $sale) {
    echo "<tr>
            <td>" . htmlspecialchars($sale['salesperson_name']) . "</td>
            <td>" . htmlspecialchars($sale['property_name']) . "</td>
            <td>" . htmlspecialchars($sale['sales_type']) . "</td>
            <td>" . number_format($sale['price'], 2) . "</td>
            <td>" . date("d M Y", strtotime($sale['sale_date'])) . "</td>
          </tr>";
}
?>
