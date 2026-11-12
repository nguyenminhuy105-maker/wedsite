<!doctype html>
<html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= e($pageTitle??'Shop MVC') ?></title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="<?= e(base_url('assets/css/style.css')) ?>"></head>
<body><nav class="navbar navbar-expand-lg"><div class="container"><a class="navbar-brand fw-bold" href="<?= e(base_url()) ?>">SHOP MVC</a><div class="navbar-nav ms-auto align-items-center"><a class="nav-link" href="<?= e(base_url()) ?>">Trang chủ</a>
<?php if(Auth::check()): ?><a class="nav-link" href="<?= e(base_url('account/profile')) ?>">Hồ sơ</a><?php if(in_array(Auth::user()['role'],['admin','staff'],true)): ?><a class="nav-link" href="<?= e(base_url('admin/dashboard')) ?>">Quản trị</a><?php endif; ?><form method="post" action="<?= e(base_url('auth/logout')) ?>" class="ms-2"><?= csrf_field() ?><button class="btn btn-sm btn-outline-light">Đăng xuất</button></form>
<?php else: ?><a class="nav-link" href="<?= e(base_url('auth/login')) ?>">Đăng nhập</a><a class="nav-link" href="<?= e(base_url('auth/register')) ?>">Đăng ký</a><?php endif; ?></div></div></nav>
<main class="container py-4">
<?php foreach(($_SESSION['flash']??[]) as $f): ?><div class="alert alert-<?= e($f['type']==='error'?'danger':$f['type']) ?>"><?= $f['message'] ?></div><?php endforeach; $_SESSION['flash']=[]; ?>
<?= $content ?>
</main><footer class="text-center text-muted py-4">Website thực hành PHP MVC - Buổi 1, 2 và 3</footer></body></html>
