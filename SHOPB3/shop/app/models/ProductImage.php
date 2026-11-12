<?php
class ProductImage extends Model {
    protected static string $table = 'product_images';
    public static function forProduct(int $pid): array {
        $s=self::db()->prepare('SELECT * FROM product_images WHERE product_id=? ORDER BY is_primary DESC,sort_order ASC,id ASC');
        $s->execute([$pid]); return $s->fetchAll();
    }
    public static function countForProduct(int $pid): int {
        $s=self::db()->prepare('SELECT COUNT(*) FROM product_images WHERE product_id=?'); $s->execute([$pid]); return (int)$s->fetchColumn();
    }
    public static function nextSortOrder(int $pid): int {
        $s=self::db()->prepare('SELECT COALESCE(MAX(sort_order),-1)+1 FROM product_images WHERE product_id=?'); $s->execute([$pid]); return (int)$s->fetchColumn();
    }
    public static function setPrimary(int $imageId,int $pid): void {
        $db=self::db(); $db->beginTransaction();
        try {
            $s=$db->prepare('SELECT COUNT(*) FROM product_images WHERE id=? AND product_id=?'); $s->execute([$imageId,$pid]);
            if (!(int)$s->fetchColumn()) { $db->rollBack(); return; }
            $db->prepare('UPDATE product_images SET is_primary=0 WHERE product_id=?')->execute([$pid]);
            $db->prepare('UPDATE product_images SET is_primary=1 WHERE id=? AND product_id=?')->execute([$imageId,$pid]);
            $db->commit();
        } catch(Throwable $e) { if($db->inTransaction()) $db->rollBack(); throw $e; }
    }
    public static function deleteImage(int $imageId,int $pid): void {
        $db=self::db(); $s=$db->prepare('SELECT * FROM product_images WHERE id=? AND product_id=?'); $s->execute([$imageId,$pid]); $img=$s->fetch();
        if(!$img) return;
        $db->beginTransaction();
        try {
            $db->prepare('DELETE FROM product_images WHERE id=? AND product_id=?')->execute([$imageId,$pid]);
            if((int)$img['is_primary']===1) {
                $q=$db->prepare('SELECT id FROM product_images WHERE product_id=? ORDER BY sort_order,id LIMIT 1'); $q->execute([$pid]); $next=$q->fetchColumn();
                if($next) $db->prepare('UPDATE product_images SET is_primary=1 WHERE id=? AND product_id=?')->execute([$next,$pid]);
            }
            $db->commit();
        } catch(Throwable $e) { if($db->inTransaction()) $db->rollBack(); throw $e; }
        ImageService::deleteFiles($img['file_name']);
    }
    public static function deleteAllForProduct(int $pid): void {
        $imgs=self::forProduct($pid);
        self::db()->prepare('DELETE FROM product_images WHERE product_id=?')->execute([$pid]);
        foreach($imgs as $img) ImageService::deleteFiles($img['file_name']);
    }
    public static function updateAlt(int $id,int $pid,string $alt): void {
        self::db()->prepare('UPDATE product_images SET alt_text=? WHERE id=? AND product_id=?')->execute([$alt,$id,$pid]);
    }
    public static function swapOrder(int $id,int $pid,string $direction): void {
        $items=array_values(array_filter(self::forProduct($pid),fn($x)=>(int)$x['is_primary']===0));
        $idx=null; foreach($items as $i=>$item) if((int)$item['id']===$id) {$idx=$i;break;}
        if($idx===null) return;
        $other=$direction==='up'?$idx-1:$idx+1; if(!isset($items[$other])) return;
        $db=self::db(); $db->beginTransaction();
        try {
            $db->prepare('UPDATE product_images SET sort_order=? WHERE id=? AND product_id=?')->execute([$items[$other]['sort_order'],$items[$idx]['id'],$pid]);
            $db->prepare('UPDATE product_images SET sort_order=? WHERE id=? AND product_id=?')->execute([$items[$idx]['sort_order'],$items[$other]['id'],$pid]);
            $db->commit();
        } catch(Throwable $e) { if($db->inTransaction()) $db->rollBack(); throw $e; }
    }
}
