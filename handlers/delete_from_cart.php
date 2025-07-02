<?php
session_start();
require_once('../handlers/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Lấy user_id từ session
    $email = $_SESSION['email'];
    $userQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = $con->prepare($userQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $userResult = $stmt->get_result();
    $user = $userResult->fetch_assoc();
    $user_id = $user['id'];

    // Lấy product_id và size_id từ yêu cầu POST
    $product_id = intval($_POST['product_id']);
    $size_id = intval($_POST['size_id']);

    // Truy vấn sản phẩm trong giỏ hàng dựa trên user_id, product_id và size_id
    $cartQuery = "SELECT id FROM cart WHERE user_id = ? AND product_id = ? AND size_id = ?";
    $stmt = $con->prepare($cartQuery);
    $stmt->bind_param("iii", $user_id, $product_id, $size_id);
    $stmt->execute();
    $cartResult = $stmt->get_result();

    if ($cartResult->num_rows > 0) {
        $cartItem = $cartResult->fetch_assoc();
        $cartId = $cartItem['id'];

        // Xóa sản phẩm khỏi giỏ hàng
        $deleteQuery = "DELETE FROM cart WHERE id = ?";
        $stmt = $con->prepare($deleteQuery);
        $stmt->bind_param("i", $cartId);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng.']);
    }
}