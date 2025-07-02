<?php
require_once('dbcon.php');
session_start();
// Gửi email xác nhận đơn hàng
require '../libs/PHPMailer/src/PHPMailer.php';
require '../libs/PHPMailer/src/SMTP.php';
require '../libs/PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$paymentMethod = $_POST['payment_method'] ?? 'cod';
$totalPrice = $_POST['total_price'];
$user_email = $_POST['user_email'] ;

// Kiểm tra nếu người dùng đã nhấn nút "ĐẶT HÀNG"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    // Nếu phương thức thanh toán là VNPAY
    if ($paymentMethod === 'vnpay') {
        // Tích hợp API VNPAY
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_TmnCode = $_ENV['VNPAY_TMN_CODE'] ?? '';
        $vnp_HashSecret = $_ENV['VNPAY_HASH_SECRET'] ?? '';
        $vnp_Url = $_ENV['VNPAY_URL'] ?? '';
        $vnp_Returnurl = $_ENV['VNPAY_RETURN_URL'] ?? '';

        $vnp_TxnRef = time(); // Mã giao dịch (unique)
        $vnp_OrderInfo = "Thanh toán đơn hàng tại Smart Fashion";
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $totalPrice * 100; // Số tiền (VND) nhân 100
        $vnp_Locale = "vn";
        $startTime = date('YmdHis'); // Thời gian hiện tại
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime))); // Thời gian hết hạn sau 15 phút
        $vnp_ExpireDate = $expire;

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate
        );

        ksort($inputData);
        $query = "";
        $hashdata = "";
        $i = 0;

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // Chuyển hướng đến VNPAY
        header('Location: ' . $vnp_Url);
        die();
    }

    $user_id = $_SESSION['user_id']; // Giả sử user_id được lưu trong session
    $order_date = date('Y-m-d H:i:s');
    $status = 'pending';

    // Bắt đầu giao dịch
    mysqli_begin_transaction($con);

    try {
        // Lưu thông tin đơn hàng vào bảng `orders`
        $insertOrderQuery = "INSERT INTO orders (user_id, order_date, total_price, status) 
                             VALUES ($user_id, '$order_date', $totalPrice, '$status')";
        mysqli_query($con, $insertOrderQuery);

        // Lấy ID của đơn hàng vừa tạo
        $order_id = mysqli_insert_id($con);

        // Lấy sản phẩm trong giỏ hàng
        $cartQuery = "
            SELECT c.id AS cart_id, c.product_id, p.name AS product_name, p.price AS product_price,
                   pi.image_url AS product_image, c.quantity, s.name AS size_name, c.size_id
            FROM cart c
            JOIN products p ON c.product_id = p.id
            LEFT JOIN (
                SELECT product_id, MIN(image_url) AS image_url
                FROM product_images
                GROUP BY product_id
            ) pi ON pi.product_id = p.id
            LEFT JOIN sizes s ON c.size_id = s.id
            WHERE c.user_id = $user_id
        ";

        $cartResult = mysqli_query($con, $cartQuery);
        if (!$cartResult) {
            die("Lỗi truy vấn giỏ hàng: " . mysqli_error($con));
        }
        $cartItems = mysqli_fetch_all($cartResult, MYSQLI_ASSOC);

        // Lưu thông tin sản phẩm vào bảng `order_items` và cập nhật `sold` trong bảng `products`
        foreach ($cartItems as $item) {
            $product_id = $item['product_id'];
            $size_id = $item['size_id'];
            $quantity = $item['quantity'];
            $price = $item['product_price'];

            if (empty($product_id) || empty($quantity) || empty($price)) {
                die("Dữ liệu không hợp lệ: product_id = $product_id, quantity = $quantity, price = $price");
            }

            // Lưu vào bảng `order_items`
            $insertOrderItemQuery = "INSERT INTO order_items (order_id, product_id, size_id, quantity, price) 
                                     VALUES ($order_id, $product_id, $size_id, $quantity, $price)";
            mysqli_query($con, $insertOrderItemQuery);

            // Cập nhật số lượng đã bán trong bảng `products`
            $updateProductQuery = "UPDATE products SET sold = sold + $quantity WHERE id = $product_id";
            mysqli_query($con, $updateProductQuery);
        }

        // Xóa sản phẩm khỏi giỏ hàng
        $deleteCartQuery = "DELETE FROM cart WHERE user_id = $user_id";
        if (!mysqli_query($con, $deleteCartQuery)) {
            throw new Exception("Lỗi khi xóa giỏ hàng: " . mysqli_error($con));
        }
        // Commit giao dịch
        mysqli_commit($con);

        
        $mail = new PHPMailer(true);
        
        try {
            // Cấu hình SMTP
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'] ?? '';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'] ?? '';
            $mail->Password = $_ENV['SMTP_PASS'] ?? '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['SMTP_PORT'] ?? 587;

            // Cấu hình email
             $mail->CharSet = 'UTF-8';

            $mail->setFrom($_ENV['SMTP_USER'] ?? '', 'Smart Fashion');
            $mail->addAddress($user_email);
            $mail->isHTML(true);
            $mail->Subject = 'Xác nhận đơn hàng từ Smart Fashion';
            $mail->Body = "
                <h3>Xin chào,</h3>
                <p>Cảm ơn bạn đã đặt hàng tại Smart Fashion. Dưới đây là thông tin đơn hàng của bạn:</p>
                <p><strong>Mã đơn hàng:</strong> $order_id</p>
                <p><strong>Tổng tiền:</strong> " . number_format($totalPrice, 0, ',', '.') . " VND</p>
                <p>Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất.</p>
                <p>Trân trọng,<br>Đội ngũ Smart Fashion</p>
            ";

            // Gửi email
            $mail->send();

            // Hiển thị thông báo thành công và chuyển hướng về trang home
            echo "<script>alert('Đặt hàng thành công!'); window.location.href='../template/home.php';</script>";
            exit();
        } catch (Exception $e) {
             mysqli_rollback($con); // Rollback giao dịch nếu có lỗi
            echo "<script>alert('Có lỗi xảy ra: " . $e->getMessage() . "'); window.location.href='../template/cart.php';</script>";
        }
    } catch (Exception $e) {
        // Rollback giao dịch nếu có lỗi
        mysqli_rollback($con);
        echo "<script>alert('Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại!'); window.location.href='../template/cart.php';</script>";
    }
} else {
    // Nếu truy cập không hợp lệ, chuyển hướng về giỏ hàng
    header("Location: ../template/cart.php");
    exit();
}
?>