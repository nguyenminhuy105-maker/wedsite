<?php
class User extends Model {
    protected static string $table = 'users';
    public static function findByEmail(string $email): ?array {
        $stmt = self::db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]); return $stmt->fetch() ?: null;
    }
}
