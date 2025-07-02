<?php

use Google\Service\CloudControlsPartnerService\Console;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../vendor/facebook/graph-sdk/src/Facebook/autoload.php'; // Tự động tải thư viện Facebook SDK
require_once 'dbcon.php'; // Kết nối cơ sở dữ liệu và load environment variables

$fb = new \Facebook\Facebook([
    'app_id' => $_ENV['FACEBOOK_APP_ID'] ?? '', // Từ .env
    'app_secret' => $_ENV['FACEBOOK_APP_SECRET'] ?? '', // Từ .env
    'default_graph_version' => 'v12.0',
]);
$helper = $fb->getRedirectLoginHelper();

try {
    // Lấy access token từ mã code trả về từ Facebook
    $accessToken = $helper->getAccessToken();

    if (!$accessToken) {
        die('Lỗi: Không lấy được access token.');
    }

    // Lấy thông tin người dùng
    $response = $fb->get('/me?fields=id,name,email', $accessToken);
    $user = $response->getGraphUser();

    // Lấy thông tin email và tên
    $email = $user['email'];
    $name = $user['name'];
    
    // Kiểm tra xem email đã tồn tại trong CSDL chưa
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Lỗi truy vấn: " . mysqli_error($con));
    }

    // Nếu chưa có tài khoản, tạo tài khoản mới
    if (mysqli_num_rows($result) === 0) {
        $username = explode('@', $email)[0]; // Lấy username từ email
        $insert = "INSERT INTO users (username, email, phone, gender, birthday, role) 
                   VALUES ('$username', '$email', '', '', NULL, 'user')";
        if (!mysqli_query($con, $insert)) {
            die("Lỗi khi thêm tài khoản mới: " . mysqli_error($con));
        }
    } else {
        // Nếu đã có tài khoản, lấy tên người dùng
        $user = mysqli_fetch_assoc($result);
        $username = $user['username'];
    }

    // Lưu email vào session
    $_SESSION['username'] = $username;
    header('Location: ../template/home.php'); // Chuyển hướng tới trang chủ
    exit;

} catch (\Facebook\Exceptions\FacebookResponseException $e) {
    die('Graph returned an error: ' . $e->getMessage());
} catch (\Facebook\Exceptions\FacebookSDKException $e) {
    die('Facebook SDK returned an error: ' . $e->getMessage());
}
?>