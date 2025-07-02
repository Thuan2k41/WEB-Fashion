<?php
require_once '../handlers/dbcon.php'; 
require '../libs/PHPMailer/src/PHPMailer.php';
require '../libs/PHPMailer/src/SMTP.php';
require '../libs/PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Kiểm tra email có tồn tại trong database không
    $stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        date_default_timezone_set('Asia/Ho_Chi_Minh'); // Đặt múi giờ Việt Nam

        // Tạo token reset mật khẩu
        $token = bin2hex(random_bytes(50));
        $stmt = $con->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        if (!$stmt) {
            die("SQL Error: " . $con->error);
        }
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        // Gửi email reset mật khẩu
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'] ?? '';
            $mail->Password = $_ENV['SMTP_PASS'] ?? '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['SMTP_PORT'] ?? 587;

            $mail->setFrom($_ENV['SMTP_USER'] ?? '', 'Smart Fashion');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click the link below to reset your password:<br>
                <a href='http://localhost/smart-fashion/handlers/reset_password.php?token=$token'>Reset Password</a>";

            $mail->send();
            echo "<script>
                alert('Vui lòng kiểm tra email của bạn để đặt lại mật khẩu!.');
                window.location.href = 'https://mail.google.com/';
                </script>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email not found!";
    }
}
?>