# Shop Online - Buoi 1: PHP MVC + MySQL

Dự án thực hành bám theo tài liệu PDF Buổi 1: dựng khung MVC, kết nối MySQL bằng PDO, thiết kế 4 bảng và hiển thị 20 sản phẩm trên trang chủ. Đây chưa phải website có giỏ hàng/đăng nhập/CRUD; các phần đó thuộc các buổi sau.

## Cấu trúc chính
- `config/config.php`: cấu hình kết nối và URL.
- `app/core/`: helpers, Database singleton, Model, Controller, Router.
- `app/controllers/HomeController.php`: lấy danh sách sản phẩm và ảnh đại diện bằng `LEFT JOIN`.
- `app/views/`: layout, trang chủ, lỗi 404.
- `public/`: front controller, `.htaccess`, CSS và nơi lưu ảnh công khai.
- `database/schema.sql`: tạo `shop_db`, 4 bảng và 20 sản phẩm mẫu.
- `database/seed_images.php`: tạo ảnh demo bằng GD, cố ý để sản phẩm ID 3 và 17 không có ảnh.

## Cách chạy với XAMPP
1. Giải nén thư mục `shop_project` và đổi tên thành `shop`.
2. Chép thư mục `shop` vào `C:\xampp\htdocs\shop`.
3. Mở XAMPP, bật Apache và MySQL.
4. Vào `http://localhost/phpmyadmin`, mở tab SQL và chạy toàn bộ `database/schema.sql`.
5. Mở `config/config.php`, kiểm tra `DB_USER`, `DB_PASS`, `DB_NAME` và `BASE_URL`. Với XAMPP mặc định: root, mật khẩu rỗng, `shop_db`, `http://localhost/shop/public/`.
6. Trong `php.ini`, bật `extension=gd` và `extension=pdo_mysql` nếu đang tắt; khởi động lại Apache.
7. Truy cập `http://localhost/shop/database/seed_images.php` một lần để tạo ảnh minh họa.
8. Truy cập `http://localhost/shop/public/`.

## Ghi chú
- Bootstrap 5 được nạp qua CDN, nên cần Internet để hiển thị đầy đủ kiểu Bootstrap.
- Nếu URL thân thiện không chạy, kiểm tra Apache đã bật `mod_rewrite` và `AllowOverride All`; có thể thử tạm `http://localhost/shop/public/index.php?url=home/index`.
- `product_images` là bảng riêng vì mỗi sản phẩm có thể có nhiều ảnh. Trang chủ dùng `LEFT JOIN` để sản phẩm chưa có ảnh vẫn xuất hiện và hàm `product_image_url()` trả ảnh mặc định.
- Tệp `database/seed_images.php` chỉ dùng cho dữ liệu demo; không để công khai trên website thật sau khi chạy xong.
