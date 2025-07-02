<?php
if (isset($_SESSION['user_id'])) {
    session_destroy();
    header('location:../template/home.php');
} else {
    header('location:../template/log-in.html');
}