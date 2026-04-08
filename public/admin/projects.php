<?php
require_once '../../includes/functions.php';
Auth::requireAuth();

$db = Database::getInstance()->getConnection();
$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyName = sanitize($_POST['company_name']);
    $location = sanitize($_POST['location']);
    $description = sanitize($_POST['description']);
    
    $mainPhoto = null;
    if (isset($_FILES['main_photo']) && $_FILES['main_photo']['error'] === 0) {
        $mainPhoto = uploadFile($_FILES['main_photo']);
    }

    $otherMedia = [];
    if (isset($_FILES['other_media'])) {
        foreach ($_FILES['other_media']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['other_media']['error'][$key] === 0) {
                $file = ['name' => $_FILES['other_media']['name'][$key], 'tmp_name' => $tmpName, 'error' => 0];
                $path = uploadFile($file);
                if ($path) $otherMedia[] = $path;
            }
        }
    }
    $otherMediaJson = json_encode($otherMedia);

    if (isset($_POST['id']) && $_POST['id']) {
        // Update
        $id = (int)$_POST['id'];
        $sql = "UPDATE projects SET company_name = ?, location = ?, description = ?";
        $params = [$companyName, $location, $description];
        
        if ($mainPhoto) {
            $sql .= ", main_photo = ?";
            $params[] = $mainPhoto;
        }
        $sql .= ", other_media = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $params[] = $otherMediaJson;
        $params[] = $id;
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $message = 'Project berhasil diupdate';
    } else {
        // Insert
        $stmt = $db->prepare("INSERT INTO projects (company_name, location, description, main_photo, other_media) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$companyName, $location, $description, $mainPhoto, $otherMediaJson]);
        $message = 'Project berhasil ditambahkan';
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->prepare("DELETE FROM projects WHERE id = ?")->execute([$id]);
    $message = 'Project berhasil dihapus';
}

$projects = $db->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
$editProject = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editProject = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - CDB Admin</title>
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
                    <a href="projects.php" class="flex items-center space-x-3 px-4 py-3 bg-secondary rounded-lg">
                        <i class="fas fa-project-diagram w-5"></i><span>Projects</span>
                    </a>
                    <a href="quotations.php" class="flex items-center space-x-3 px-4 py-3 hover:bg-secondary rounded-lg transition">
                        <i class="fas fa-file-invoice-dollar w-5"></i><span>Quotations</span>
                    </a>
                    <a href="invoices.php" class="flex items-center space-x-3 px-4 py-3 hover:bg-secondary rounded-lg transition">
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
            <header class="bg-white shadow-sm px-8 py-4">
                <h2 class="text-2xl font-bold text-primary">Manage Projects</h2>
            </header>

            <div class="p-8">
                <?php if ($message): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                    <i class="fas fa-check-circle mr-2"></i><?= $message ?>
                </div>
                <?php endif; ?>

                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Form -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="text-lg font-bold text-primary mb-4"><?= $editProject ? 'Edit' : 'Tambah' ?> Project</h3>
                        <form method="POST" enctype="multipart/form-data" class="space-y-4">
                            <?php if ($editProject): ?>
                            <input type="hidden" name="id" value="<?= $editProject['id'] ?>">
                            <?php endif; ?>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Nama PT</label>
                                <input type="text" name="company_name" required value="<?= $editProject ? sanitize($editProject['company_name']) : '' ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Lokasi</label>
                                <input type="text" name="location" required value="<?= $editProject ? sanitize($editProject['location']) : '' ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Project Description</label>
                                <textarea name="description" rows="4" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"><?= $editProject ? sanitize($editProject['description']) : '' ?></textarea>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Foto Depan</label>
                                <input type="file" name="main_photo" accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                <?php if ($editProject && $editProject['main_photo']): ?>
                                <img src="../<?= $editProject['main_photo'] ?>" class="mt-2 h-20 rounded">
                                <?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Other Foto / Video</label>
                                <input type="file" name="other_media[]" accept="image/*,video/*" multiple class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                            <div class="flex space-x-3">
                                <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-secondary transition">
                                    <i class="fas fa-save mr-2"></i>Simpan
                                </button>
                                <?php if ($editProject): ?>
                                <a href="projects.php" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">Batal</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <!-- Project List -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="text-lg font-bold text-primary mb-4">Daftar Project</h3>
                        <div class="space-y-4 max-h-[600px] overflow-y-auto">
                            <?php foreach ($projects as $p): ?>
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                                <?php if ($p['main_photo']): ?>
                                <img src="../<?= $p['main_photo'] ?>" class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                                <?php else: ?>
                                <div class="w-20 h-20 bg-primary rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-building text-white text-2xl"></i>
                                </div>
                                <?php endif; ?>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-800"><?= sanitize($p['company_name']) ?></h4>
                                    <p class="text-sm text-gray-500"><i class="fas fa-map-marker-alt mr-1"></i><?= sanitize($p['location']) ?></p>
                                    <p class="text-sm text-gray-600 mt-1 line-clamp-2"><?= sanitize($p['description']) ?></p>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="?edit=<?= $p['id'] ?>" class="text-blue-600 hover:text-blue-800 p-2"><i class="fas fa-edit"></i></a>
                                    <a href="?delete=<?= $p['id'] ?>" onclick="return confirm('Hapus project ini?')" class="text-red-600 hover:text-red-800 p-2"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php if (count($projects) === 0): ?>
                            <p class="text-gray-400 text-center py-8">Belum ada project</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>