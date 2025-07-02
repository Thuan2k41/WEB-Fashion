<?php
session_start();
include 'dbcon.php';

if(isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];
    // Tìm user trong bảng users
    $sql = "SELECT * FROM users WHERE email='$email'";
    $query = mysqli_query($con, $sql);
    $count = mysqli_num_rows($query);

    if($count > 0) {
        $user = mysqli_fetch_assoc($query);
        $db_pass = $user['password'];

        if(password_verify($password, $db_pass)) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            // Phân quyền theo role
            if($user['role'] == 'admin') {
                echo "<script>location.replace('../template/admin_home.php');</script>";
            } else {
                echo "<script>location.replace('../template/home.php');</script>";
            }
        } else {
            echo "<script>alert('Sai mật khẩu'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Email không tồn tại'); window.history.back();</script>";
    }
}
?>