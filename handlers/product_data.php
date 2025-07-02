<?php
require_once('../handlers/dbcon.php');

// Lấy ID sản phẩm từ URL
$pId = isset($_GET['pId']) ? intval($_GET['pId']) : 1;

// Truy vấn thông tin sản phẩm
$sql = "SELECT * FROM products WHERE id = $pId";
$result = mysqli_query($con, $sql);

// Kiểm tra nếu sản phẩm tồn tại
if ($row = mysqli_fetch_assoc($result)) {
    $productName = $row['name'];
    $productPrice = $row['price'];
    $productDescription = $row['description'];
    $productSKU = $row['sku'] ?? 'Không có';
    $productDiscount = $row['discount'] ?? 0;

    // Truy vấn danh sách ảnh liên quan đến sản phẩm
    $imageQuery = "SELECT image_url, alt_text FROM product_images WHERE product_id = $pId";
    $imageResult = mysqli_query($con, $imageQuery);
    $productImages = mysqli_fetch_all($imageResult, MYSQLI_ASSOC);

    // Truy vấn danh sách kích thước của sản phẩm
    $sizeQuery = "
        SELECT s.id, s.name 
        FROM product_sizes ps
        JOIN sizes s ON ps.size_id = s.id
        WHERE ps.product_id = $pId
    ";
    $sizeResult = mysqli_query($con, $sizeQuery);
    $productSizes = mysqli_fetch_all($sizeResult, MYSQLI_ASSOC);
} else {
    // Nếu sản phẩm không tồn tại, chuyển hướng hoặc hiển thị thông báo lỗi
    header("Location: ../template/404.php");
    exit;
}