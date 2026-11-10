<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' - ' : '' ?>Shop Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(asset('css/style.css')) ?>" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<header class="border-bottom shadow-sm bg-white">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= e(base_url('')) ?>">Shop<span class="text-primary">Online</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Mở menu"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto"><li class="nav-item"><a class="nav-link" href="<?= e(base_url('')) ?>">Trang chủ</a></li><li class="nav-item"><a class="nav-link" href="#products">Sản phẩm</a></li></ul>
            </div>
        </div>
    </nav>
</header>
<main class="container py-4 flex-grow-1"><?php $content(); ?></main>
<footer class="bg-dark text-white text-center py-3 mt-auto"><div class="container small">© <?= date('Y') ?> Shop Online - Website thực hành PHP MVC</div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
