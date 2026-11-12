<?php
class AdminCategoryController extends AdminController {
    public function index(): void {
        $sql='SELECT c.*,COUNT(p.id) product_count FROM categories c LEFT JOIN products p ON p.category_id=c.id GROUP BY c.id ORDER BY c.name';
        $rows=Database::getInstance()->query($sql)->fetchAll();
        $this->adminView('admin/category/index',['pageTitle'=>'Quản lý danh mục','categories'=>$rows]);
    }
    public function add(): void {
        if($_SERVER['REQUEST_METHOD']==='POST') { verify_csrf($_POST['csrf_token']??''); $name=trim($_POST['name']??'');
            if($name==='') { flash('error','Tên danh mục không được để trống.'); $this->redirect('admin/category/add'); }
            Category::insert(['name'=>$name,'slug'=>Category::slugify($name),'status'=>isset($_POST['status'])?'active':'inactive']);
            flash('success','Đã thêm danh mục.'); $this->redirect('admin/category');
        }
        $this->adminView('admin/category/form',['pageTitle'=>'Thêm danh mục','category'=>null]);
    }
    public function edit($id): void {
        $c=Category::find((int)$id); if(!$c) { flash('error','Không tìm thấy danh mục.'); $this->redirect('admin/category'); }
        if($_SERVER['REQUEST_METHOD']==='POST') { verify_csrf($_POST['csrf_token']??''); $name=trim($_POST['name']??'');
            if($name==='') { flash('error','Tên danh mục không được để trống.'); $this->redirect('admin/category/edit/'.$id); }
            Category::update((int)$id,['name'=>$name,'slug'=>Category::slugify($name),'status'=>isset($_POST['status'])?'active':'inactive']);
            flash('success','Đã cập nhật danh mục.'); $this->redirect('admin/category');
        }
        $this->adminView('admin/category/form',['pageTitle'=>'Sửa danh mục','category'=>$c]);
    }
    public function delete(): void {
        verify_csrf($_POST['csrf_token']??''); $id=(int)($_POST['id']??0);
        if(Category::countProducts($id)>0) { flash('error','Không thể xóa danh mục đang có sản phẩm.'); $this->redirect('admin/category'); }
        Category::delete($id); flash('success','Đã xóa danh mục.'); $this->redirect('admin/category');
    }
}
