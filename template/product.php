<?php
include("header.php");
require_once('../handlers/product_data.php'); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/product.css">
    <link rel="stylesheet" href="../bootstrap/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body>
    <main class="container py-5">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6 mb-4">
                <div class="row gx-0  ">
                    <div class="col-md-10 ">
                        <div class="position-relative">
                            <img src="../<?php echo $productImages[0]['image_url'] ?? ''; ?>" class=" rounded shadow"
                                style="width: 90%;" alt="<?php echo htmlspecialchars($productName); ?>"
                                id="mainImage" />

                        </div>
                    </div>

                    <!-- Thumbnails -->
                    <div class="col-md-2 product-gallery d-flex flex-column align-items-center my-auto">
                        <?php foreach ($productImages as $image) { ?>
                        <div class="thumbnail mb-3">
                            <img src="../<?php echo $image['image_url']; ?>" class="img-fluid border rounded shadow-sm"
                                alt="<?php echo htmlspecialchars($image['alt_text']); ?>" style="cursor: pointer;"
                                onclick="document.getElementById('mainImage').src='../<?php echo $image['image_url']; ?>'" />
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-lg-6 px-5">
                <h1 class="fw-bold fs-4"><?php echo htmlspecialchars($productName); ?></h1>
                <div class="mb-3">
                    <span>SKU: <?php echo htmlspecialchars($row['id']); ?></span>

                </div>

                <div class="mb-4">
                    <span class="h2 fw-bold fs-4">
                        <?php echo number_format($productPrice , 0, ',', '.'); ?>₫
                    </span>

                </div>

                <div class="mb-4">
                    <p class="mb-2">Màu sắc:</p>
                    <div>
                        <span class="color-option bg-light active" title="Trắng"></span>
                        <span class="color-option bg-success" title="Xanh lá"></span>
                        <span class="color-option bg-black" title="Đen"></span>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="mb-2">Kích thước:</p>
                    <div class="d-flex">
                        <?php foreach ($productSizes as $size): ?>
                        <button class="size-btn" data-size-id="<?= $size['id'] ?>">
                            <?= htmlspecialchars($size['name']) ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="mb-2">Số lượng:</p>
                    <div class="d-flex align-items-center" style="max-width: 150px;">
                        <button type="button" class="btn btn-outline-secondary quantity-decrease">-</button>
                        <input type="text" class="form-control mx-2 quantity-input" value="1" />
                        <button type="button" class="btn btn-outline-secondary quantity-increase">+</button>
                    </div>
                </div>

                <div class="d-flex mb-3">
                    <form action="../handlers/add_to_cart.php" method="POST" class="d-inline">
                        <input type="hidden" name="product_id" value="<?php echo $pId; ?>">

                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($productName); ?>">
                        <input type="hidden" name="product_price" value="<?php echo $productPrice; ?>">
                        <input type="hidden" name="product_image"
                            value="<?php echo $productImages[0]['image_url'] ?? ''; ?>">
                        <input type="hidden" name="quantity" value="1" class="quantity-hidden">
                        <input type="hidden" name="size_id" value="" id="selected-size">
                        <input type="hidden" name="size_name" value="" id="selected-size-name">
                        <button type="submit" class="btn btn-outline-dark px-4 me-2">MUA HÀNG</button>
                    </form>
                    <button id="add-to-cart" class="btn btn-dark px-4 me-2" data-product-id="<?php echo $pId; ?>"
                        data-product-name="<?php echo htmlspecialchars($productName); ?>"
                        data-product-price="<?php echo $productPrice; ?>"
                        data-product-image="<?php echo $productImages[0]['image_url'] ?? ''; ?>">THÊM VÀO GIỎ</button>
                    <button class="btn btn-outline-dark">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
                <div class="toast-container position-fixed bottom-0 end-0 p-3 ">
                    <div id="addToCartToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header  bg-danger">
                            <strong class="me-auto">Thông báo</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            Sản phẩm đã được thêm vào giỏ hàng!
                        </div>
                    </div>
                </div>
                <!-- Product Tabs -->
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab"
                            data-bs-target="#overview" type="button">
                            GIỚI THIỆU
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="productTabsContent">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel">
                        <p><?php echo htmlspecialchars($productDescription); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../js/product.js"></script>
    <script src="../bootstrap/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <?php include("footer.html"); ?>
</body>

</html>