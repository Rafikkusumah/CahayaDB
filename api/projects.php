<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/middleware.php';

Middleware::requireAuth();
// Skip CSRF for API JSON requests - use token validation only if form data
if ($_SERVER['CONTENT_TYPE'] !== 'application/json' && in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE'])) {
    Middleware::csrfVerify();
}

$method = $_SERVER['REQUEST_METHOD'];
$db = Database::getInstance()->getConnection();

if ($method === 'GET') {
    echo json_encode($db->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC));
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $db->prepare("INSERT INTO projects (company_name, location, description, main_photo, other_media) VALUES (?,?,?,?,?)");
    $stmt->execute([$data['company_name'], $data['location'], $data['description'], $data['main_photo'] ?? null, $data['other_media'] ?? null]);
    echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
} elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $db->prepare("UPDATE projects SET company_name=?, location=?, description=?, main_photo=?, other_media=?, updated_at=CURRENT_TIMESTAMP WHERE id=?");
    $stmt->execute([$data['company_name'], $data['location'], $data['description'], $data['main_photo'] ?? null, $data['other_media'] ?? null, $data['id']]);
    echo json_encode(['success' => true]);
} elseif ($method === 'DELETE') {
    $id = json_decode(file_get_contents('php://input'), true)['id'];
    $db->prepare("DELETE FROM projects WHERE id=?")->execute([$id]);
    echo json_encode(['success' => true]);
}