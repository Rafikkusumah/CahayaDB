<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/middleware.php';

// Guard & CSRF
Middleware::requireAuth();
if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE'])) {
    Middleware::csrfVerify();
}

$db = Database::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $stmt = $db->query("SELECT id, invoice_number, company_name, invoice_date, due_date, vat_applied, vat_percentage, notes, terms, created_at FROM invoices ORDER BY created_at DESC");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    } elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || empty($data['invoice_number'])) throw new Exception('Invalid or missing data');

        $lineItems = $data['items'] ?? [];
        $stmt = $db->prepare("INSERT INTO invoices (
            invoice_number, invoice_date, due_date, salesperson, company_name, address, city, zip_code, 
            project_description, line_items, vat_applied, vat_percentage, notes, terms, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
        
        $stmt->execute([
            $data['invoice_number'], $data['invoice_date'], $data['due_date'], $data['salesperson'],
            $data['company_name'], $data['address'] ?? '', $data['city'] ?? '', $data['zip_code'] ?? '',
            $data['project_description'] ?? '', json_encode($lineItems),
            $data['vat_applied'] ? 1 : 0, $data['vat_percentage'] ?? 0,
            $data['notes'] ?? '', $data['terms'] ?? ''
        ]);
        echo json_encode(['success' => true, 'id' => $db->lastInsertId(), 'message' => 'Invoice created successfully']);

    } elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['id'])) throw new Exception('ID required');

        $lineItems = $data['items'] ?? [];
        $stmt = $db->prepare("UPDATE invoices SET 
            invoice_number=?, invoice_date=?, due_date=?, salesperson=?, company_name=?, address=?, city=?, zip_code=?, 
            project_description=?, line_items=?, vat_applied=?, vat_percentage=?, notes=?, terms=?, updated_at=CURRENT_TIMESTAMP 
            WHERE id=?");
            
        $stmt->execute([
            $data['invoice_number'], $data['invoice_date'], $data['due_date'], $data['salesperson'],
            $data['company_name'], $data['address'] ?? '', $data['city'] ?? '', $data['zip_code'] ?? '',
            $data['project_description'] ?? '', json_encode($lineItems),
            $data['vat_applied'] ? 1 : 0, $data['vat_percentage'] ?? 0,
            $data['notes'] ?? '', $data['terms'] ?? '', $data['id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Invoice updated successfully']);

    } elseif ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['id'])) throw new Exception('ID required');
        
        $db->prepare("DELETE FROM invoices WHERE id=?")->execute([$data['id']]);
        echo json_encode(['success' => true, 'message' => 'Invoice deleted successfully']);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}