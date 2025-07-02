<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../vendor/autoload.php';
require_once 'dbcon.php'; // file kết nối DB và load environment variables

$client = new Google_Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID'] ?? '');
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET'] ?? '');
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI'] ?? '');

if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $token = $client->getAccessToken();
    $client->setAccessToken($token);

    $oauth2 = new Google_Service_Oauth2($client);
    $userinfo = $oauth2->userinfo->get();

    $email = $userinfo->email;
    $name = $userinfo->name;

    // Gán giá trị mặc định cho các trường không được cung cấp
    $phone = '';
    $gender = 'Không xác định';
    $birthday = NULL;

    // Kiểm tra trong DB
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Lỗi truy vấn: " . mysqli_error($con));
    }

    if (mysqli_num_rows($result) === 0) {
        // Nếu email chưa tồn tại, thêm người dùng mới
        $username = explode('@', $email)[0];
        $insert = "INSERT INTO users (username, email, phone, gender, birthday, role) 
                   VALUES ('$username', '$email', '$phone', '$gender', NULL, 'user')";
        if (!mysqli_query($con, $insert)) {
            die("Lỗi khi thêm tài khoản mới: " . mysqli_error($con));
        }
    } else {
        // Nếu email đã tồn tại, lấy thông tin người dùng
        $user = mysqli_fetch_assoc($result);
        $username = $user['username'];
    }

    // Thiết lập session
    $_SESSION['email'] = $email; // Lưu username vào session
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
            
    if (!isset($_SESSION['email'])) {
        die("Lỗi: Session không được thiết lập.");
    }

    header('Location: ../template/home.php');
    exit;
}
?>