<?php
class AccountController extends Controller {
    public function profile(): void {
        Auth::requireLogin(); $user=User::find((int)Auth::user()['id']);
        if($_SERVER['REQUEST_METHOD']==='POST') {
            verify_csrf($_POST['csrf_token']??'');
            $name=trim($_POST['name']??'');
            if($name==='') { flash('error','Tên không được để trống.'); $this->redirect('account/profile'); }
            User::update((int)$user['id'],['name'=>$name]);
            $_SESSION['user']['name']=$name; flash('success','Đã cập nhật hồ sơ.'); $this->redirect('account/profile');
        }
        $this->view('account/profile',['pageTitle'=>'Hồ sơ cá nhân','user'=>$user]);
    }
}
