<?php
class Model {
    protected static string $table = '';
    protected static function db(): PDO { return Database::getInstance(); }
    public static function all(string $order = 'id DESC'): array {
        return static::db()->query('SELECT * FROM ' . static::$table . ' ORDER BY ' . $order)->fetchAll();
    }
    public static function find(int $id): ?array {
        $stmt = static::db()->prepare('SELECT * FROM ' . static::$table . ' WHERE id = ? LIMIT 1');
        $stmt->execute([$id]); $row = $stmt->fetch(); return $row ?: null;
    }
    public static function insert(array $data): int {
        $cols = array_keys($data);
        $sql = 'INSERT INTO ' . static::$table . ' (' . implode(',', $cols) . ') VALUES (:' . implode(',:', $cols) . ')';
        $stmt = static::db()->prepare($sql); $stmt->execute($data);
        return (int) static::db()->lastInsertId();
    }
    public static function update(int $id, array $data): void {
        $sets = [];
        foreach ($data as $key => $value) $sets[] = "$key = :$key";
        $data['id'] = $id;
        static::db()->prepare('UPDATE ' . static::$table . ' SET ' . implode(',', $sets) . ' WHERE id = :id')->execute($data);
    }
    public static function delete(int $id): void {
        static::db()->prepare('DELETE FROM ' . static::$table . ' WHERE id = ?')->execute([$id]);
    }
}
