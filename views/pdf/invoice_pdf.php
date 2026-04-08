<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; margin: 0; padding: 20px; }
        .letterhead { border-bottom: 3px solid #1e3a5f; padding-bottom: 15px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .logo { width: 80px; height: 80px; background: #1e3a5f; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #d69e2e; font-size: 24px; font-weight: bold; }
        .comp { text-align: right; } 
        .comp h1 { color: #1e3a5f; font-size: 16px; margin: 0; } 
        .comp p { color: #666; font-size: 10px; line-height: 1.5; margin: 5px 0; }
        
        .doc-title { text-align: center; background: #1e3a5f; color: #fff; padding: 10px; margin-bottom: 20px; font-weight: bold; font-size: 14px; letter-spacing: 2px; }
        
        .info { display: flex; justify-content: space-between; margin-bottom: 20px; } 
        .info-left, .info-right { width: 48%; }
        .block { margin-bottom: 8px; } 
        .block label { display: block; font-size: 9px; color: #888; text-transform: uppercase; margin-bottom: 2px; } 
        .block p { margin: 0; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; } 
        th { background: #1e3a5f; color: #fff; padding: 8px; text-align: left; font-size: 10px; text-transform: uppercase; } 
        td { padding: 8px; border-bottom: 1px solid #eee; }
        .tr-alt { background: #f9f9f9; }
        .text-right { text-align: right; } .text-center { text-align: center; }
        
        .totals { width: 300px; margin-left: auto; margin-bottom: 20px; } 
        .t-row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #eee; } 
        .t-total { background: #1e3a5f; color: #fff; padding: 10px; font-weight: bold; border: none; }
        
        .notes { background: #f5f5f5; padding: 15px; border-radius: 5px; margin-bottom: 30px; } 
        .notes h4 { margin: 0 0 5px; font-size: 11px; color: #1e3a5f; } 
        .notes p { margin: 0; font-size: 10px; color: #555; white-space: pre-line; }
        
        .sig { margin-top: 20px; text-align: right; } 
        .sig-box { text-align: center; display: inline-block; width: 250px; } 
        .line { border-bottom: 1px solid #333; margin-bottom: 5px; height: 60px; } 
        .sig-name { font-weight: bold; color: #1e3a5f; margin: 0; } 
        .sig-title { margin: 0; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="letterhead">
        <div class="logo">CDB</div>
        <div class="comp">
            <h1>PT CAHAYA DIMENSI BUMI</h1>
            <p>Jl. Contoh Alamat No. 123<br>Jakarta, Indonesia 12345<br>Telp: +62 21 1234 5678<br>info@cahayadimensibumi.com</p>
        </div>
    </div>

    <div class="doc-title">INVOICE</div>

    <!-- Data Client & Invoice -->
    <div class="info">
        <div class="info-left">
            <div class="block"><label>Invoice Number</label><p><strong><?= htmlspecialchars($d['invoice_number']) ?></strong></p></div>
            <div class="block"><label>Invoice Date</label><p><?= date('d F Y', strtotime($d['invoice_date'])) ?></p></div>
            <div class="block"><label>Due Date</label><p><?= date('d F Y', strtotime($d['due_date'])) ?></p></div>
            <div class="block"><label>Salesperson</label><p><?= htmlspecialchars($d['salesperson']) ?></p></div>
        </div>
        <div class="info-right">
            <div class="block"><label>Company Name</label><p><strong><?= htmlspecialchars($d['company_name']) ?></strong></p></div>
            <div class="block"><label>Address</label><p><?= htmlspecialchars($d['address']) ?></p></div>
            <div class="block"><label>City / Zip Code</label><p><?= htmlspecialchars($d['city']) ?> <?= htmlspecialchars($d['zip_code']) ?></p></div>
            <div class="block"><label>Project Description</label><p><?= htmlspecialchars($d['project_description']) ?></p></div>
        </div>
    </div>

    <!-- Tabel Item -->
    <table>
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:35%">Item Name</th>
                <th class="text-center" style="width:10%">Qty</th>
                <th class="text-center" style="width:10%">Unit</th>
                <th class="text-right" style="width:20%">Unit Price</th>
                <th class="text-right" style="width:20%">Total</th>
            </tr>
        </thead>
        <tbody>
        <?php $n=1; foreach($d['items'] as $i): $lineTotal = $i['unit_price'] * $i['quantity']; ?>
        <tr class="<?= $n%2==0?'tr-alt':'' ?>">
            <td class="text-center"><?= $n++ ?></td>
            <td><?= htmlspecialchars($i['item_name']) ?></td>
            <td class="text-center"><?= number_format($i['quantity'], 2) ?></td>
            <td class="text-center"><?= htmlspecialchars($i['unit']) ?></td>
            <td class="text-right">Rp <?= number_format($i['unit_price'], 0, ',', '.') ?></td>
            <td class="text-right">Rp <?= number_format($lineTotal, 0, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Total -->
    <div class="totals">
        <div class="t-row"><span>Subtotal</span><span>Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></span></div>
        <?php if($d['vat_applied']): ?>
        <div class="t-row"><span>VAT (<?= $d['vat_percentage'] ?>%)</span><span>Rp <?= number_format($d['vat_amount'], 0, ',', '.') ?></span></div>
        <?php endif; ?>
        <div class="t-row t-total"><span>TOTAL</span><span>Rp <?= number_format($d['total'], 0, ',', '.') ?></span></div>
    </div>

    <!-- Notes & Terms -->
    <?php if(!empty($d['notes']) || !empty($d['terms'])): ?>
    <div class="notes">
        <?php if(!empty($d['notes'])): ?><h4>Notes:</h4><p><?= nl2br(htmlspecialchars($d['notes'])) ?></p><?php endif; ?>
        <?php if(!empty($d['terms'])): ?><h4>Terms & Conditions:</h4><p><?= nl2br(htmlspecialchars($d['terms'])) ?></p><?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Tanda Tangan -->
    <div class="sig">
        <div class="sig-box">
            <p style="margin-bottom:10px;">Jakarta, <?= date('d F Y', strtotime($d['invoice_date'])) ?></p>
            <p style="margin-bottom:40px;">Hormat kami,</p>
            <div class="line"></div>
            <p class="sig-name">Valerie Febriana Putri</p>
            <p class="sig-title">Direktur</p>
            <p class="sig-title">PT Cahaya Dimensi Bumi</p>
        </div>
    </div>
</body>
</html>