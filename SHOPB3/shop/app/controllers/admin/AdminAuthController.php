<?php
class AdminAuthController extends Controller {
    public function login(): void {
        if($_SERVER['REQUEST_METHOD']==='POST') {
            verify_csrf($_POST['csrf_token']??'');
            $u=User::findByEmail(trim($_POST['email']??''));
            if(!$u||!password_verify($_POST['password']??'',$u['password'])||$u['status']!=='active'||!in_array($u['role'],['admin','staff'],true)) {
                flash('error','Thông tin đăng nhập quản trị không hợp lệ.'); $this->redirect('admin/auth/login');
            }
            Auth::login($u); $this->redirect('admin/dashboard');
        }
        $this->view('auth/login',['pageTitle'=>'Đăng nhập quản trị']);
    }
}
