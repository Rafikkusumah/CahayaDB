<!DOCTYPE html>
<html><head><meta charset="UTF-8"><style>
body{font-family:Arial,sans-serif;font-size:11px;color:#333;padding:20px}
.letterhead{border-bottom:3px solid #1e3a5f;padding-bottom:15px;margin-bottom:20px;display:flex;justify-content:space-between;align-items:center}
.logo{width:80px;height:80px;background:#1e3a5f;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#d69e2e;font-size:24px;font-weight:bold}
.comp{text-align:right}.comp h1{color:#1e3a5f;font-size:16px}.comp p{color:#666;font-size:10px}
.title{text-align:center;background:#1e3a5f;color:#fff;padding:10px;margin-bottom:20px;font-weight:bold;letter-spacing:2px}
.info{display:flex;justify-content:space-between;margin-bottom:20px}.info-left,.info-right{width:48%}
.block{margin-bottom:8px}.block label{display:block;font-size:9px;color:#888;text-transform:uppercase}
table{width:100%;border-collapse:collapse;margin-bottom:20px}
th{background:#1e3a5f;color:#fff;padding:8px;text-align:left;font-size:10px}
td{padding:8px;border-bottom:1px solid #eee}
.totals{width:300px;margin-left:auto;margin-bottom:20px}.row{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #eee}
.total{background:#1e3a5f;color:#fff;padding:10px;font-weight:bold}
.notes{background:#f5f5f5;padding:15px;border-radius:5px;margin-bottom:20px}
.sig{margin-top:40px;text-align:right}.sig-box{text-align:center;display:inline-block;width:250px}
.line{border-bottom:1px solid #333;margin-bottom:5px;height:60px}
</style></head><body>
<div class="letterhead"><div class="logo">CDB</div><div class="comp"><h1>PT CAHAYA DIMENSI BUMI</h1><p>Jl. Contoh Alamat No. 123, Jakarta 12345<br>Telp: +62 21 1234 5678<br>info@cahayadimensibumi.com</p></div></div>
<div class="title">QUOTATION</div>
<div class="info">
    <div class="info-left">
        <div class="block"><label>Quotation No</label><strong><?= htmlspecialchars($d['quotation_number']) ?></strong></div>
        <div class="block"><label>Date</label><?= date('d F Y', strtotime($d['quotation_date'])) ?></div>
        <div class="block"><label>Valid Until</label><?= date('d F Y', strtotime($d['valid_until'])) ?></div>
        <div class="block"><label>Salesperson</label><?= htmlspecialchars($d['salesperson']) ?></div>
    </div>
    <div class="info-right">
        <div class="block"><label>Company</label><strong><?= htmlspecialchars($d['company_name']) ?></strong></div>
        <div class="block"><label>Address</label><?= htmlspecialchars($d['address']) ?></div>
        <div class="block"><label>City / Zip</label><?= htmlspecialchars($d['city']) ?> <?= htmlspecialchars($d['zip_code']) ?></div>
        <div class="block"><label>Project</label><?= htmlspecialchars($d['project_description']) ?></div>
    </div>
</div>
<table><thead><tr><th style="width:5%">No</th><th style="width:35%">Item</th><th style="width:10%">Qty</th><th style="width:10%">Unit</th><th style="width:20%">Price</th><th style="width:20%">Total</th></tr></thead><tbody>
<?php $n=1; foreach($d['items'] as $i): $t=$i['unit_price']*$i['quantity']; ?>
<tr><td><?= $n++ ?></td><td><?= htmlspecialchars($i['item_name']) ?></td><td><?= number_format($i['quantity'],2) ?></td><td><?= htmlspecialchars($i['unit']) ?></td><td style="text-align:right">Rp <?= number_format($i['unit_price'],0,',','.') ?></td><td style="text-align:right">Rp <?= number_format($t,0,',','.') ?></td></tr>
<?php endforeach; ?>
</tbody></table>
<div class="totals">
    <div class="row"><span>Subtotal</span><span>Rp <?= number_format($d['subtotal'],0,',','.') ?></span></div>
    <?php if($d['vat_applied']): ?><div class="row"><span>VAT (<?= $d['vat_percentage'] ?>%)</span><span>Rp <?= number_format($d['vat_amount'],0,',','.') ?></span></div><?php endif; ?>
    <div class="row total"><span>TOTAL</span><span>Rp <?= number_format($d['total'],0,',','.') ?></span></div>
</div>
<?php if($d['notes']||$d['terms']): ?><div class="notes">
    <?php if($d['notes']): ?><label>Notes:</label><p><?= nl2br(htmlspecialchars($d['notes'])) ?></p><?php endif; ?>
    <?php if($d['terms']): ?><label>Terms & Conditions:</label><p><?= nl2br(htmlspecialchars($d['terms'])) ?></p><?php endif; ?>
</div><?php endif; ?>
<div class="sig"><div class="sig-box"><p>Jakarta, <?= date('d F Y', strtotime($d['created_at'])) ?></p><p>Hormat kami,</p><div class="line"></div><strong>Valerie Febriana Putri</strong><br>Direktur<br>PT Cahaya Dimensi Bumi</div></div>
</body></html>