<?php
require_once '../../includes/functions.php';
Auth::requireAuth();

$db = Database::getInstance()->getConnection();
$message = '';
$editData = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'invoice_number' => sanitize($_POST['invoice_number']),
        'invoice_date' => sanitize($_POST['invoice_date']),
        'due_date' => sanitize($_POST['due_date']),
        'salesperson' => sanitize($_POST['salesperson']),
        'company_name' => sanitize($_POST['company_name']),
        'address' => sanitize($_POST['address']),
        'city' => sanitize($_POST['city']),
        'zip_code' => sanitize($_POST['zip_code']),
        'project_description' => sanitize($_POST['project_description']),
        'vat_applied' => isset($_POST['vat_applied']) ? 1 : 0,
        'vat_percentage' => (float)$_POST['vat_percentage'],
        'notes' => sanitize($_POST['notes']),
        'terms' => sanitize($_POST['terms']),
    ];

    $lineItems = [];
    for ($i = 0; $i < count($_POST['item_name']); $i++) {
        if (!empty($_POST['item_name'][$i])) {
            $lineItems[] = [
                'item_name' => sanitize($_POST['item_name'][$i]),
                'unit_price' => (float)$_POST['unit_price'][$i],
                'quantity' => (float)$_POST['quantity'][$i],
                'unit' => sanitize($_POST['unit'][$i]),
                'total' => (float)$_POST['unit_price'][$i] * (float)$_POST['quantity'][$i]
            ];
        }
    }
    $data['line_items'] = json_encode($lineItems);

    if (isset($_POST['id']) && $_POST['id']) {
        $id = (int)$_POST['id'];
        $sql = "UPDATE invoices SET invoice_number = ?, invoice_date = ?, due_date = ?, 
                salesperson = ?, company_name = ?, address = ?, city = ?, zip_code = ?, 
                project_description = ?, line_items = ?, vat_applied = ?, vat_percentage = ?, 
                notes = ?, terms = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $params = [
            $data['invoice_number'], $data['invoice_date'], $data['due_date'],
            $data['salesperson'], $data['company_name'], $data['address'], $data['city'], $data['zip_code'],
            $data['project_description'], $data['line_items'], $data['vat_applied'], $data['vat_percentage'],
            $data['notes'], $data['terms'], $id
        ];
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $message = 'Invoice berhasil diupdate';
    } else {
        $sql = "INSERT INTO invoices (invoice_number, invoice_date, due_date, salesperson, 
                company_name, address, city, zip_code, project_description, line_items, 
                vat_applied, vat_percentage, notes, terms) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $data['invoice_number'], $data['invoice_date'], $data['due_date'],
            $data['salesperson'], $data['company_name'], $data['address'], $data['city'], $data['zip_code'],
            $data['project_description'], $data['line_items'], $data['vat_applied'], $data['vat_percentage'],
            $data['notes'], $data['terms']
        ];
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $message = 'Invoice berhasil dibuat';
    }
}

if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM invoices WHERE id = ?")->execute([(int)$_GET['delete']]);
    $message = 'Invoice berhasil dihapus';
}

if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM invoices WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editData = $stmt->fetch();
}

$invoices = $db->query("SELECT * FROM invoices ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices - CDB Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { primary: '#1e3a5f', secondary: '#2c5282', accent: '#d69e2e' } } } }
    </script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-primary text-white fixed h-full">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center">
                        <i class="fas fa-building text-white"></i>
                    </div>
                    <h1 class="font-bold">CDB Admin</h1>
                </div>
                <nav class="space-y-2">
                    <a href="index.php" class="flex items-center space-x-3 px-4 py-3 hover:bg-secondary rounded-lg transition">
                        <i class="fas fa-home w-5"></i><span>Dashboard</span>
                    </a>
                    <a href="projects.php" class="flex items-center space-x-3 px-4 py-3 hover:bg-secondary rounded-lg transition">
                        <i class="fas fa-project-diagram w-5"></i><span>Projects</span>
                    </a>
                    <a href="quotations.php" class="flex items-center space-x-3 px-4 py-3 hover:bg-secondary rounded-lg transition">
                        <i class="fas fa-file-invoice-dollar w-5"></i><span>Quotations</span>
                    </a>
                    <a href="invoices.php" class="flex items-center space-x-3 px-4 py-3 bg-secondary rounded-lg">
                        <i class="fas fa-file-invoice w-5"></i><span>Invoices</span>
                    </a>
                    <a href="blogs.php" class="flex items-center space-x-3 px-4 py-3 hover:bg-secondary rounded-lg transition">
                        <i class="fas fa-newspaper w-5"></i><span>Blog</span>
                    </a>
                    <hr class="border-gray-600 my-4">
                    <a href="../logout.php" class="flex items-center space-x-3 px-4 py-3 hover:bg-red-600 rounded-lg transition text-red-300">
                        <i class="fas fa-sign-out-alt w-5"></i><span>Logout</span>
                    </a>
                </nav>
            </div>
        </aside>

        <main class="flex-1 ml-64">
            <header class="bg-white shadow-sm px-8 py-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-primary">Invoice Management</h2>
                <a href="invoices.php" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                    <i class="fas fa-plus mr-2"></i>New Invoice
                </a>
            </header>

            <div class="p-8">
                <?php if ($message): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                    <i class="fas fa-check-circle mr-2"></i><?= $message ?>
                </div>
                <?php endif; ?>

                <?php if ($editData || !isset($_GET['edit'])): ?>
                <div class="bg-white rounded-xl shadow p-6 mb-8">
                    <h3 class="text-lg font-bold text-primary mb-6"><?= $editData ? 'Edit' : 'Buat' ?> Invoice</h3>
                    <form method="POST" id="invoiceForm">
                        <?php if ($editData): ?>
                        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                        <?php endif; ?>

                        <div class="grid md:grid-cols-3 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Invoice Number</label>
                                <input type="text" name="invoice_number" required 
                                       value="<?= $editData ? sanitize($editData['invoice_number']) : 'INV-' . date('Ymd') . '-' . rand(100, 999) ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Invoice Date</label>
                                <input type="date" name="invoice_date" required 
                                       value="<?= $editData ? $editData['invoice_date'] : date('Y-m-d') ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Due Date</label>
                                <input type="date" name="due_date" required 
                                       value="<?= $editData ? $editData['due_date'] : date('Y-m-d', strtotime('+30 days')) ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Salesperson</label>
                                <input type="text" name="salesperson" required 
                                       value="<?= $editData ? sanitize($editData['salesperson']) : '' ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Company Name</label>
                                <input type="text" name="company_name" required 
                                       value="<?= $editData ? sanitize($editData['company_name']) : '' ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-3 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Address</label>
                                <input type="text" name="address" value="<?= $editData ? sanitize($editData['address']) : '' ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">City</label>
                                <input type="text" name="city" value="<?= $editData ? sanitize($editData['city']) : '' ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Zip Code</label>
                                <input type="text" name="zip_code" value="<?= $editData ? sanitize($editData['zip_code']) : '' ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Project Description</label>
                            <textarea name="project_description" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"><?= $editData ? sanitize($editData['project_description']) : '' ?></textarea>
                        </div>

                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <label class="text-gray-700 font-semibold">Line Items</label>
                                <button type="button" onclick="addLineItem()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                                    <i class="fas fa-plus mr-1"></i>Add Item
                                </button>
                            </div>
                            <div id="lineItems" class="space-y-3">
                                <?php
                                $items = $editData ? json_decode($editData['line_items'], true) : [];
                                if (empty($items)) $items = [['item_name' => '', 'unit_price' => '', 'quantity' => '', 'unit' => '']];
                                foreach ($items as $index => $item):
                                ?>
                                <div class="line-item-row grid grid-cols-12 gap-3 items-end p-3 bg-gray-50 rounded-lg">
                                    <div class="col-span-4">
                                        <input type="text" name="item_name[]" required value="<?= sanitize($item['item_name']) ?>"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    </div>
                                    <div class="col-span-2">
                                        <input type="number" name="unit_price[]" required value="<?= $item['unit_price'] ?>" step="0.01"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg" onchange="calculateTotals()">
                                    </div>
                                    <div class="col-span-2">
                                        <input type="number" name="quantity[]" required value="<?= $item['quantity'] ?>" step="0.01"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg" onchange="calculateTotals()">
                                    </div>
                                    <div class="col-span-2">
                                        <input type="text" name="unit[]" required value="<?= sanitize($item['unit']) ?>"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="pcs/set">
                                    </div>
                                    <div class="col-span-1">
                                        <input type="text" class="item-total w-full px-3 py-2 bg-gray-100 border rounded-lg text-right" readonly value="<?= $item['unit_price'] * $item['quantity'] ?>">
                                    </div>
                                    <div class="col-span-1">
                                        <button type="button" onclick="removeLineItem(this)" class="text-red-500 hover:text-red-700 p-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Subtotal</span>
                                <span id="subtotalDisplay" class="font-semibold"><?= $editData ? formatCurrency(calculateLineItemTotal($editData['line_items'])) : 'Rp 0' ?></span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="vat_applied" id="vatCheckbox" <?= $editData && $editData['vat_applied'] ? 'checked' : '' ?>
                                           class="mr-2 w-4 h-4 text-primary rounded" onchange="toggleVAT()">
                                    <span class="text-gray-600">Include VAT</span>
                                </label>
                                <div id="vatInput" class="flex items-center <?= (!$editData || !$editData['vat_applied']) ? 'hidden' : '' ?>">
                                    <input type="number" name="vat_percentage" id="vatPercentage" 
                                           value="<?= $editData ? $editData['vat_percentage'] : 11 ?>" step="0.01"
                                           class="w-20 px-2 py-1 border border-gray-300 rounded-lg mr-2">
                                    <span class="text-gray-500">%</span>
                                    <span id="vatAmount" class="ml-4 font-semibold">Rp 0</span>
                                </div>
                            </div>
                            <div class="flex justify-between text-lg font-bold text-primary border-t pt-2">
                                <span>Total</span>
                                <span id="totalDisplay">Rp 0</span>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Notes</label>
                                <textarea name="notes" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"><?= $editData ? sanitize($editData['notes']) : '' ?></textarea>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Terms & Conditions</label>
                                <textarea name="terms" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"><?= $editData ? sanitize($editData['terms']) : '' ?></textarea>
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg hover:bg-secondary transition font-semibold">
                                <i class="fas fa-save mr-2"></i>Save Invoice
                            </button>
                            <?php if ($editData): ?>
                            <a href="invoices.php" class="bg-gray-500 text-white px-8 py-3 rounded-lg hover:bg-gray-600 transition">Batal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-bold text-primary mb-4">Daftar Invoice</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">No. Invoice</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Company</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Date</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Due Date</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Total</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($invoices as $inv): ?>
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold text-primary"><?= sanitize($inv['invoice_number']) ?></td>
                                    <td class="px-4 py-3"><?= sanitize($inv['company_name']) ?></td>
                                    <td class="px-4 py-3"><?= formatDate($inv['invoice_date']) ?></td>
                                    <td class="px-4 py-3"><?= formatDate($inv['due_date']) ?></td>
                                    <td class="px-4 py-3"><?= formatCurrency(calculateLineItemTotal($inv['line_items']) * (1 + ($inv['vat_applied'] ? $inv['vat_percentage']/100 : 0))) ?></td>
                                    <td class="px-4 py-3">
                                        <div class="flex space-x-2">
                                            <a href="view_invoice.php?id=<?= $inv['id'] ?>" class="text-blue-600 hover:text-blue-800" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="?edit=<?= $inv['id'] ?>" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="download_invoice.php?id=<?= $inv['id'] ?>" class="text-green-600 hover:text-green-800" title="Download PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <a href="?delete=<?= $inv['id'] ?>" onclick="return confirm('Hapus invoice ini?')" class="text-red-600 hover:text-red-800" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (count($invoices) === 0): ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada invoice</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function addLineItem() {
            const container = document.getElementById('lineItems');
            const row = document.createElement('div');
            row.className = 'line-item-row grid grid-cols-12 gap-3 items-end p-3 bg-gray-50 rounded-lg';
            row.innerHTML = `
                <div class="col-span-4"><input type="text" name="item_name[]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg"></div>
                <div class="col-span-2"><input type="number" name="unit_price[]" required step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg" onchange="calculateTotals()"></div>
                <div class="col-span-2"><input type="number" name="quantity[]" required step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg" onchange="calculateTotals()"></div>
                <div class="col-span-2"><input type="text" name="unit[]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="pcs/set"></div>
                <div class="col-span-1"><input type="text" class="item-total w-full px-3 py-2 bg-gray-100 border rounded-lg text-right" readonly></div>
                <div class="col-span-1"><button type="button" onclick="removeLineItem(this)" class="text-red-500 hover:text-red-700 p-2"><i class="fas fa-trash"></i></button></div>
            `;
            container.appendChild(row);
        }

        function removeLineItem(btn) {
            btn.closest('.line-item-row').remove();
            calculateTotals();
        }

        function calculateTotals() {
            let subtotal = 0;
            document.querySelectorAll('.line-item-row').forEach(row => {
                const price = parseFloat(row.querySelector('[name="unit_price[]"]')?.value || 0);
                const qty = parseFloat(row.querySelector('[name="quantity[]"]')?.value || 0);
                const total = price * qty;
                row.querySelector('.item-total').value = total.toLocaleString('id-ID');
                subtotal += total;
            });
            document.getElementById('subtotalDisplay').textContent = formatCurrency(subtotal);
            const vatApplied = document.getElementById('vatCheckbox').checked;
            const vatPercent = parseFloat(document.getElementById('vatPercentage').value || 0);
            let vatAmount = vatApplied ? subtotal * (vatPercent / 100) : 0;
            document.getElementById('vatAmount').textContent = formatCurrency(vatAmount);
            document.getElementById('totalDisplay').textContent = formatCurrency(subtotal + vatAmount);
        }

        function toggleVAT() {
            document.getElementById('vatInput').classList.toggle('hidden');
            calculateTotals();
        }

        function formatCurrency(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID', { minimumFractionDigits: 0 });
        }

        calculateTotals();
    </script>
</body>
</html>