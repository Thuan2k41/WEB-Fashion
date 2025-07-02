-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 23, 2025 lúc 01:50 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `web-fashion`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `detail` varchar(255) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `address`
--

INSERT INTO `address` (`id`, `user_id`, `detail`, `ward`, `district`, `province`, `is_default`) VALUES
(14, 7, 'so 43', 'Xã An Bá', 'Huyện Sơn Động', 'Tỉnh Bắc Giang', 1),
(16, 19, 'dd', 'Phường Phúc Xá', 'Quận Ba Đình', 'Thành phố Hà Nội', 1),
(17, 6, 'Ngõ 30,Ngô Quyền', 'Phường Phúc Xá', 'Quận Ba Đình', 'Thành phố Hà Nội', 1),
(18, 24, 'Ngõ 30,Ngô Quyền', 'Phường La Khê', 'Quận Hà Đông', 'Thành phố Hà Nội', 1),
(21, 28, 'ngo 30 ', 'Phường Phương Liệt', 'Quận Thanh Xuân', 'Thành phố Hà Nội', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(50) NOT NULL DEFAULT 1,
  `size_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `size_id`, `created_at`) VALUES
(25, 6, 7, 1, 8, '2025-05-09 03:27:55'),
(27, 6, 6, 1, 8, '2025-05-09 03:35:23'),
(28, 6, 1, 1, 3, '2025-05-09 03:35:33'),
(29, 6, 6, 1, 7, '2025-05-09 03:39:22'),
(91, 24, 14, 1, 3, '2025-05-25 03:45:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Nam'),
(2, 'Nữ');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `collections`
--

CREATE TABLE `collections` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `collections`
--

INSERT INTO `collections` (`id`, `name`) VALUES
(1, 'LILAS DREAM'),
(2, 'ROSIE CRUSH');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `total_price` decimal(10,0) NOT NULL,
  `status` enum('pending','shipped','completed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(30) NOT NULL DEFAULT 'Thanh toán khi nhận hàng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `total_price`, `status`, `payment_method`) VALUES
(1, 24, '2025-05-09', 2580000, 'pending', 'Thanh toán khi nhận hàng'),
(2, 24, '2025-05-09', 1190000, 'pending', 'Thanh toán khi nhận hàng'),
(3, 24, '2025-05-09', 699000, 'pending', 'Thanh toán khi nhận hàng'),
(4, 24, '2025-05-09', 1490000, 'pending', 'Thanh toán khi nhận hàng'),
(7, 24, '2025-05-09', 699000, 'pending', 'Thanh toán khi nhận hàng'),
(8, 24, '2025-05-09', 1190000, 'pending', 'Thanh toán khi nhận hàng'),
(24, 24, '2025-05-09', 699000, 'pending', 'Thanh toán khi nhận hàng'),
(25, 24, '2025-05-09', 1798000, 'pending', 'Thanh toán khi nhận hàng'),
(26, 24, '2025-05-09', 699000, 'pending', 'Thanh toán khi nhận hàng'),
(27, 24, '2025-05-10', 1190000, 'pending', 'Thanh toán khi nhận hàng'),
(28, 24, '2025-05-11', 1890000, 'pending', 'Thanh toán khi nhận hàng'),
(29, 24, '2025-05-11', 1890000, 'pending', 'Thanh toán khi nhận hàng'),
(30, 24, '2025-05-11', 1890000, 'pending', 'Thanh toán khi nhận hàng'),
(31, 24, '2025-05-11', 1890000, 'pending', 'Thanh toán khi nhận hàng'),
(32, 24, '2025-05-11', 3080000, 'pending', 'Thanh toán khi nhận hàng'),
(33, 24, '2025-05-11', 1990000, 'pending', 'Thanh toán khi nhận hàng'),
(34, 24, '2025-05-11', 3280000, 'pending', 'Thanh toán khi nhận hàng'),
(35, 24, '2025-05-11', 3280000, 'pending', 'Thanh toán khi nhận hàng'),
(36, 24, '2025-05-11', 3280000, 'pending', 'Thanh toán khi nhận hàng'),
(37, 24, '2025-05-11', 3280000, 'pending', 'Thanh toán khi nhận hàng'),
(38, 24, '2025-05-11', 3280000, 'pending', 'Thanh toán khi nhận hàng'),
(39, 24, '2025-05-11', 3280000, 'pending', 'Thanh toán khi nhận hàng'),
(40, 24, '2025-05-11', 3280000, 'pending', 'Thanh toán khi nhận hàng'),
(41, 24, '2025-05-11', 3280000, 'pending', 'Thanh toán khi nhận hàng'),
(42, 24, '2025-05-11', 3280000, 'pending', 'Thanh toán khi nhận hàng'),
(43, 24, '2025-05-11', 3280000, 'pending', 'Thanh toán khi nhận hàng'),
(44, 24, '2025-05-11', 3280000, 'pending', 'Thanh toán khi nhận hàng'),
(45, 24, '2025-05-11', 3280000, 'pending', 'Thanh toán khi nhận hàng'),
(46, 24, '2025-05-11', 3280000, 'pending', 'Thanh toán khi nhận hàng'),
(47, 24, '2025-05-11', 1990000, 'pending', 'Thanh toán khi nhận hàng'),
(48, 24, '2025-05-11', 1190000, 'pending', 'Thanh toán khi nhận hàng'),
(49, 24, '2025-05-11', 1190000, 'pending', 'Thanh toán khi nhận hàng'),
(50, 24, '2025-05-11', 1190000, 'pending', 'Thanh toán khi nhận hàng'),
(51, 24, '2025-05-11', 1190000, 'pending', 'Thanh toán khi nhận hàng'),
(52, 24, '2025-05-11', 1190000, 'pending', 'Thanh toán khi nhận hàng'),
(53, 24, '2025-05-11', 1190000, 'pending', 'Thanh toán khi nhận hàng'),
(54, 24, '2025-05-11', 1190000, 'pending', 'Thanh toán khi nhận hàng'),
(55, 24, '2025-05-11', 699000, 'pending', 'Thanh toán khi nhận hàng'),
(56, 24, '2025-05-11', 1190000, 'pending', 'Thanh toán khi nhận hàng'),
(57, 24, '2025-05-11', 699000, 'pending', 'Thanh toán khi nhận hàng'),
(60, 19, '2025-05-12', 3180000, 'pending', 'Thanh toán khi nhận hàng'),
(61, 24, '2025-05-13', 1290000, 'pending', 'Thanh toán khi nhận hàng'),
(62, 24, '2025-05-13', 1290000, 'pending', 'Thanh toán khi nhận hàng'),
(63, 24, '2025-05-14', 1490000, 'pending', 'Thanh toán khi nhận hàng'),
(64, 28, '2025-05-14', 1290000, 'pending', 'Thanh toán khi nhận hàng'),
(65, 24, '2025-05-24', 2889000, 'pending', 'Thanh toán khi nhận hàng'),
(66, 24, '2025-05-24', 1890000, 'pending', 'Thanh toán khi nhận hàng'),
(67, 24, '2025-05-24', 1890000, 'pending', 'Thanh toán khi nhận hàng'),
(68, 24, '2025-05-24', 1490000, 'pending', 'Thanh toán khi nhận hàng'),
(69, 19, '2025-06-09', 8650000, 'pending', 'Thanh toán khi nhận hàng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `size_id`, `quantity`, `price`) VALUES
(1, 24, 3, 3, 1, 699000),
(2, 25, 2, 4, 2, 699000),
(3, 25, 4, 3, 2, 200000),
(4, 26, 3, 3, 1, 699000),
(5, 27, 8, 8, 1, 1190000),
(11, 28, 7, 7, 1, 1190000),
(12, 33, 17, 2, 1, 1990000),
(13, 47, 17, 2, 1, 1990000),
(14, 48, 8, 8, 1, 1190000),
(15, 49, 8, 7, 1, 1190000),
(16, 55, 2, 3, 1, 699000),
(19, 60, 7, 8, 1, 1190000),
(20, 60, 17, 3, 1, 1990000),
(21, 61, 18, 3, 1, 1290000),
(22, 62, 18, 2, 1, 1290000),
(23, 63, 15, 3, 1, 1490000),
(24, 64, 18, 2, 1, 1290000),
(25, 65, 2, 2, 1, 699000),
(26, 65, 13, 3, 1, 2190000),
(27, 66, 14, 3, 1, 1890000),
(28, 68, 15, 2, 1, 1490000),
(29, 69, 15, 3, 1, 1490000),
(30, 69, 15, 2, 2, 1490000),
(31, 69, 17, 4, 1, 1990000),
(32, 69, 13, 2, 1, 2190000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `quantity` int(100) NOT NULL,
  `subcategory` int(11) NOT NULL,
  `description` text NOT NULL,
  `tag` enum('new','sale','none') NOT NULL DEFAULT 'none',
  `sold` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `collection_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `quantity`, `subcategory`, `description`, `tag`, `sold`, `created_at`, `collection_id`) VALUES
(1, 'Áo thun Your Dream', 299000, 55, 1, '- Áo cổ tròn, tay cộc, kiểu dáng Slim fit, độ dài vừa phải. Thiết kế phù hợp với quý anh yêu thích vẻ ngoài hiện đại, trẻ trung, năng động. \r\n- Thân trước áo tạo điểm nhấn mây in 3D sắc nét với chủ đề Your Dream, bắt mắt người nhìn. \r\n- Sản phẩm được sản xuất từ Ice Cotton, một chất liệu phát triển bởi SPOERRY Thụy Sĩ. Vải có độ xoắn cao, lông tơ sẽ bị triệt tiêu, mang đến cảm giác \"mát lạnh\" ngay điểm chạm đầu tiên trên da.\r\n- Thấm hút mồ hôi tốt, mang đến cảm giác mặc thoải mái, thích hợp với các hoạt động ngoài trời.', 'new', 10, '2025-04-01 12:00:00', NULL),
(2, 'Áo thun Regular Supima Classic', 699000, 50, 1, '- Áo cổ tròn\n- Tay áo ngắn\n- Kiểu dáng Regular\n- Có hình in năng động và bắt mắt\n- Chất liệu Supima Cotton 100% thành phần tự nhiên\n- Cảm giác mặc \"mát lạnh\"\n- Mềm mịn, chống nhăn\n- Giữ màu lâu, bền bỉ\n- Có khả năng chống co rút gấp 5 lần so với Cotton thông thường.\n- Đặc biệt, thấm hút mồ hôi tốt', 'new', 5, '2025-04-02 14:30:00', NULL),
(3, 'Áo thun Regular Meta Supima', 699000, 55, 1, '- Áo cổ tròn\n- Tay áo ngắn\n- Kiểu dáng Regular\n- Có hình in năng động và bắt mắt\n- Chất liệu Supima Cotton 100% thành phần tự nhiên\n- Cảm giác mặc \"mát lạnh\"\n- Mềm mịn, chống nhăn\n- Giữ màu lâu, bền bỉ\n- Có khả năng chống co rút gấp 5 lần so với Cotton thông thường.\n- Đặc biệt, thấm hút mồ hôi tốt', 'new', 4, '2025-04-03 10:15:00', NULL),
(4, 'Áo thun in hình', 200000, 55, 1, '- Áo thun basic dáng regular fit, tay ngắn, cổ tròn. Ngực in hình thiết kế\n\r\n- Chất liệu thun mềm mại, thấm hút mồ hôi tốt\n\r\nLưu ý: Màu sắc sản phẩm thực tế sẽ có sự chênh lệch nhỏ so với ảnh do điều kiện ánh sáng khi chụp và màu sắc hiển thị qua mản hình máy tính/ điện thoại.', 'new', 2, '2025-04-04 16:45:00', NULL),
(5, 'Quần Jeans Trơn Regular Fit', 1190000, 50, 2, 'Lưu ý: Màu sắc sản phẩm thực tế sẽ có sự chênh lệch nhỏ so với ảnh do điều kiện ánh sáng khi chụp và màu sắc hiển thị qua màn hình máy tính/ điện thoại.', 'new', 1, '2025-04-12 23:20:27', NULL),
(6, 'Craft Jeans - Quần Jeans Slim Fit', 1190000, 50, 2, '- Quần Jeans basic\n- Độ dài qua mắt cá chân\n- Tạo điểm nhấn qua các đường chỉ nổi khác màu\n- Thiết kế 4 túi\n- Cạp cài khuy kèm đai đeo thắt lưng\n- Khóa YKK, được sản xuất riêng\n- Chất liệu Denim tuyển chọn', 'new', 4, '2025-04-12 23:20:27', NULL),
(7, 'Quần Jeans Xanh Mài Regular Fit', 1190000, 45, 2, 'Lưu ý: Màu sắc sản phẩm thực tế sẽ có sự chênh lệch nhỏ so với ảnh do điều kiện ánh sáng khi chụp và màu sắc hiển thị qua màn hình máy tính/ điện thoại.', 'new', 5, '2025-04-12 23:20:27', NULL),
(8, 'Quần Jeans Regular Smart', 1190000, 45, 2, '- Quần Jeans basic\r\n- Độ dài qua mắt cá chân', 'new', 3, '2025-04-12 23:20:27', NULL),
(9, 'Áo Khoác Dạ Lông Cừu', 3499500, 40, 3, 'Lưu ý: Màu sắc sản phẩm thực tế sẽ có sự chênh lệch nhỏ so với ảnh do điều kiện ánh sáng khi chụp và màu sắc hiển thị qua màn hình máy tính/ điện thoại.', 'sale', 0, '2025-04-12 23:20:27', NULL),
(10, 'Suede Jacket - Áo khoác sơ mi da lộn', 500000, 50, 3, '- Chất liệu da lộn mềm mại, đều màu, mịn như nhung, đem đến cho người mặc phong cách trẻ trung, cá tính và cảm giác ấm áp vào mùa đông\r\n- Thiết kế áo khoác kiểu dáng sơ mi: cổ đức, áo phối 2 túi hộp, cài bằng hàng khuy phía trước', 'sale', 0, '2025-04-12 23:20:27', NULL),
(11, 'Wool Jacket - Áo khoác sơ mi dạ lông cừu', 2999500, 50, 3, 'Chất liệu 100% lông cừu cao cấp đã qua kiểm định chất lượng nghiêm ngặt với công đoạn xử lý thủ công hàng trăm giờ đồng hồ. Chất vải dạ ép từ những sợi lông cừu được chất lọc ở những vị trí lông tốt nhất. Đặc tính chất liệu mềm, mỏng, nhẹ và giữ ấm tuyệt đối; khả năng giữ phom dáng và tuổi thọ của sản phẩm cao.', 'sale', 0, '2025-04-12 23:20:27', NULL),
(12, 'Áo Hoodie nỉ cổ mũ kéo khóa', 400000, 50, 3, '- Chất liệu: Sử dụng vải Interlock thành phần gồm 65% Cotton, 30% Polyester, 5% Spandex. Đây là sự kết hợp giữa sợi cotton và sợi chiết suất tổng hợp có độ co giãn đàn hồi cao. Tính năng chống gió, cách nhiệt giữ ấm cơ thể.', 'sale', 0, '2025-04-12 23:20:27', NULL),
(13, 'White Flare Dress - Đầm xòe Tuytsi', 2190000, 40, 4, 'White Flare Dress mang đến vẻ đẹp thanh lịch và tinh tế với thiết kế ôm nhẹ thân trên kết hợp dáng chân xòe nhẹ nhàng.\n\nChất liệu tuytsi cao cấp cùng họa tiết vân hoa tinh tế không chỉ tạo hiệu ứng thị giác đẹp mắt mà còn mang lại cảm giác thoải mái khi mặc.', 'new', 2, '2025-04-12 23:20:27', NULL),
(14, 'Midsummer Glow Dress - Đầm Lụa Tay Hến', 1890000, 50, 4, 'Giữa ngày hè rực rỡ ngập tràn ánh nắng, Midsummer Glow Dress mang đến vẻ đẹp nữ tính, thanh thoát nhưng vẫn đầy cuốn hút. Với chất liệu lụa mềm mại và thiết kế tối giản, chiếc đầm giúp nàng tỏa sáng tự nhiên như ánh hoàng hôn mùa hè.', 'new', 1, '2025-04-12 23:20:27', 1),
(15, 'Đầm Lụa Xoè Bloom Cách Điệu', 1490000, 50, 4, 'Thiết kế nằm trong BST Dreamy Bloom, người bạn đồng hành hoàn hảo của phái đẹp. Đầm lụa cách điệu là sự lựa chọn hoàn hảo để bạn luôn tự tin và nổi bật với vẻ đẹp thanh lịch, cuốn hút và đầy phong cách!', 'new', 28, '2025-04-12 23:20:27', 2),
(16, 'Đầm Hai Dây Nhún Ngực', 1690000, 50, 4, 'Thiết kế được may trên nền vải linen mềm mại, ôm nhẹ lấy cơ thể, tạo cảm giác êm ái và thoải mái mỗi khi diện. Kiểu dáng maxi mang đến vẻ ngoài sang trọng, với thiết kế hai dây cho phép bạn thoải mái di chuyển và thuận tiện cho mọi chuyển động.', 'new', 0, '2025-04-12 23:20:27', NULL),
(17, 'Sonata Vest - Áo Vest Ôm Tuytsi', 1990000, 50, 5, 'Bên cạnh những thiết kế có hoạ tiết bắt mắt, BST Blue Sonata còn chinh phục phái đẹp bằng sự tối giản vô cùng cuốn hút! Với gam màu trung tính, những items như đầm, chân váy hay combo áo vest - quần suông vẫn có thể phối linh hoạt trong nhiều dịp.', 'new', 4, '2025-04-12 23:20:27', NULL),
(18, 'Chân váy xếp ly Youthful', 1290000, 50, 6, 'Youthful Set với thiết kế trẻ trung, hiện đại nhưng vẫn thời thượng và sang trọng dành cho nàng công sở thêm vào tủ đồ Xuân - Hè của mình. Set bộ gồm áo vest kiểu kết hợp cùng chân váy xếp ly.\r\n\r\nChân váy xếp ly độ dài qua gối, tạo độ bồng nhẹ, đặc biệt che khuyết điểm cực tốt. Thiết kế mix cùng MS', 'new', 3, '2025-04-12 23:20:27', 2),
(30, 'Áo Gile Tuysi Cổ kiểu', 1390000, 50, 5, 'Thiết kế công sở hiện đại, ghi dấu ấn với tính thẩm mỹ cao cùng sự tinh tế qua các chi tiết tạo điểm nhấn mà không mất đi nét sang trọng vốn có.', 'sale', 0, '2025-05-14 11:11:09', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_url` text NOT NULL,
  `alt_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `alt_text`) VALUES
(1, 1, 'img/men/aothun/thun1.jpg', 'Áo thun in hình - 1'),
(2, 1, 'img/men/aothun/thun2.jpg', 'Áo thun in hình - 2'),
(3, 1, 'img/men/aothun/thun3.jpg', 'Áo thun in hình - 3'),
(4, 1, 'img/men/aothun/thun4.jpg', 'Áo thun in hình - 4'),
(5, 2, 'img/men/aothun/thun5.jpg', 'Áo thun sản phẩm 2 - 1'),
(6, 2, 'img/men/aothun/thun6.jpg', 'Áo thun sản phẩm 2 - 2'),
(7, 2, 'img/men/aothun/thun7.jpg', 'Áo thun sản phẩm 2 - 3'),
(8, 2, 'img/men/aothun/thun8.jpg', 'Áo thun sản phẩm 2 - 4'),
(9, 3, 'img/men/aothun/thun9.jpg', 'Áo thun sản phẩm 3 - 1'),
(10, 3, 'img/men/aothun/thun10.jpg', 'Áo thun sản phẩm 3 - 2'),
(11, 3, 'img/men/aothun/thun11.jpg', 'Áo thun sản phẩm 3 - 3'),
(12, 3, 'img/men/aothun/thun12.jpg', 'Áo thun sản phẩm 3 - 4'),
(13, 4, 'img/men/aothun/thun13.jpg', 'Áo thun sản phẩm 4 - 1'),
(14, 4, 'img/men/aothun/thun14.jpg', 'Áo thun sản phẩm 4 - 2'),
(15, 4, 'img/men/aothun/thun15.jpg', 'Áo thun sản phẩm 4 - 3'),
(16, 4, 'img/men/aothun/thun16.jpg', 'Áo thun sản phẩm 4 - 4'),
(17, 5, 'img/men/quanjeans/jean1.jpg', 'Quần Jeans - 1'),
(18, 5, 'img/men/quanjeans/jean2.jpg', 'Quần Jeans - 2'),
(19, 5, 'img/men/quanjeans/jean3.jpg', 'Quần Jeans - 3'),
(20, 5, 'img/men/quanjeans/jean4.jpg', 'Quần Jeans - 4'),
(21, 6, 'img/men/quanjeans/jean5.jpg', 'Quần Jeans - 1'),
(22, 6, 'img/men/quanjeans/jean6.jpg', 'Quần Jeans - 2'),
(23, 6, 'img/men/quanjeans/jean7.jpg', 'Quần Jeans - 3'),
(24, 6, 'img/men/quanjeans/jean8.jpg', 'Quần Jeans - 4'),
(25, 7, 'img/men/quanjeans/jean9.jpg', 'Quần Jeans - 1'),
(26, 7, 'img/men/quanjeans/jean10.jpg', 'Quần Jeans - 2'),
(27, 7, 'img/men/quanjeans/jean11.jpg', 'Quần Jeans - 3'),
(28, 7, 'img/men/quanjeans/jean12.jpg', 'Quần Jeans - 4'),
(29, 8, 'img/men/quanjeans/jean13.jpg', 'Quần Jeans - 1'),
(30, 8, 'img/men/quanjeans/jean14.jpg', 'Quần Jeans - 2'),
(31, 8, 'img/men/quanjeans/jean15.jpg', 'Quần Jeans - 3'),
(32, 8, 'img/men/quanjeans/jean16.jpg', 'Quần Jeans - 4'),
(33, 9, 'img/men/aokhoac/khoac1.jpg', 'Ảnh 1 - Áo khoác dạ'),
(34, 9, 'img/men/aokhoac/khoac2.jpg', 'Ảnh 2 - Áo khoác dạ'),
(35, 9, 'img/men/aokhoac/khoac3.jpg', 'Ảnh 3 - Áo khoác dạ'),
(36, 9, 'img/men/aokhoac/khoac4.jpg', 'Ảnh 4 - Áo khoác dạ'),
(37, 10, 'img/men/aokhoac/khoac5.jpg', 'Ảnh 1 - Suede Jacket'),
(38, 10, 'img/men/aokhoac/khoac6.jpg', 'Ảnh 2 - Suede Jacket'),
(39, 10, 'img/men/aokhoac/khoac7.jpg', 'Ảnh 3 - Suede Jacket'),
(40, 10, 'img/men/aokhoac/khoac8.jpg', 'Ảnh 4 - Suede Jacket'),
(41, 11, 'img/men/aokhoac/khoac9.jpg', 'Ảnh 1 - Wool Jacket'),
(42, 11, 'img/men/aokhoac/khoac10.jpg', 'Ảnh 2 - Wool Jacket'),
(43, 11, 'img/men/aokhoac/khoac11.jpg', 'Ảnh 3 - Wool Jacket'),
(44, 11, 'img/men/aokhoac/khoac12.jpg', 'Ảnh 4 - Wool Jacket'),
(45, 12, 'img/men/aokhoac/khoac13.jpg', 'Áo Hoodie - Ảnh 1'),
(46, 12, 'img/men/aokhoac/khoac14.jpg', 'Áo Hoodie - Ảnh 2'),
(47, 12, 'img/men/aokhoac/khoac15.jpg', 'Áo Hoodie - Ảnh 3'),
(48, 12, 'img/men/aokhoac/khoac16.jpg', 'Áo Hoodie - Ảnh 4'),
(49, 13, 'img/women/dam/dam1.webp', 'Đầm nữ - Ảnh 1'),
(50, 13, 'img/women/dam/dam2.webp', 'Đầm nữ - Ảnh 2'),
(51, 13, 'img/women/dam/dam3.webp', 'Đầm nữ - Ảnh 3'),
(52, 13, 'img/women/dam/dam4.webp', 'Đầm nữ - Ảnh 4'),
(53, 14, 'img/women/dam/dam5.webp', 'Đầm lụa tay hến - Ảnh 1'),
(54, 14, 'img/women/dam/dam6.webp', 'Đầm lụa tay hến - Ảnh 2'),
(55, 14, 'img/women/dam/dam7.webp', 'Đầm lụa tay hến - Ảnh 3'),
(56, 14, 'img/women/dam/dam8.webp', 'Đầm lụa tay hến - Ảnh 4'),
(57, 15, 'img/women/dam/dam9.webp', 'Đầm Bloom - Ảnh 1'),
(58, 15, 'img/women/dam/dam10.webp', 'Đầm Bloom - Ảnh 2'),
(59, 15, 'img/women/dam/dam11.webp', 'Đầm Bloom - Ảnh 3'),
(60, 15, 'img/women/dam/dam12.webp', 'Đầm Bloom - Ảnh 4'),
(61, 16, 'img/women/dam/dam13.webp', 'Đầm Hai Dây - Ảnh 1'),
(62, 16, 'img/women/dam/dam14.webp', 'Đầm Hai Dây - Ảnh 2'),
(63, 16, 'img/women/dam/dam15.webp', 'Đầm Hai Dây - Ảnh 3'),
(64, 16, 'img/women/dam/dam16.webp', 'Đầm Hai Dây - Ảnh 4'),
(65, 17, 'img/women/khoac/khoac1.webp', 'Sonata Vest - Ảnh 1'),
(66, 17, 'img/women/khoac/khoac2.webp', 'Sonata Vest - Ảnh 2'),
(67, 17, 'img/women/khoac/khoac3.webp', 'Sonata Vest - Ảnh 3'),
(68, 17, 'img/women/khoac/khoac4.webp', 'Sonata Vest - Ảnh 4'),
(69, 18, 'img/women/chanvay/chan1.webp', 'Chân váy Youthful - Ảnh 1'),
(70, 18, 'img/women/chanvay/chan2.webp', 'Chân váy Youthful - Ảnh 2'),
(71, 18, 'img/women/chanvay/chan3.webp', 'Chân váy Youthful - Ảnh 3'),
(72, 18, 'img/women/chanvay/chan4.webp', 'Chân váy Youthful - Ảnh 4'),
(79, 30, 'img/women/khoac/khoac224.png', 'Ảnh sản phẩm - khoac224.png'),
(80, 30, 'img/women/khoac/khoac223.png', 'Ảnh sản phẩm - khoac223.png'),
(81, 30, 'img/women/khoac/khoac222.png', 'Ảnh sản phẩm - khoac222.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_sizes`
--

INSERT INTO `product_sizes` (`id`, `product_id`, `size_id`, `quantity`) VALUES
(1, 1, 1, 10),
(2, 1, 2, 10),
(3, 1, 3, 10),
(4, 1, 4, 10),
(5, 1, 5, 10),
(6, 2, 1, 10),
(7, 2, 2, 10),
(8, 2, 3, 10),
(9, 2, 4, 10),
(10, 2, 5, 10),
(11, 3, 1, 11),
(12, 3, 2, 11),
(13, 3, 3, 11),
(14, 3, 4, 11),
(15, 3, 5, 11),
(16, 4, 1, 11),
(17, 4, 2, 11),
(18, 4, 3, 11),
(19, 4, 4, 11),
(20, 4, 5, 11),
(21, 5, 6, 5),
(22, 5, 7, 10),
(23, 5, 8, 10),
(24, 5, 9, 10),
(25, 5, 10, 10),
(26, 6, 6, 4),
(27, 6, 7, 10),
(28, 6, 8, 10),
(29, 6, 9, 10),
(30, 6, 10, 10),
(31, 7, 6, 0),
(32, 7, 7, 10),
(33, 7, 8, 10),
(34, 7, 9, 10),
(35, 7, 10, 10),
(36, 8, 6, 0),
(37, 8, 7, 10),
(38, 8, 8, 10),
(39, 8, 9, 10),
(40, 8, 10, 10),
(41, 9, 1, 10),
(42, 9, 2, 10),
(43, 9, 3, 10),
(44, 9, 4, 10),
(45, 10, 1, 10),
(46, 10, 2, 10),
(47, 10, 3, 10),
(48, 10, 4, 10),
(49, 10, 5, 10),
(50, 11, 1, 12),
(51, 11, 2, 12),
(52, 11, 3, 13),
(53, 11, 4, 13),
(54, 12, 1, 10),
(55, 12, 2, 10),
(56, 12, 3, 10),
(57, 12, 4, 10),
(58, 12, 5, 10),
(59, 13, 1, 10),
(60, 13, 2, 10),
(61, 13, 3, 10),
(62, 13, 4, 10),
(63, 13, 5, 10),
(64, 14, 1, 10),
(65, 14, 2, 10),
(66, 14, 3, 10),
(67, 14, 4, 10),
(68, 14, 5, 10),
(69, 15, 1, 10),
(70, 15, 2, 10),
(71, 15, 3, 10),
(72, 15, 4, 10),
(73, 15, 5, 10),
(74, 16, 1, 10),
(75, 16, 2, 10),
(76, 16, 3, 10),
(77, 16, 4, 10),
(78, 16, 5, 10),
(79, 17, 1, 10),
(80, 17, 2, 10),
(81, 17, 3, 10),
(82, 17, 4, 10),
(83, 17, 5, 10),
(84, 18, 1, 10),
(85, 18, 2, 10),
(86, 18, 3, 10),
(87, 18, 4, 10),
(88, 18, 5, 10),
(89, 30, 1, 10),
(90, 30, 2, 10),
(91, 30, 3, 10),
(92, 30, 4, 10),
(93, 30, 5, 10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sizes`
--

CREATE TABLE `sizes` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sizes`
--

INSERT INTO `sizes` (`id`, `name`) VALUES
(1, 'S'),
(2, 'M'),
(3, 'L'),
(4, 'XL'),
(5, 'XXL'),
(6, '29'),
(7, '30'),
(8, '31'),
(9, '32'),
(10, '33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `subcategories`
--

INSERT INTO `subcategories` (`id`, `name`, `category_id`) VALUES
(1, 'Áo thun', 1),
(2, 'Quần jeans', 1),
(3, 'Áo khoác', 1),
(4, 'Đầm', 2),
(5, 'Áo khoác', 2),
(6, 'Chân váy', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `used_vouchers`
--

CREATE TABLE `used_vouchers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `used_vouchers`
--

INSERT INTO `used_vouchers` (`id`, `user_id`, `voucher_id`, `used_at`) VALUES
(14, 24, 1, '2025-05-09 09:08:26'),
(15, 24, 2, '2025-05-09 09:08:43');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `birthday` date DEFAULT NULL,
  `gender` enum('Nam','Nữ','Khác') DEFAULT 'Khác',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `phone`, `email`, `role`, `birthday`, `gender`, `reset_token`, `reset_token_expiry`) VALUES
(4, 'th', '$2y$10$yBs4gL0bHnD4dHzprRq9YePj6rugdl4LM7f4rxB4HGRmirngvy82m', '0389468847', 'thanh11446sss6@gmail.com', 'user', '2025-04-04', 'Nữ', NULL, NULL),
(6, 'thanh nguyen', '$2y$10$PFUq4T0oSbnBN2cPiImVceZCQm10ucSuy9OvI896W0UIdHjTjTlwy', '0372119847', 'thanhdz@gmail.com', 'user', '2025-05-09', 'Nam', NULL, NULL),
(7, 'tuan', '$2y$10$KdKacQ1JCyp3tNWZMIsO5e5TilQTVs9.Bnbql2f.58q2VdTDINLGi', '0123456789', 'tuan11@gmail.com', 'user', '2025-04-09', 'Nam', NULL, NULL),
(8, 'tuan1', '$2y$10$tnqT5GS.SpAUt5p15ZtAb.UyxZQp9cv5VdswJdBWCE3y7ssNvMGxi', '0999999229', 'hahaha@gmail.com', 'user', NULL, 'Khác', NULL, NULL),
(19, 'thuan2412004', '$2y$10$y.6f/1g.cC3MRt9WrrT6/uoK.FkJdbC/zkT1wHrVWpyR4.aVbaicu', '0389468847', 'thuan2412004@gmail.com', 'user', '2025-05-08', 'Nam', NULL, NULL),
(24, 'Hai Thanh', '$2y$10$CMV70eqnnLTMoRguKxegMulnCix48V/Zj/NqDPGIWzE4jEaMnEBa.', '0333333333', 'thanh114466@gmail.com', 'admin', '2025-05-03', 'Nam', '2e91a6b0d54e18962ca957c987df1464edefa543d5d6fa2ffbb44e2a567f0329185181a70ec801c0080f95c5a05d89b4f1c6', '2025-05-15 18:30:02'),
(28, 'nhung0507kk', '', '0333333333', 'nhung0507kk@gmail.com', 'user', NULL, '', '242126d81f5a7fda6fc8b7c327b9d2230a43463f95a71f11bb43d390a277e3cdebe54d00f7cc5707d9421609a57c96adbb24', '2025-05-14 17:53:24'),
(29, 'hn4023906', '', '', 'hn4023906@gmail.com', 'user', NULL, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_percent` int(11) NOT NULL,
  `expiry_date` date NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `discount_percent`, `expiry_date`, `description`) VALUES
(1, 'NEW40', 40, '2025-06-30', 'Giảm 40% cho đơn hàng đầu tiên'),
(2, 'SUMMER20', 20, '2025-06-30', 'Giảm 20% cho đơn hàng mùa hè'),
(3, 'PREMIUM', 50, '2025-12-31', 'Giảm giá sập sàn 50%');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `size_id` (`size_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subcategory` (`subcategory`),
  ADD KEY `fk_products_collection` (`collection_id`);

--
-- Chỉ mục cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `size_id` (`size_id`);

--
-- Chỉ mục cho bảng `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `used_vouchers`
--
ALTER TABLE `used_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`voucher_id`),
  ADD KEY `voucher_id` (`voucher_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `collections`
--
ALTER TABLE `collections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT cho bảng `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT cho bảng `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `used_vouchers`
--
ALTER TABLE `used_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_collection` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`subcategory`) REFERENCES `subcategories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD CONSTRAINT `product_sizes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_sizes_ibfk_2` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `used_vouchers`
--
ALTER TABLE `used_vouchers`
  ADD CONSTRAINT `used_vouchers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `used_vouchers_ibfk_2` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
