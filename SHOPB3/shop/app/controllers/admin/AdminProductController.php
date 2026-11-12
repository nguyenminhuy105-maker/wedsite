<?php
class AdminProductController extends AdminController {
    public function index(): void {
        $sql="SELECT p.*,c.name category_name,pi.file_name image,(SELECT COUNT(*) FROM product_images x WHERE x.product_id=p.id) image_count
              FROM products p JOIN categories c ON c.id=p.category_id
              LEFT JOIN product_images pi ON pi.product_id=p.id AND pi.is_primary=1 ORDER BY p.created_at DESC";
        $this->adminView('admin/product/index',['pageTitle'=>'Quản lý sản phẩm','products'=>Database::getInstance()->query($sql)->fetchAll()]);
    }
    public function add(): void {
        if($_SERVER['REQUEST_METHOD']==='POST') { verify_csrf($_POST['csrf_token']??''); [$data,$errors]=$this->validate($_POST,null);
            if($errors) { flash('error',implode(' | ',$errors)); $this->redirect('admin/product/add'); }
            $id=Product::insert($data); flash('success','Đã thêm sản phẩm. Tiếp theo hãy tải ảnh.'); $this->redirect('admin/product/edit/'.$id);
        }
        $this->adminView('admin/product/form',['pageTitle'=>'Thêm sản phẩm','product'=>null,'categories'=>Category::all('name ASC'),'images'=>[]]);
    }
    public function edit($id): void {
        $p=Product::find((int)$id); if(!$p) { flash('error','Không tìm thấy sản phẩm.'); $this->redirect('admin/product'); }
        if($_SERVER['REQUEST_METHOD']==='POST') { verify_csrf($_POST['csrf_token']??''); [$data,$errors]=$this->validate($_POST,(int)$id);
            if($errors) { flash('error',implode(' | ',$errors)); $this->redirect('admin/product/edit/'.$id); }
            Product::update((int)$id,$data); flash('success','Đã cập nhật sản phẩm.'); $this->redirect('admin/product/edit/'.$id);
        }
        $this->adminView('admin/product/form',['pageTitle'=>'Sửa sản phẩm','product'=>$p,'categories'=>Category::all('name ASC'),'images'=>ProductImage::forProduct((int)$id)]);
    }
    public function delete(): void {
        verify_csrf($_POST['csrf_token']??''); $id=(int)($_POST['id']??0);
        ProductImage::deleteAllForProduct($id); Product::delete($id); flash('success','Đã xóa sản phẩm và ảnh liên quan.'); $this->redirect('admin/product');
    }
    private function validate(array $in,?int $exclude): array {
        $errors=[]; $name=trim($in['name']??''); $sku=trim($in['sku']??''); $cat=(int)($in['category_id']??0);
        $price=(float)($in['price']??0); $stock=(int)($in['stock']??0);
        if($name==='') $errors[]='Tên sản phẩm không được để trống.';
        if($sku==='') $errors[]='SKU không được để trống.'; elseif(Product::skuExists($sku,$exclude)) $errors[]='SKU đã tồn tại.';
        if(!$cat||!Category::find($cat)) $errors[]='Danh mục không hợp lệ.';
        if($price<=0) $errors[]='Giá phải lớn hơn 0.'; if($stock<0) $errors[]='Tồn kho không được âm.';
        return [[ 'category_id'=>$cat,'sku'=>$sku,'name'=>$name,'brand'=>trim($in['brand']??''),'description'=>trim($in['description']??''),'price'=>$price,'stock'=>$stock,'is_featured'=>isset($in['is_featured'])?1:0,'status'=>in_array($in['status']??'',['active','inactive'],true)?$in['status']:'active' ],$errors];
    }
}
