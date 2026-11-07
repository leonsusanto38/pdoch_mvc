<?php
// Muat konfigurasi lokal (config.php tidak ikut di-push)
$configFile = __DIR__ . '/config.php';
if (!file_exists($configFile)) {
    die('config.php belum ada. Salin config.example.php menjadi config.php lalu sesuaikan isinya.');
}
define('APP_CONFIG', require $configFile);
define('BASE_URL', APP_CONFIG['base_url']);

date_default_timezone_set('Asia/Jakarta');

// Register PSR-4 Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Helper global untuk view
function csrf_token(): string
{
    return \App\Core\Session::csrfToken();
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

// Boot the application
new App\Core\App();
