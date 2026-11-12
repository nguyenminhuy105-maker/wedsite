<?php
class AuthController extends Controller {
    public function register(): void {
        if($_SERVER['REQUEST_METHOD']==='POST') {
            verify_csrf($_POST['csrf_token']??'');
            $name=trim($_POST['name']??''); $email=trim($_POST['email']??''); $pass=$_POST['password']??'';
            if($name===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($pass)<6||User::findByEmail($email)) {
                flash('error','Thông tin chưa hợp lệ, email đã tồn tại hoặc mật khẩu dưới 6 ký tự.'); $this->redirect('auth/register');
            }
            $id=User::insert(['name'=>$name,'email'=>$email,'password'=>password_hash($pass,PASSWORD_DEFAULT),'role'=>'customer','status'=>'active']);
            Auth::login(User::find($id)); $this->redirect('');
        }
        $this->view('auth/register',['pageTitle'=>'Đăng ký']);
    }
    public function login(): void {
        if($_SERVER['REQUEST_METHOD']==='POST') {
            verify_csrf($_POST['csrf_token']??'');
            $u=User::findByEmail(trim($_POST['email']??''));
            if(!$u||!password_verify($_POST['password']??'',$u['password'])||$u['status']!=='active') {
                flash('error','Email hoặc mật khẩu không đúng, hoặc tài khoản đã khóa.'); $this->redirect('auth/login');
            }
            Auth::login($u); $this->redirect('');
        }
        $this->view('auth/login',['pageTitle'=>'Đăng nhập']);
    }
    public function logout(): void {
        if($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405); exit('Method not allowed'); }
        verify_csrf($_POST['csrf_token']??''); Auth::logout(); $this->redirect('');
    }
}
