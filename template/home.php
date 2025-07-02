<?php
include("header.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../bootstrap/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../static/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>

<body>
    <!-- Icon chat -->
    <div id="chat-icon" style="position: fixed; bottom: 20px; right: 20px; cursor: pointer;">
        <img src="../img/main/chat1.jpg" alt="Chat Icon" style="width: 70px; height: 70px;" />
    </div>

    <!-- Cửa sổ chat -->
    <div id="chat-window">
        <div style="background:rgb(44, 132, 227); color: #fff; padding: 10px; text-align: center;">
            Chat với mọi người
            <span id="close-chat" style="float: right; cursor: pointer;">&times;</span>
        </div>
        <div id="chat" style="height: 300px; overflow-y: auto; padding: 10px; border-bottom: 1px solid #ccc;"></div>
        <div style="padding: 10px; ">
            <input type="text" id="message" placeholder="Type your message here..." style="width: calc(100% - 60px);" />
            <i class="bi bi-send mx-3" id="send"></i>
        </div>
    </div>
    <!-- Slider -->
    <div class="container">
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 0"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                    aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3"
                    aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4"
                    aria-label="Slide 4"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="../img/main/slide1.jpg" class="img-slider" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="../img/main/slide5.jpg" class="img-slider" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="../img/main/slide6.jpg" class="img-slider" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="../img/main/slide7.jpg" class="img-slider" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="../img/main/slide0.jpg" class="img-slider" alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- New Arrivals -->
    <div class="container">
        <div class="px-0 py-1 tab-group">
            <h2 class="text-center">NEW ARRIVAL</h2>
            <div class="d-flex justify-content-center mb-4 border-bottom tab-links">
                <a href="#" class="tab-link " data-category="1">MEN</a>
                <a href="#" class="tab-link active" data-category="2">WOMEN</a>
            </div>

            <div class="row-5-cols" id="product-new">
                <!-- Sản phẩm sẽ được tải động vào đây -->
            </div>

            <div class="mt-3 text-center">
                <a href="list_product.php?tag=new" class="view-all-btn">Xem tất cả</a>
            </div>
        </div>
    </div>

    <hr class="gradient-divider">

    <!-- COLLECTION -->
    <div class="container my-2">
        <h2 class="text-center">COLLECTION
        </h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="banner-box overflow-hidden rounded-4">
                    <a href="lookbook.php"><img src="../img/women/collec.webp" class="img-fluid img-fix"
                            alt="Banner 1"></a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="banner-box overflow-hidden rounded-4">
                    <a href="lookbook.php"><img src="../img/women/collect2.webp" class="img-fluid img-fix"
                            alt="Banner 2"></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Sale -->
    <div class="container">
        <div class="px-0 py-1 tab-group">
            <h2 class="text-center">SALE</h2>
            <div class="d-flex justify-content-center mb-4 border-bottom tab-links">
                <a href="#" class="tab-linkk active" data-category="1">Nam</a>
                <a href="#" class=" tab-linkk" data-category="2">Nữ</a>
            </div>
            <div class="row-5-cols" id="product-sale">


            </div>
            <div class="mt-3 text-center">
                <a href="list_product.php?tag=sale" class="view-all-btn">Xem tất cả</a>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <a href="list_product.php?tag=sale"><img src="../img/women/4.webp" alt="" class="h-70 w-100"
                style="border-radius: 80px 0 80px 0;"></a>
        <hr>
    </div>

    <script src="../bootstrap/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/home.js"></script>
</body>

<?php include("footer.html"); ?>

</html>