<?php
// Chay mot lan sau khi da import schema.sql va bat extension GD.
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/core/Database.php';
header('Content-Type: text/html; charset=utf-8');
if (!function_exists('imagecreatetruecolor')) exit('PHP chua bat extension GD. Hay bat extension=gd trong php.ini va khoi dong lai Apache.');
$pdo = Database::getInstance();
$uploadDir = __DIR__ . '/../public/uploads/products/';
$thumbDir = $uploadDir . 'thumbs/';
$noImageDir = __DIR__ . '/../public/assets/img/';
foreach ([$uploadDir, $thumbDir, $noImageDir] as $dir) if (!is_dir($dir)) mkdir($dir, 0755, true);

// Tao anh mac dinh neu chua ton tai.
$noImagePath = $noImageDir . 'no-image.png';
if (!file_exists($noImagePath)) {
    $img = imagecreatetruecolor(400, 400); $bg = imagecolorallocate($img, 235, 238, 242); imagefill($img, 0, 0, $bg);
    $gray = imagecolorallocate($img, 95, 105, 115); imagestring($img, 5, 145, 190, 'NO IMAGE', $gray); imagepng($img, $noImagePath); imagedestroy($img);
    echo 'Da tao anh mac dinh.<br>';
}
$products = $pdo->query('SELECT id, name FROM products ORDER BY id')->fetchAll();
$skipProductIds = [3, 17]; // de trong 2 san pham theo bai hoc
$palette = [[41,128,185],[39,174,96],[142,68,173],[230,126,34],[192,57,43],[26,188,156]];
$insertStmt = $pdo->prepare('INSERT INTO product_images (product_id,file_name,original_name,alt_text,is_primary,sort_order,created_at) VALUES (:product_id,:file_name,:original_name,:alt_text,:is_primary,:sort_order,NOW())');
foreach ($products as $product) {
    if (in_array((int)$product['id'], $skipProductIds, true)) { echo 'Bo qua san pham #' . (int)$product['id'] . ' de test anh mac dinh.<br>'; continue; }
    // Neu da co anh thi khong tao trung khi chay lai script.
    $check = $pdo->prepare('SELECT COUNT(*) FROM product_images WHERE product_id = ?'); $check->execute([$product['id']]);
    if ((int)$check->fetchColumn() > 0) { echo 'San pham #' . (int)$product['id'] . ' da co anh, bo qua.<br>'; continue; }
    $imageCount = random_int(1, 3);
    for ($i = 0; $i < $imageCount; $i++) {
        $fileName = 'seed_' . $product['id'] . '_' . ($i + 1) . '_' . bin2hex(random_bytes(3)) . '.jpg';
        [$r,$g,$b] = $palette[array_rand($palette)];
        $main = imagecreatetruecolor(IMAGE_MAIN_WIDTH, IMAGE_MAIN_WIDTH); $bg = imagecolorallocate($main,$r,$g,$b); imagefill($main,0,0,$bg);
        $white = imagecolorallocate($main,255,255,255); $label = mb_substr($product['name'],0,35); imagestring($main,5,25,(int)(IMAGE_MAIN_WIDTH/2),$label,$white);
        imagejpeg($main,$uploadDir.$fileName,85);
        $thumb = imagecreatetruecolor(IMAGE_THUMB_WIDTH,IMAGE_THUMB_WIDTH);
        imagecopyresampled($thumb,$main,0,0,0,0,IMAGE_THUMB_WIDTH,IMAGE_THUMB_WIDTH,IMAGE_MAIN_WIDTH,IMAGE_MAIN_WIDTH);
        imagejpeg($thumb,$thumbDir.$fileName,85); imagedestroy($main); imagedestroy($thumb);
        $insertStmt->execute(['product_id'=>$product['id'],'file_name'=>$fileName,'original_name'=>'seed.jpg','alt_text'=>$product['name'],'is_primary'=>$i===0?1:0,'sort_order'=>$i]);
    }
    echo 'Da tao ' . $imageCount . ' anh cho san pham #' . (int)$product['id'] . ' - ' . htmlspecialchars($product['name'],ENT_QUOTES,'UTF-8') . '<br>';
}
echo '<p><strong>Hoan tat sinh anh mau.</strong></p>';
