<?php
include 'dbcon.php';

if(isset($_POST['submit'])) {
    // Lấy dữ liệu
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    // Kiểm tra
    if ($password !== $cpassword) {
        echo "<script>alert('Mật khẩu không khớp'); window.history.back();</script>";
        exit;
    }

    // Mã hóa & thêm mặc định
    $hashed = password_hash($password, PASSWORD_BCRYPT);
    $phone = '';
    
    $role = 'user';

    // Kiểm tra email trùng
    $check = mysqli_query($con, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email đã tồn tại'); window.history.back();</script>";
        exit;
    }

    // Thêm vào bảng users
    $sql = "INSERT INTO users (username, email, password, phone,  role)
            VALUES ('$username', '$email', '$hashed', '$phone', '$role')";
    $result = mysqli_query($con, $sql);
    
    if ($result) {
        echo "<script>alert('Đăng ký thành công'); window.location.href='../template/log-in.html';</script>";
    } else {
        die("Lỗi khi thêm tài khoản mới: " . mysqli_error($con));
    }
}
?>