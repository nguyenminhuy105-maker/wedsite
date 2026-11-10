<?php
// Cau hinh tap trung cho toan bo ung dung.
define('DB_HOST', 'localhost');
define('DB_NAME', 'shop_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Neu doi ten thu muc shop, hay sua BASE_URL cho dung.
define('BASE_URL', 'http://localhost/shop/public/');

// Cau hinh anh dung cho cac buoi sau.
define('UPLOAD_ALLOWED_EXT', ['jpg', 'jpeg', 'png', 'webp']);
define('UPLOAD_MAX_SIZE', 2 * 1024 * 1024);
define('UPLOAD_MIN_SIDE', 300);
define('UPLOAD_MAX_SIDE', 5000);
define('UPLOAD_MAX_IMAGES', 8);
define('IMAGE_MAIN_WIDTH', 1200);
define('IMAGE_THUMB_WIDTH', 400);
