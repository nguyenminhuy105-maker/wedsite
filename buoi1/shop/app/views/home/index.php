<section class="hero p-4 p-md-5 mb-4 rounded-4">
    <div class="col-lg-8">
        <span class="badge text-bg-primary mb-2">SHOP ONLINE</span>
        <h1 class="display-6 fw-bold">Mua sắm dễ dàng mỗi ngày</h1>
        <p class="lead mb-0">Khám phá sản phẩm công nghệ, thời trang và đồ gia dụng với giao diện minh họa của bài thực hành MVC.</p>
    </div>
</section>
<div id="products" class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0">Sản phẩm mới nhất</h2><span class="text-secondary small"><?= count($products) ?> sản phẩm</span>
</div>
<?php if (empty($products)): ?>
    <div class="alert alert-info">Chưa có sản phẩm nào. Hãy chạy file database/schema.sql trước.</div>
<?php else: ?>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php foreach ($products as $p): ?>
            <div class="col"><article class="card product-card h-100">
                <img src="<?= e(product_image_url($p['image'] ?? null, 'thumb')) ?>" class="card-img-top" alt="<?= e($p['name']) ?>" loading="lazy">
                <div class="card-body d-flex flex-column">
                    <div class="text-secondary small mb-1"><?= e($p['sku']) ?></div>
                    <h3 class="h6 card-title"><?= e($p['name']) ?></h3>
                    <p class="small text-secondary flex-grow-1"><?= e($p['description']) ?></p>
                    <div class="d-flex justify-content-between align-items-center gap-2"><span class="text-danger fw-bold"><?= e(format_vnd($p['price'])) ?></span><span class="badge rounded-pill text-bg-light">Còn <?= (int)$p['stock'] ?></span></div>
                </div>
            </article></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
