<?php
require_once('../handlers/dbcon.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Lấy thông tin người dùng
function getUserDetails($con, $email) {
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($con, $query);
    return mysqli_fetch_assoc($result);
}

// Cập nhật thông tin người dùng
if (isset($_POST['update_info'])) {
    $number = mysqli_real_escape_string($con, $_POST['number']);
    // $address = mysqli_real_escape_string($con, $_POST['address']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $birthday = mysqli_real_escape_string($con, $_POST['birthday']);
    $username = $_SESSION['username'];

    $updateQuery = "UPDATE users SET phone='$number',  gender='$gender', birthday='$birthday' WHERE username='$username'";
    if (mysqli_query($con, $updateQuery)) {
        echo "<script>alert('Cập nhật thông tin thành công!'); location.replace('../template/user_profile.php');</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật thông tin: " . mysqli_error($con) . "');</script>";
    }
}

// Đổi mật khẩu
if (isset($_POST['change_pass'])) {
    $newpass = mysqli_real_escape_string($con, $_POST['newpass']);
    $confirmpass = mysqli_real_escape_string($con, $_POST['confirmpass']);
    $username = $_SESSION['username'];

    if ($newpass === $confirmpass) {
        $hashed = password_hash($newpass, PASSWORD_DEFAULT);
        $updatePassQuery = "UPDATE users SET password='$hashed' WHERE username='$username'";
        if (mysqli_query($con, $updatePassQuery)) {
            echo "<script>alert('Cập nhật mật khẩu thành công!'); location.replace('../template/user_profile.php');</script>";
        } else {
            echo "<script>alert('Lỗi khi cập nhật mật khẩu: " . mysqli_error($con) . "');</script>";
        }
    } else {
        echo "<script>alert('Mật khẩu xác nhận không khớp!');</script>";
    }
}
// Xử lý thêm/cập nhật địa chỉ
if (isset($_POST['add_address'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $number   = mysqli_real_escape_string($con, $_POST['number']);
    $detail   = mysqli_real_escape_string($con, $_POST['address']);
    $province = mysqli_real_escape_string($con, $_POST['province']);
    $district = mysqli_real_escape_string($con, $_POST['district']);
    $ward     = mysqli_real_escape_string($con, $_POST['ward']);
    $isDefault = 1; // Luôn đặt là mặc định vì chỉ có 1 địa chỉ

    // Cập nhật lại số điện thoại trong bảng users
    $updatePhone = "UPDATE users SET phone='$number' WHERE email='$email'";
    mysqli_query($con, $updatePhone);

    // Lấy user_id từ username
    $getUserId = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($con, $getUserId);
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['id'];

    // Kiểm tra xem người dùng đã có địa chỉ chưa
    $checkAddress = "SELECT id FROM address WHERE user_id = $user_id";
    $checkResult = mysqli_query($con, $checkAddress);
    
    if (mysqli_num_rows($checkResult) > 0) {
        // Nếu đã có địa chỉ, cập nhật địa chỉ hiện có
        $addressRow = mysqli_fetch_assoc($checkResult);
        $address_id = $addressRow['id'];
        
        $updateAddress = "UPDATE address SET 
                          detail = '$detail', 
                          ward = '$ward', 
                          district = '$district', 
                          province = '$province', 
                          is_default = $isDefault 
                          WHERE id = $address_id";
        
        if (mysqli_query($con, $updateAddress)) {
            echo "<script>alert('Cập nhật địa chỉ thành công!'); location.replace('../template/user_profile.php');</script>";
        } else {
            echo "<script>alert('Lỗi khi cập nhật địa chỉ: " . mysqli_error($con) . "');</script>";
        }
    } else {
        // Nếu chưa có địa chỉ, thêm mới
        $insertAddress = "INSERT INTO address (user_id, detail, ward, district, province, is_default)
                         VALUES ($user_id, '$detail', '$ward', '$district', '$province', $isDefault)";
        
        if (mysqli_query($con, $insertAddress)) {
            echo "<script>alert('Thêm địa chỉ thành công!'); location.replace('../template/user_profile.php');</script>";
        } else {
            echo "<script>alert('Lỗi khi thêm địa chỉ: " . mysqli_error($con) . "');</script>";
        }
    }
}