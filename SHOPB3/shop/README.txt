DỰ ÁN SHOP MVC - KẾT HỢP BUỔI 1 + BUỔI 2 + BUỔI 3

Yêu cầu:
- PHP 8.1+, MySQL, Apache (XAMPP)
- Bật extension: gd, fileinfo, pdo_mysql, mbstring
- mod_rewrite và AllowOverride All cho Apache
- Kết nối DB mặc định XAMPP: root, mật khẩu trống

CÀI ĐẶT:
1. Giải nén thư mục shop vào C:\xampp\htdocs\
2. Mở phpMyAdmin: http://localhost/phpmyadmin
3. Chọn Import và chạy database/schema.sql (script sẽ tạo DB shop_db và các bảng).
4. Mở config/config.php, kiểm tra DB_USER, DB_PASS và BASE_URL.
5. Khởi động Apache và MySQL.
6. Mở http://localhost/shop/public/

TÀI KHOẢN MẪU:
- Admin: admin@shop.local / admin123
- Staff: staff@shop.local / staff123
Mật khẩu đã được hash trong SQL.

ĐƯỜNG DẪN:
- Trang chủ: /public/
- Đăng ký: /public/auth/register
- Đăng nhập: /public/auth/login
- Hồ sơ: /public/account/profile
- Admin login: /public/admin/auth/login
- Dashboard: /public/admin/dashboard
- Danh mục: /public/admin/category
- Sản phẩm: /public/admin/product
- Người dùng: /public/admin/user

UPLOAD ẢNH:
- Tối đa 8 ảnh/sản phẩm, mỗi ảnh tối đa 2MB.
- Chỉ nhận jpg/jpeg/png/webp, cạnh ngắn tối thiểu 200px, cạnh dài tối đa 5000px.
- Ảnh được xử lý lại bằng GD và lưu JPEG bản chính + thumbnail.
- Ảnh tải lên nằm trong public/uploads/products/ và thumbs/.
- Không đưa ảnh tải lên lên Git; thư mục có .gitkeep.

LƯU Ý:
- Sao lưu CSDL trước khi import lại schema.sql vì các bảng hiện có cùng tên sẽ bị xóa.
- Sau khi thêm sản phẩm, hệ thống chuyển sang trang sửa để tải ảnh.
- Tài khoản đăng ký mới có vai trò customer.
- Chức năng CRUD danh mục/sản phẩm và ảnh theo nội dung Buổi 3.
- Bootstrap dùng CDN nên cần internet để tải giao diện.
- Nếu URL không chạy, kiểm tra mod_rewrite và AllowOverride All trong Apache.
