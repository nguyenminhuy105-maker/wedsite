<?php
class Product extends Model {
    protected static string $table = 'products';
    public static function skuExists(string $sku, ?int $excludeId = null): bool {
        $sql = 'SELECT COUNT(*) FROM products WHERE sku = ?'; $params = [$sku];
        if ($excludeId) { $sql .= ' AND id <> ?'; $params[] = $excludeId; }
        $s = self::db()->prepare($sql); $s->execute($params); return (int)$s->fetchColumn() > 0;
    }
    public static function homeProducts(): array {
        $sql = "SELECT p.*, c.name category_name, pi.file_name image
                FROM products p JOIN categories c ON c.id=p.category_id
                LEFT JOIN product_images pi ON pi.product_id=p.id AND pi.is_primary=1
                WHERE p.status='active' ORDER BY p.is_featured DESC,p.id DESC";
        return self::db()->query($sql)->fetchAll();
    }
}
