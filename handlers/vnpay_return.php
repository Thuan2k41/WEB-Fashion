<?php
require_once 'dbcon.php';
$vnp_HashSecret = $_ENV['VNPAY_HASH_SECRET'] ?? '';  // Chuỗi bí mật của bạn tại VNPAY
$inputData = array();
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}

$vnp_SecureHash = $_GET['vnp_SecureHash'];
unset($inputData['vnp_SecureHash']);
ksort($inputData);
$hashData = "";
$i = 0;
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashData .= '&' . $key . "=" . $value;
    } else {
        $hashData .= $key . "=" . $value;
        $i = 1;
    }
}

$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
if ($secureHash == $vnp_SecureHash) {
    if ($_GET['vnp_ResponseCode'] == '00') {
        // Thanh toán thành công
        echo "Giao dịch thành công!";
    } else {
        // Thanh toán thất bại
        echo "Giao dịch không thành công!";
    }
} else {
    echo "Chữ ký không hợp lệ!";
}