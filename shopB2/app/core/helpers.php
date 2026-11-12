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
/**
* Sinh (hoac tai su dung) token CSRF luu trong session.
*/
function csrf_token(): string
{
if (empty($_SESSION['csrf_token'])) {
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
return $_SESSION['csrf_token'];
}
/**
* Tra ve input hidden de nhung vao MOI form POST.
*/
function csrf_field(): string
{
return '<input type="hidden" name="csrf_token" value="' .
e(csrf_token()) . '">';
}
/**

* Doi chieu token gui len voi token trong session.
* Goi o DAU moi ham xu ly POST, truoc khi dong cham den du lieu.
*/
function verify_csrf(string $token): void
{
if (empty($_SESSION['csrf_token']) ||
!hash_equals($_SESSION['csrf_token'], $token)) {
http_response_code(419);
die('Phien lam viec da het han hoac yeu cau khong hop le. Vui long
tai lai trang va thu lai.');
}
}
/**
* Thong bao flash: hien thi MOT LAN roi tu xoa.
* - Goi flash('success', 'Noi dung') de LUU thong bao truoc khi redirect.
* - Goi flash('success') (khong co $message) de LAY va xoa thong bao.
*/
function flash(string $key, ?string $message = null, string $type =
'success'): ?array
{
if ($message !== null) {
$_SESSION['flash'][$key] = ['message' => $message, 'type' =>
$type];
return null;
}
if (!empty($_SESSION['flash'][$key])) {
$data = $_SESSION['flash'][$key];
unset($_SESSION['flash'][$key]);
return $data;
}
return null;
}