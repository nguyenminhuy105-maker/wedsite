<?php
class Auth {
    public static function user(): ?array { return $_SESSION['user'] ?? null; }
    public static function check(): bool { return self::user() !== null; }
    public static function login(array $user): void {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id'=>(int)$user['id'], 'name'=>$user['name'], 'email'=>$user['email'], 'role'=>$user['role']];
    }
    public static function logout(): void { unset($_SESSION['user']); session_regenerate_id(true); }
    public static function requireLogin(): void {
        if (!self::check()) { flash('error', 'Vui lòng đăng nhập.'); header('Location: ' . base_url('auth/login')); exit; }
    }
    public static function requireRole(array $roles): void {
        self::requireLogin();
        if (!in_array(self::user()['role'], $roles, true)) { http_response_code(403); require __DIR__ . '/../views/errors/403.php'; exit; }
    }
}
