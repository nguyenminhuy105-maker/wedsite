<?php
class AdminUserController extends AdminController {
    public function index(): void {
        $users=Database::getInstance()->query('SELECT id,name,email,role,status,created_at FROM users ORDER BY id DESC')->fetchAll();
        $this->adminView('admin/user/index',['pageTitle'=>'Quản lý người dùng','users'=>$users]);
    }
    public function update(): void {
        Auth::requireRole(['admin']); verify_csrf($_POST['csrf_token']??''); $id=(int)($_POST['user_id']??0);
        if($id!==(int)Auth::user()['id'] && in_array($_POST['role']??'',['admin','staff','customer'],true)) User::update($id,['role'=>$_POST['role']]);
        flash('success','Đã cập nhật vai trò.'); $this->redirect('admin/user');
    }
    public function status(): void {
        Auth::requireRole(['admin']); verify_csrf($_POST['csrf_token']??''); $id=(int)($_POST['user_id']??0);
        if($id!==(int)Auth::user()['id'] && in_array($_POST['status']??'',['active','locked'],true)) User::update($id,['status'=>$_POST['status']]);
        flash('success','Đã cập nhật trạng thái.'); $this->redirect('admin/user');
    }
}
