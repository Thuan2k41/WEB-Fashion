<?php
// Load environment variables function
function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        die("File .env không tồn tại tại đường dẫn: " . $filePath);
    }
    
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Loại bỏ các dòng bắt đầu bằng dấu # hoặc các dòng trống
        if (strpos(trim($line), '#') === 0 || empty(trim($line))) {
            continue;
        }
        
        // Xử lý các dòng chứa biến môi trường
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            // Lưu biến môi trường vào $_ENV
            if (!array_key_exists($name, $_ENV)) {
                $_ENV[$name] = $value;
            }
        }
    }
    return true;
}

// Load .env file from parent directory
loadEnv(__DIR__ . '/../.env');
// var_dump($_ENV);
// Database configuration from environment variables
$servername = $_ENV['DB_HOST'] ;
$username = $_ENV['DB_USER'] ;
$password = $_ENV['DB_PASS'] ;
$database = $_ENV['DB_NAME'];


// Create connection
$con = new mysqli($servername, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Kết nối thất bại: " . $con->connect_error);
}

?>