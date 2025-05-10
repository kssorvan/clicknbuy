<?php
use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$earnings = $db->query("
    SELECT MONTHNAME(created_at) as month, SUM(total_amount) as total
    FROM orders
    WHERE YEAR(created_at) = YEAR(CURRENT_DATE())
    AND status != 'canceled'
    GROUP BY MONTH(created_at)
    ORDER BY MONTH(created_at)
")->get();

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=earnings.csv');
$output = fopen('php://output', 'w');
fputcsv($output, ['Month', 'Total Earnings']);
foreach ($earnings as $row) {
    fputcsv($output, [$row['month'], $row['total']]);
}
fclose($output);
exit;