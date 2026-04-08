<?php
require_once '../../includes/functions.php';
Auth::requireAuth();

$db = Database::getInstance()->getConnection();

if (!isset($_GET['id'])) {
    die('Invalid request');
}

$stmt = $db->prepare("SELECT * FROM invoices WHERE id = ?");
$stmt->execute([(int)$_GET['id']]);
$inv = $stmt->fetch();

if (!$inv) die('Invoice not found');

$lineItems = json_decode($inv['line_items'], true);
$subtotal = 0;
foreach ($lineItems as $item) {
    $subtotal += $item['unit_price'] * $item['quantity'];
}
$vatAmount = $inv['vat_applied'] ? $subtotal * ($inv['vat_percentage'] / 100) : 0;
$total = $subtotal + $vatAmount;

$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; padding: 20px; }
        
        /* Letterhead */
        .letterhead { 
            border-bottom: 3px solid #1e3a5f; 
            padding-bottom: 15px; 
            margin-bottom: 20px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
        }
        .logo-section { width: 150px; }
        .logo-placeholder { 
            width: 100px; height: 100px; 
            background: #1e3a5f; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: #d69e2e; 
            font-size: 28px; 
            font-weight: bold;
        }
        .company-info { text-align: right; }
        .company-info h1 { color: #1e3a5f; font-size: 18px; margin-bottom: 4px; }
        .company-info p { color: #666; font-size: 10px; line-height: 1.5; }
        
        /* Title */
        .doc-title { 
            text-align: center; 
            background: #1e3a5f; 
            color: white; 
            padding: 10px; 
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        
        /* Info Grid */
        .info-grid { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 20px; 
        }
        .info-left, .info-right { width: 48%; }
        .info-block { margin-bottom: 10px; }
        .info-block label { 
            display: block; 
            font-size: 9px; 
            color: #888; 
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .info-block p { font-size: 11px; color: #333; }
        
        /* Table */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { 
            background: #1e3a5f; 
            color: white; 
            padding: 8px 10px; 
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        td { padding: 8px 10px; border-bottom: 1px solid #eee; font-size: 10px; }
        tr:nth-child(even) { background: #f9f9f9; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* Totals */
        .totals { 
            width: 300px; 
            margin-left: auto; 
            margin-bottom: 20px; 
        }
        .totals-row { 
            display: flex; 
            justify-content: space-between; 
            padding: 6px 0; 
            border-bottom: 1px solid #eee;
        }
        .totals-row.grand-total { 
            background: #1e3a5f; 
            color: white; 
            padding: 10px; 
            font-weight: bold;
            font-size: 13px;
        }
        
        /* Notes & Terms */
        .notes-section { 
            background: #f5f5f5; 
            padding: 15px; 
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .notes-section h4 { color: #1e3a5f; margin-bottom: 5px; font-size: 11px; }
        .notes-section p { font-size: 10px; color: #555; white-space: pre-line; }
        
        /* Signature */
        .signature-section { 
            margin-top: 40px; 
            display: flex; 
            justify-content: flex-end;
        }
        .signature-box { 
            text-align: center; 
            width: 250px;
        }
        .signature-line { 
            border-bottom: 1px solid #333; 
            margin-bottom: 5px; 
            height: 60px;
        }
        .signature-name { font-weight: bold; color: #1e3a5f; }
        .signature-title { font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <!-- Letterhead -->
    <div class="letterhead">
        <div class="logo-section">
            <div class="logo-placeholder">CDB</div>
        </div>
        <div class="company-info">
            <h1>PT CAHAYA DIMENSI BUMI</h1>
            <p>
                Jl. Contoh Alamat No. 123<br>
                Jakarta, Indonesia 12345<br>
                Telp: +62 21 1234 5678<br>
                Email: info@cahayadimensibumi.com
            </p>
        </div>
    </div>

    <!-- Document Title -->
    <div class="doc-title">INVOICE</div>

    <!-- Info Grid -->
    <div class="info-grid">
        <div class="info-left">
            <div class="info-block">
                <label>Invoice Number</label>
                <p><strong>' . sanitize($inv['invoice_number']) . '</strong></p>
            </div>
            <div class="info-block">
                <label>Invoice Date</label>
                <p>' . formatDate($inv['invoice_date']) . '</p>
            </div>
            <div class="info-block">
                <label>Due Date</label>
                <p>' . formatDate($inv['due_date']) . '</p>
            </div>
            <div class="info-block">
                <label>Salesperson</label>
                <p>' . sanitize($inv['salesperson']) . '</p>
            </div>
        </div>
        <div class="info-right">
            <div class="info-block">
                <label>Company Name</label>
                <p><strong>' . sanitize($inv['company_name']) . '</strong></p>
            </div>
            <div class="info-block">
                <label>Address</label>
                <p>' . sanitize($inv['address']) . '</p>
            </div>
            <div class="info-block">
                <label>City / Zip Code</label>
                <p>' . sanitize($inv['city']) . ' ' . sanitize($inv['zip_code']) . '</p>
            </div>
            <div class="info-block">
                <label>Project Description</label>
                <p>' . sanitize($inv['project_description']) . '</p>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 35%">Item Name</th>
                <th class="text-center" style="width: 10%">Qty</th>
                <th class="text-center" style="width: 10%">Unit</th>
                <th class="text-right" style="width: 20%">Unit Price</th>
                <th class="text-right" style="width: 20%">Total</th>
            </tr>
        </thead>
        <tbody>';

$no = 1;
foreach ($lineItems as $item) {
    $lineTotal = $item['unit_price'] * $item['quantity'];
    $html .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . sanitize($item['item_name']) . '</td>
                <td class="text-center">' . number_format($item['quantity'], 2) . '</td>
                <td class="text-center">' . sanitize($item['unit']) . '</td>
                <td class="text-right">' . formatCurrency($item['unit_price']) . '</td>
                <td class="text-right">' . formatCurrency($lineTotal) . '</td>
            </tr>';
}

$html .= '
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <div class="totals-row">
            <span>Subtotal</span>
            <span>' . formatCurrency($subtotal) . '</span>
        </div>';

if ($inv['vat_applied']) {
    $html .= '
        <div class="totals-row">
            <span>VAT (' . $inv['vat_percentage'] . '%)</span>
            <span>' . formatCurrency($vatAmount) . '</span>
        </div>';
}

$html .= '
        <div class="totals-row grand-total">
            <span>TOTAL</span>
            <span>' . formatCurrency($total) . '</span>
        </div>
    </div>';

if ($inv['notes'] || $inv['terms']) {
    $html .= '
    <!-- Notes & Terms -->
    <div class="notes-section">';
    
    if ($inv['notes']) {
        $html .= '
        <h4>Notes:</h4>
        <p>' . sanitize($inv['notes']) . '</p>';
    }
    if ($inv['terms']) {
        $html .= '
        <h4>Terms & Conditions:</h4>
        <p>' . sanitize($inv['terms']) . '</p>';
    }
    $html .= '
    </div>';
}

$html .= '
    <!-- Signature -->
    <div class="signature-section">
        <div class="signature-box">
            <p style="margin-bottom: 5px;">Jakarta, ' . formatDate($inv['invoice_date']) . '</p>
            <p style="margin-bottom: 10px;">Hormat kami,</p>
            <div class="signature-line"></div>
            <p class="signature-name">Valerie Febriana Putri</p>
            <p class="signature-title">Direktur</p>
            <p class="signature-title">PT Cahaya Dimensi Bumi</p>
        </div>
    </div>
</body>
</html>';

generatePDF($html, 'Invoice_' . $inv['invoice_number']);