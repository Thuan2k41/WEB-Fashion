<?php
require_once 'dbcon.php'; // Load environment variables
require '../libs/PHPMailer/src/PHPMailer.php';
require '../libs/PHPMailer/src/SMTP.php';
require '../libs/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['txtName']);
    $email = htmlspecialchars($_POST['txtEmail']);
    $phone = htmlspecialchars($_POST['txtPhone']);
    $subject = htmlspecialchars($_POST['txtSubject']);
    $message = htmlspecialchars($_POST['txtMsg']);

    $mail = new PHPMailer(true);

    try {
        // Cấu hình SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'] ?? '';
        $mail->Password = $_ENV['SMTP_PASS'] ?? '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['SMTP_PORT'] ?? 587;

        // Người gửi và người nhận
        $mail->setFrom($email, $name);
        $mail->addAddress($_ENV['SMTP_USER'] ?? '', 'Smart Fashion'); // Email nhận

        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = $subject ?: 'Liên hệ từ khách hàng';
        $mail->Body = "
            <h3>Thông tin liên hệ:</h3>
            <p><strong>Họ và tên:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Số điện thoại:</strong> $phone</p>
            <p><strong>Nội dung:</strong></p>
            <p>$message</p>
        ";

        $mail->send();
        echo "<script>
            alert('Gửi liên hệ thành công!');
            window.location.href = '../template/customer_care.php';
        </script>";
    } catch (Exception $e) {
        echo "<script>
            alert('Không thể gửi email. Lỗi: {$mail->ErrorInfo}');
            window.location.href = '../template/customer_care.php';
        </script>";
    }
}
?>