<?php
// Konfigurasi lokal. File ini TIDAK ikut di-push (lihat .gitignore).
return [
  'base_url' => 'http://localhost/pdoch',

  'db' => [
    'host'    => 'localhost',
    'name'    => 'pdoch',
    'user'    => 'root',
    'pass'    => '',
    'charset' => 'utf8mb4',
  ],

  // Hanya dipakai saat tabel users masih kosong (auto seed)
  'seed' => [
    'admin_password' => 'ganti-dengan-password-kuat',
    'user_password'  => 'ganti-dengan-password-kuat',
  ],
];
