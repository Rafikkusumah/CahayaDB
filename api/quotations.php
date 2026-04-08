<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/middleware.php';
Middleware::requireAuth();
Middleware::csrfVerify();

$db = Database::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    echo json_encode($db->query("SELECT id, quotation_number, company_name, quotation_date, valid_until, created_at FROM quotations ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC));
} elseif ($method === 'POST') {
    $d = json_decode(file_get_contents('php://input'), true);
    $d['line_items'] = json_encode($d['items']);
    unset($d['items']);
    $cols = implode(',', array_keys($d));
    $vals = implode(',', array_fill(0, count($d), '?'));
    $db->prepare("INSERT INTO quotations ($cols, created_at, updated_at) VALUES ($vals, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)")->execute(array_values($d));
    echo json_encode(['success'=>true, 'id'=>$db->lastInsertId()]);
} elseif ($method === 'PUT') {
    $d = json_decode(file_get_contents('php://input'), true);
    $sets = implode('=?, ', array_keys($d)) . '=?';
    $vals = array_values($d); $vals[] = $d['id']; unset($vals[array_search($d['id'], $vals)]);
    $db->prepare("UPDATE quotations SET $sets, updated_at=CURRENT_TIMESTAMP WHERE id=?")->execute(array_values($d));
    echo json_encode(['success'=>true]);
} elseif ($method === 'DELETE') {
    $id = json_decode(file_get_contents('php://input'), true)['id'];
    $db->prepare("DELETE FROM quotations WHERE id=?")->execute([$id]);
    echo json_encode(['success'=>true]);
}