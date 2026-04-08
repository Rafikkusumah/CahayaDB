<section id="blog" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16"><h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Blog & Informasi</h2></div>
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($blogs as $b): ?>
            <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition">
                <?php if($b['image']): ?>
                <img src="<?= $b['image'] ?>" class="w-full h-48 object-cover">
                <?php else: ?>
                <div class="w-full h-48 bg-primary flex items-center justify-center"><i class="fas fa-newspaper text-5xl text-white opacity-50"></i></div>
                <?php endif; ?>
                <div class="p-6">
                    <div class="text-sm text-gray-500 mb-2"><i class="far fa-calendar mr-1"></i> <?= date('d F Y', strtotime($b['created_at'])) ?></div>
                    <h3 class="text-lg font-bold text-primary mb-2"><?= htmlspecialchars($b['title']) ?></h3>
                    <p class="text-gray-600 text-sm line-clamp-3"><?= substr(strip_tags($b['content']), 0, 120) ?>...</p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>