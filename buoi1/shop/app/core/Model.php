<?php
// Model co so de cac Model khac ke thua.
abstract class Model {
    protected static string $table = '';
    protected static string $primaryKey = 'id';
    protected static function db(): PDO { return Database::getInstance(); }

    public static function all(string $orderBy = ''): array {
        // Chi truyen orderBy la cot co dinh trong code, khong nhan tu nguoi dung.
        $sql = 'SELECT * FROM ' . static::$table;
        if ($orderBy !== '') $sql .= ' ORDER BY ' . $orderBy;
        return static::db()->query($sql)->fetchAll();
    }
    public static function find($id): ?array {
        $stmt = static::db()->prepare('SELECT * FROM ' . static::$table . ' WHERE ' . static::$primaryKey . ' = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }
    public static function delete($id): bool {
        $stmt = static::db()->prepare('DELETE FROM ' . static::$table . ' WHERE ' . static::$primaryKey . ' = :id');
        return $stmt->execute(['id' => $id]);
    }
    public static function insert(array $data): int {
        if (!$data) throw new InvalidArgumentException('Du lieu them moi dang trong.');
        $columns = array_keys($data);
        foreach ($columns as $column) {
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) throw new InvalidArgumentException('Ten cot khong hop le.');
        }
        $placeholders = array_map(fn($c) => ':' . $c, $columns);
        $sql = 'INSERT INTO ' . static::$table . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')';
        $stmt = static::db()->prepare($sql);
        $stmt->execute($data);
        return (int) static::db()->lastInsertId();
    }
}
