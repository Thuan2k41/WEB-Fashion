<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../vendor/facebook/graph-sdk/src/Facebook/autoload.php'; // Tự động tải thư viện Facebook SDK
require_once 'dbcon.php'; // Load environment variables

$fb = new \Facebook\Facebook([
    'app_id' => $_ENV['FACEBOOK_APP_ID'] ?? '', // Từ .env
    'app_secret' => $_ENV['FACEBOOK_APP_SECRET'] ?? '', // Từ .env
    'default_graph_version' => 'v12.0',
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // Quyền cần yêu cầu
$loginUrl = $helper->getLoginUrl($_ENV['FACEBOOK_CALLBACK_URL'] ?? 'http://localhost/smart-fashion/handlers/facebook_callback.php', $permissions);

echo '<a href="' . $loginUrl . '">Đăng nhập bằng Facebook</a>';
?>