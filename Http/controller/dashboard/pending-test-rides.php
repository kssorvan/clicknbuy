<?php
use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$pendingRequests = $db->query("
    SELECT COUNT(*) as total
    FROM test_rides
    WHERE status = 'pending'
")->find()['total'] ?? 0;

header('Content-Type: application/json');
echo json_encode(['pendingRequests' => $pendingRequests]);
exit;