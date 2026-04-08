<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/middleware.php';

Middleware::requireAuth();
// Skip CSRF for API JSON requests - use token validation only if form data
if ($_SERVER['CONTENT_TYPE'] !== 'application/json' && in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE'])) {
    Middleware::csrfVerify();
}

$db = Database::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

// Helper: Slug Generator
function generateSlug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

try {
    if ($method === 'GET') {
        $limit = $_GET['limit'] ?? 10;
        $status = $_GET['status'] ?? null;
        
        $sql = "SELECT id, title, slug, status, image, created_at, updated_at FROM blogs";
        if ($status) $sql .= " WHERE status = ?";
        $sql .= " ORDER BY created_at DESC LIMIT $limit";
        
        $stmt = $status ? $db->prepare($sql) : $db->query($sql);
        if ($status) $stmt->execute([$status]);
        
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    } elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || empty($data['title'])) throw new Exception('Title is required');

        $slug = generateSlug($data['title']);
        $stmt = $db->prepare("INSERT INTO blogs (title, slug, content, image, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
        $stmt->execute([
            $data['title'], $slug, $data['content'] ?? '', $data['image'] ?? null, $data['status'] ?? 'draft'
        ]);
        echo json_encode(['success' => true, 'id' => $db->lastInsertId(), 'message' => 'Blog created successfully']);

    } elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['id'])) throw new Exception('ID required');

        $slug = generateSlug($data['title']);
        $stmt = $db->prepare("UPDATE blogs SET title=?, slug=?, content=?, image=?, status=?, updated_at=CURRENT_TIMESTAMP WHERE id=?");
        $stmt->execute([
            $data['title'], $slug, $data['content'] ?? '', $data['image'] ?? null, $data['status'] ?? 'draft', $data['id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Blog updated successfully']);

    } elseif ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['id'])) throw new Exception('ID required');
        
        $db->prepare("DELETE FROM blogs WHERE id=?")->execute([$data['id']]);
        echo json_encode(['success' => true, 'message' => 'Blog deleted successfully']);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}