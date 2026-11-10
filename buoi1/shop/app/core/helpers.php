<?php
// Ham dung chung: escape HTML, tao URL, dinh dang tien va lay anh san pham.
function e($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function base_url(string $path = ''): string {
    return rtrim(BASE_URL, '/') . ($path === '' ? '/' : '/' . ltrim($path, '/'));
}
function asset(string $path): string {
    return base_url('assets/' . ltrim($path, '/'));
}
function format_vnd($number): string {
    return number_format((float)$number, 0, ',', '.') . ' đ';
}
function product_image_url(?string $fileName, string $size = 'thumb'): string {
    if (empty($fileName)) return asset('img/no-image.png');
    $folder = $size === 'full' ? 'uploads/products/' : 'uploads/products/thumbs/';
    $diskPath = __DIR__ . '/../../public/' . $folder . basename($fileName);
    if (!is_file($diskPath)) return asset('img/no-image.png');
    return base_url($folder . rawurlencode(basename($fileName)));
}
