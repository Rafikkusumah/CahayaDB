<?php
// Konfigurasi utama aplikasi
return [
    'app_name'   => 'Cahaya Dimensi Bumi',
    'base_url'   => 'http://localhost:8000',
    'debug'      => true,
    'session_ttl'=> 1440, // menit
    'upload_dir' => __DIR__ . '/../public/assets/uploads/',
    'db_path'    => __DIR__ . '/../database.sqlite',
    'pdf_temp'   => __DIR__ . '/../storage/tmp/',
];