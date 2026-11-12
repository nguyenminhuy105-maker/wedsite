<?php
function base_url(string $path = ''): string { return rtrim(BASE_URL, '/') . ($path ? '/' . ltrim($path, '/') : '/'); }
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function redirect_to(string $path): never { header('Location: ' . base_url($path)); exit; }
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">'; }
function verify_csrf(string $token): void {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) { http_response_code(419); exit('Yêu cầu không hợp lệ (CSRF).'); }
}
function flash(string $type, string $message): void { $_SESSION['flash'][] = ['type'=>$type, 'message'=>$message]; }
function format_vnd($value): string { return number_format((float)$value, 0, ',', '.') . ' đ'; }
function product_image_url(?string $name, string $size = 'main'): string {
    if (!$name) return base_url('assets/img/no-image.svg');
    $folder = $size === 'thumb' ? 'thumbs/' : '';
    $path = __DIR__ . '/../../public/uploads/products/' . $folder . $name;
    return file_exists($path) ? base_url('uploads/products/' . $folder . rawurlencode($name)) : base_url('assets/img/no-image.svg');
}
