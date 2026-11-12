-- Buoi 1: tao CSDL va du lieu mau cho website Shop Online.
CREATE DATABASE IF NOT EXISTS shop_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shop_db;
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','staff','customer') NOT NULL DEFAULT 'customer',
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS categories (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  slug VARCHAR(160) NOT NULL UNIQUE,
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS products (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id BIGINT UNSIGNED NOT NULL,
  sku VARCHAR(40) NOT NULL UNIQUE,
  name VARCHAR(180) NOT NULL,
  brand VARCHAR(120) NOT NULL DEFAULT '',
  description TEXT NULL,
  price DECIMAL(12,0) NOT NULL DEFAULT 0,
  stock INT UNSIGNED NOT NULL DEFAULT 0,
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_products_status_created (status, created_at),
  INDEX idx_products_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS product_images (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id BIGINT UNSIGNED NOT NULL,
  file_name VARCHAR(255) NOT NULL,
  original_name VARCHAR(255) NOT NULL DEFAULT '',
  alt_text VARCHAR(255) NOT NULL DEFAULT '',
  is_primary TINYINT(1) NOT NULL DEFAULT 0,
  sort_order INT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_images_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  INDEX idx_product_sort (product_id, sort_order),
  INDEX idx_product_primary (product_id, is_primary)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Du lieu mau: 5 danh muc
INSERT IGNORE INTO categories (id, name, slug, status) VALUES
(1,'Điện thoại - Máy tính bảng','dien-thoai-may-tinh-bang','active'),
(2,'Thời trang nam','thoi-trang-nam','active'),
(3,'Thời trang nữ','thoi-trang-nu','active'),
(4,'Giày dép','giay-dep','active'),
(5,'Đồ gia dụng','do-gia-dung','active');

-- 20 san pham; khong luu cot anh trong bang products.
INSERT IGNORE INTO products (id, category_id, sku, name, brand, description, price, stock, is_featured, status) VALUES
(1,1,'SP0001','Điện thoại Smart X10','SmartTech','Màn hình 6.5 inch, pin 5000mAh, camera 50MP.',4990000,50,1,'active'),
(2,1,'SP0002','Điện thoại Smart X10 Pro','SmartTech','Bản nâng cấp RAM 8GB, bộ nhớ 256GB.',7990000,30,1,'active'),
(3,1,'SP0003','Máy tính bảng TabOne 10','TabCo','Màn hình 10.1 inch Full HD, hỗ trợ bút cảm ứng.',3590000,25,0,'active'),
(4,1,'SP0004','Điện thoại Mini S','SmartTech','Thiết kế nhỏ gọn, phù hợp học sinh sinh viên.',2290000,60,0,'active'),
(5,1,'SP0005','Máy tính bảng TabOne Lite','TabCo','Phiên bản phổ thông, giá tốt, phù hợp giải trí.',2890000,40,0,'active'),
(6,2,'SP0006','Áo sơ mi nam dài tay','FashionMen','Chất liệu cotton thoáng mát, form slimfit.',259000,100,1,'active'),
(7,2,'SP0007','Quần tây nam công sở','FashionMen','Vải kaki co giãn nhẹ, không nhăn.',329000,80,0,'active'),
(8,2,'SP0008','Áo thun nam cổ tròn','FashionMen','Cotton 100%, nhiều màu lựa chọn.',159000,150,1,'active'),
(9,2,'SP0009','Áo khoác nam chống nước','FashionMen','Chống thấm nước nhẹ, phù hợp đi xe máy.',459000,45,0,'active'),
(10,3,'SP0010','Váy liền thân nữ','FashionWomen','Thiết kế trẻ trung, phù hợp đi làm và dạo phố.',499000,50,1,'active'),
(11,3,'SP0011','Áo kiểu nữ tay bồng','FashionWomen','Chất liệu mềm mại, kiểu dáng thanh lịch.',279000,90,0,'active'),
(12,3,'SP0012','Chân váy xếp ly','FashionWomen','Phong cách công sở thanh lịch.',249000,65,0,'active'),
(13,3,'SP0013','Áo len nữ cổ lọ','FashionWomen','Giữ ấm tốt, chất len mềm không xù lông.',319000,55,0,'active'),
(14,4,'SP0014','Giày sneaker thể thao nam','SportFeet','Đế cao su chống trượt, đệm êm chân.',589000,60,1,'active'),
(15,4,'SP0015','Giày cao gót nữ 7cm','StyleWalk','Thiết kế mũi nhọn thanh lịch, đế êm.',399000,40,0,'active'),
(16,4,'SP0016','Dép sandal nam quai ngang','SportFeet','Phù hợp đi biển, đi học, đi làm.',199000,85,0,'active'),
(17,4,'SP0017','Giày thể thao nữ dệt lưới','StyleWalk','Thoáng khí, trọng lượng nhẹ.',479000,50,0,'active'),
(18,5,'SP0018','Nồi cơm điện 1.8L','HomeLife','Lòng nồi chống dính, nấu nhanh tiết kiệm điện.',629000,35,1,'active'),
(19,5,'SP0019','Máy xay sinh tố đa năng','HomeLife','Công suất 500W, cối xay inox 304.',459000,45,0,'active'),
(20,5,'SP0020','Bộ nồi inox 5 món','HomeLife','Đáy từ 3 lớp, dùng được cho mọi loại bếp.',890000,20,0,'active');
