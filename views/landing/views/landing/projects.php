<section id="projects" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16"><h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Recent Projects</h2></div>
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($projects as $p): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition group">
                <div class="relative h-64 overflow-hidden">
                    <?php if($p['main_photo']): ?>
                    <img src="<?= $p['main_photo'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <?php else: ?>
                    <div class="w-full h-full bg-primary flex items-center justify-center"><i class="fas fa-building text-6xl text-white opacity-50"></i></div>
                    <?php endif; ?>
                    <div class="absolute top-4 right-4 bg-accent text-white px-3 py-1 rounded-full text-sm font-semibold">Completed</div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-primary mb-2"><?= htmlspecialchars($p['company_name']) ?></h3>
                    <p class="text-gray-500 mb-2"><i class="fas fa-map-marker-alt mr-2"></i><?= htmlspecialchars($p['location']) ?></p>
                    <p class="text-gray-600 text-sm"><?= htmlspecialchars($p['description']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>