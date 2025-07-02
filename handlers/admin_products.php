<?php
require_once 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $subcategory = $_POST['subcategory'];
    $description = $_POST['description'];
    $tag = $_POST['tag'];

    $query = "UPDATE products SET name = ?, price = ?, quantity = ?, subcategory = ?, description = ?, tag = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sdisssi", $name, $price, $quantity, $subcategory, $description, $tag, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Sản phẩm đã được cập nhật.";
    } else {
        $_SESSION['error'] = "Có lỗi xảy ra. Vui lòng thử lại.";
    }

    header("Location: ../template/admin_home.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'delete') {
    $id = $_GET['id'];

    $query = "DELETE FROM products WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Sản phẩm đã được xóa.";
    } else {
        $_SESSION['error'] = "Có lỗi xảy ra. Vui lòng thử lại.";
    }

    header("Location: ../template/admin_home.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    // Lấy dữ liệu từ form
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $subcategory = mysqli_real_escape_string($con, $_POST['subcategory']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $tag = mysqli_real_escape_string($con, $_POST['tag']);
    $quantity = mysqli_real_escape_string($con, $_POST['quantity']);

    // Thêm sản phẩm vào bảng `products`
    $query = "INSERT INTO products (name, price, quantity, subcategory, description, tag, created_at) 
              VALUES ('$name', '$price', '$quantity', '$subcategory', '$description', '$tag', NOW())";

    if (mysqli_query($con, $query)) {
        $product_id = mysqli_insert_id($con); // Lấy ID sản phẩm vừa thêm

        // Xử lý thêm size và số lượng
        if (isset($_POST['sizes']) && is_array($_POST['sizes'])) {
            foreach ($_POST['sizes'] as $size_id => $size_quantity) {
                $size_quantity = intval($size_quantity); // Chuyển đổi số lượng thành số nguyên
                if ($size_quantity > 0) {
                    $sizeQuery = "INSERT INTO product_sizes (product_id, size_id, quantity) VALUES (?, ?, ?)";
                    $stmt = $con->prepare($sizeQuery);
                    $stmt->bind_param("iii", $product_id, $size_id, $size_quantity);
                    $stmt->execute();
                }
            }
        }

        // Xử lý upload ảnh (đã có ở trên)
        $uploadDir = '../img/women/khoac/'; // Thư mục lưu ảnh
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        // $_FILES['images'] = [
        // 'name' => [0 => 'anh1.jpg', 1 => 'anh2.png', ...],
        // 'type' => [0 => 'image/jpeg', 1 => 'image/png', ...],
        // 'tmp_name' => [0 => '/tmp/php1234', 1 => '/tmp/php5678', ...],
        // 'error' => [0 => 0, 1 => 0, ...],
        // 'size' => [0 => 12345, 1 => 67890, ...]
        // ]
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['images']['name'][$key]);
            $fileType = $_FILES['images']['type'][$key];
            $filePath = $uploadDir . $fileName;

            // Kiểm tra loại file
            if (!in_array($fileType, $allowedTypes)) {
                echo "<script>alert('Chỉ cho phép tải lên các file ảnh (JPG, PNG, GIF).');</script>";
                continue;
            }

            // Di chuyển file vào thư mục uploads
            if (move_uploaded_file($tmpName, $filePath)) {
                // Lưu đường dẫn ảnh vào bảng `product_images`
                $relativePath = str_replace('../', '', $filePath); // Loại bỏ ../ khỏi đường dẫn
                $imageQuery = "INSERT INTO product_images (product_id, image_url, alt_text) VALUES (?, ?, ?)";
                $altText = "Ảnh sản phẩm - " . $fileName;
                $stmt = $con->prepare($imageQuery);
                $stmt->bind_param("iss", $product_id, $relativePath, $altText);
                $stmt->execute();
            } else {
                echo "<script>alert('Không thể tải lên ảnh: $fileName');</script>";
            }
        }

        echo "<script>
            alert('Thêm sản phẩm, size và ảnh thành công!');
            window.location.href = '../template/admin_home.php';
        </script>";
    } else {
        echo "<script>alert('Lỗi khi thêm sản phẩm: " . mysqli_error($con) . "');</script>";
    }
}
?>