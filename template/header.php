<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../handlers/dbcon.php');
?>

<link rel="stylesheet" href="../bootstrap/bootstrap-5.3.3-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand" href="home.php">
            <img src="../img/main/logo.png" alt="Logo" style="height: 40px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 align-items-center justify-content-center mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link text-uppercase fw-bold" href="home.php">Trang Chủ</a>
                </li>
                <!-- Nam -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-uppercase fw-bold" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">Nam</a>
                    <ul class="dropdown-menu">
                        <?php
                        $sql_nam = "SELECT * FROM subcategories WHERE category_id = 1";
                        $result_nam = mysqli_query($con, $sql_nam);
                        while ($row = mysqli_fetch_assoc($result_nam)) {
                            echo '<li><a class="dropdown-item" href="list_product.php?subcategory='.$row['id'].'">'.$row['name'].'</a></li>';
                            // <a href="list_product.php?category=2&tag=sale" class="view-all-btn">Xem tất cả</a>
                        }
                        ?>
                    </ul>
                </li>
                <!-- Nữ -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-uppercase fw-bold" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">Nữ</a>
                    <ul class="dropdown-menu">
                        <?php
                        $sql_nu = "SELECT * FROM subcategories WHERE category_id = 2";
                        $result_nu = mysqli_query($con, $sql_nu);
                        while ($row = mysqli_fetch_assoc($result_nu)) {
                            echo '<li><a class="dropdown-item" href="list_product.php?subcategory='.$row['id'].'">'.$row['name'].'</a></li>';
                        }
                        ?>
                    </ul>
                </li>
                <!-- Bộ sưu tập -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-uppercase fw-bold" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">Bộ sưu tập</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="collection2.php">LILAS DREAM</a></li>
                        <li><a class="dropdown-item" href="collection1.php">ROSIE CRUSH</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-uppercase fw-bold" href="customer_care.php">Care & Share</a>
                </li>
            </ul>
            <!-- Icons -->
            <ul class="nav align-items-center justify-content-center mb-0">
                <li class="nav-item">
                    <a href="results.php" class="nav-link text-dark px-2"><i class="bi bi-search"></i></a>
                </li>
                <li class="nav-item">
                    <a href="cart.php" class="nav-link text-dark px-2"><i class="bi bi-cart"></i></a>
                </li>
                <?php if(isset($_SESSION['username'])){ ?>
                <li class="nav-item">
                    <a href="user_profile.php" class="nav-link text-dark px-2"><i class="bi bi-person"></i></a>
                </li>
                <?php } else { ?>
                <li class="nav-item">
                    <a href="log-in.html" class="nav-link text-dark px-2"><i class="bi bi-person"></i></a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
<script src="../js/header.js"></script>