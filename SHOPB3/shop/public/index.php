<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/core/helpers.php';
spl_autoload_register(function($class) {
    $paths=[__DIR__.'/../app/core/'.$class.'.php',__DIR__.'/../app/models/'.$class.'.php',__DIR__.'/../app/services/'.$class.'.php',__DIR__.'/../app/controllers/'.$class.'.php',__DIR__.'/../app/controllers/admin/'.$class.'.php'];
    foreach($paths as $path) if(file_exists($path)) { require_once $path; return; }
});
try { (new App())->run(); }
catch(Throwable $e) { error_log((string)$e); http_response_code(500); echo '<h1>Lỗi hệ thống</h1><p>Kiểm tra Apache/PHP error log để biết chi tiết.</p>'; }
