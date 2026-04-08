<?php
require_once __DIR__ . '/auth.php';

function generatePDF($html, $filename, $orientation = 'P') {
    require_once __DIR__ . '/../vendor/autoload.php';
    
    $options = new Dompdf\Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'Arial');
    
    $dompdf = new Dompdf\Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', $orientation);
    $dompdf->render();
    $dompdf->stream($filename . '.pdf', ['Attachment' => false]);
}

function generateSlug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function uploadFile($file, $directory = 'public/assets/uploads/') {
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $targetPath = $directory . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return 'assets/uploads/' . $filename;
    }
    return null;
}

function formatCurrency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function formatDate($date) {
    return date('d F Y', strtotime($date));
}

function calculateLineItemTotal($lineItems) {
    $items = json_decode($lineItems, true);
    $total = 0;
    foreach ($items as $item) {
        $total += $item['unit_price'] * $item['quantity'];
    }
    return $total;
}