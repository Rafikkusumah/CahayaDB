<?php
require_once '../../includes/functions.php';
Auth::requireAuth();

$db = Database::getInstance()->getConnection();
$message = '';
$editBlog = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $slug = generateSlug($title);
    $content = $_POST['content']; // Allow HTML
    $status = sanitize($_POST['status']);
    
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = uploadFile($_FILES['image']);
    }

    if (isset($_POST['id']) && $_POST['id']) {
        $id = (int)$_POST['id'];
        $sql = "UPDATE blogs SET title = ?, slug = ?, content = ?, status = ?";
        $params = [$title, $slug, $content, $status];
        if ($image) {
            $sql .= ", image = ?";
            $params[] = $image;
        }
        $sql .= ", updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $params[] = $id;
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $message = 'Blog berhasil diupdate';
    } else {
        $stmt = $db->prepare("INSERT INTO blogs (title, slug, content, image, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $content, $image, $status]);
        $message = 'Blog berhasil ditambahkan';
    }
}

if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM blogs WHERE id = ?")->execute([(int)$_GET['delete']]);
    $message = 'Blog berhasil dihapus';
}

if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM blogs WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editBlog = $stmt->fetch();
}

$blogs = $db->query("SELECT * FROM blogs ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - CDB Admin</title>
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
                    <a href="invoices.php" class="flex items-center space-x-3 px-4 py-3 hover:bg-secondary rounded-lg transition">
                        <i class="fas fa-file-invoice w-5"></i><span>Invoices</span>
                    </a>
                    <a href="blogs.php" class="flex items-center space-x-3 px-4 py-3 bg-secondary rounded-lg">
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
            <header class="bg-white shadow-sm px-8 py-4">
                <h2 class="text-2xl font-bold text-primary">Blog Management</h2>
            </header>

            <div class="p-8">
                <?php if ($message): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                    <i class="fas fa-check-circle mr-2"></i><?= $message ?>
                </div>
                <?php endif; ?>

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="text-lg font-bold text-primary mb-4"><?= $editBlog ? 'Edit' : 'Tambah' ?> Blog</h3>
                        <form method="POST" enctype="multipart/form-data" class="space-y-4">
                            <?php if ($editBlog): ?>
                            <input type="hidden" name="id" value="<?= $editBlog['id'] ?>">
                            <?php endif; ?>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Judul</label>
                                <input type="text" name="title" required value="<?= $editBlog ? sanitize($editBlog['title']) : '' ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Gambar</label>
                                <input type="file" name="image" accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                <?php if ($editBlog && $editBlog['image']): ?>
                                <img src="../<?= $editBlog['image'] ?>" class="mt-2 h-20 rounded">
                                <?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Konten</label>
                                <textarea name="content" rows="8" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"><?= $editBlog ? $editBlog['content'] : '' ?></textarea>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Status</label>
                                <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                                    <option value="draft" <?= $editBlog && $editBlog['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
                                    <option value="published" <?= $editBlog && $editBlog['status'] == 'published' ? 'selected' : '' ?>>Published</option>
                                </select>
                            </div>
                            <div class="flex space-x-3">
                                <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-secondary transition">
                                    <i class="fas fa-save mr-2"></i>Simpan
                                </button>
                                <?php if ($editBlog): ?>
                                <a href="blogs.php" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">Batal</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="text-lg font-bold text-primary mb-4">Daftar Blog</h3>
                        <div class="space-y-4 max-h-[600px] overflow-y-auto">
                            <?php foreach ($blogs as $b): ?>
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                                <?php if ($b['image']): ?>
                                <img src="../<?= $b['image'] ?>" class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                                <?php else: ?>
                                <div class="w-20 h-20 bg-primary rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-newspaper text-white text-2xl"></i>
                                </div>
                                <?php endif; ?>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-800"><?= sanitize($b['title']) ?></h4>
                                    <div class="flex items-center space-x-3 mt-1">
                                        <span class="text-xs px-2 py-1 rounded-full <?= $b['status'] == 'published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                                            <?= ucfirst($b['status']) ?>
                                        </span>
                                        <span class="text-xs text-gray-500"><?= formatDate($b['created_at']) ?></span>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="?edit=<?= $b['id'] ?>" class="text-blue-600 hover:text-blue-800 p-2"><i class="fas fa-edit"></i></a>
                                    <a href="?delete=<?= $b['id'] ?>" onclick="return confirm('Hapus blog ini?')" class="text-red-600 hover:text-red-800 p-2"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php if (count($blogs) === 0): ?>
                            <p class="text-gray-400 text-center py-8">Belum ada blog</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>