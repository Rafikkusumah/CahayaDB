<?php $pageTitle = 'Dashboard'; ob_start(); ?>
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <?php foreach(['projects'=>'fas fa-project-diagram','quotations'=>'fas fa-file-invoice-dollar','invoices'=>'fas fa-file-invoice','blogs'=>'fas fa-newspaper'] as $k=>$icon): ?>
    <div class="bg-white rounded-xl shadow p-6 flex items-center justify-between">
        <div><p class="text-gray-500 text-sm"><?= ucfirst($k) ?></p><p class="text-3xl font-bold text-primary"><?= $stats[$k] ?? 0 ?></p></div>
        <div class="bg-blue-100 p-3 rounded-lg"><i class="<?= $icon ?> text-primary text-xl"></i></div>
    </div>
    <?php endforeach; ?>
</div>

<div class="grid md:grid-cols-2 gap-8">
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-bold text-primary mb-4">Recent Projects</h3>
        <?php foreach($recentProjects as $p): ?>
        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg mb-2">
            <div class="w-10 h-10 bg-primary rounded flex items-center justify-center"><i class="fas fa-building text-white"></i></div>
            <div class="flex-1"><p class="font-semibold"><?= htmlspecialchars($p['company_name']) ?></p><p class="text-xs text-gray-500"><?= htmlspecialchars($p['location']) ?></p></div>
            <span class="text-xs text-gray-400"><?= date('d M Y', strtotime($p['created_at'])) ?></span>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-bold text-primary mb-4">Recent Quotations</h3>
        <?php foreach($recentQuotations as $q): ?>
        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg mb-2">
            <div class="w-10 h-10 bg-green-600 rounded flex items-center justify-center"><i class="fas fa-file-invoice-dollar text-white"></i></div>
            <div class="flex-1"><p class="font-semibold"><?= htmlspecialchars($q['quotation_number']) ?></p><p class="text-xs text-gray-500"><?= htmlspecialchars($q['company_name']) ?></p></div>
            <span class="text-xs text-gray-400"><?= date('d M Y', strtotime($q['created_at'])) ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../layouts/admin.php'; ?>