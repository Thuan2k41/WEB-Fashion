<?php include("header.php"); 
require_once('../handlers/dbcon.php');

$collection_id = 2;

$sql = "
    SELECT p.id, p.name, p.price, pi.image_url
    FROM products p
    LEFT JOIN product_images pi ON p.id = pi.product_id
    WHERE p.collection_id = $collection_id
    GROUP BY p.id
";

$result = mysqli_query($con, $sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>BLUE SONATA | Collection</title>
    <link rel="stylesheet" href="../static/lookbook.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <main class="container mt-4">
        <img src="../img/women/collect3.webp" alt="" class="w-100 object-fit-cover fix-img ">
        <p class="mt-5 mb-0 fw-bolder fs-5 text-center">SPRING - SUMMER COLLECTION 2025</p>
        <p class="font-new text-center">ROSIE CRUSH</p>
        <p class="text-center text-secondary mx-auto fw-bold" style="max-width: 800px">Vẻ đẹp của sắc hồng không chỉ nằm
            ở sự ngọt ngào mà còn ở cách gam màu ấy khơi gợi nét quyến rũ ở phái đẹp. Với BST Rosie Crush, IVY moda đã
            khéo léo đưa sức hút độc bản ấy vào từng thiết kế, tạo nên nét chấm phá đầy tinh tế cho tủ đồ của quý cô
            công sở hiện đại. Từ gam pastel dịu nhẹ đến sắc magenta quyến rũ, Rosie Crush vừa mang vẻ e ấp, ngọt ngào,
            vừa có chút say mê, lãng mạn như mang theo mọi cung bậc cảm xúc của màu hồng. Được may từ chất vải mềm nhẹ,
            thoáng mát, mỗi thiết kế ấy đều nâng niu dáng vẻ yêu kiều của nàng, tôn lên đường nét một cách tự nhiên và
            tinh tế nhất.</p>
        <div class="row g-4 mt-2">

            <div class="col-md-6">
                <div class="overflow-hidden rounded-4 ">
                    <img src="../img/women/collect31.webp" class="img-fluid img-fix" alt="Banner 1" />
                </div>
            </div>

            <div class="col-md-6">
                <div class=" overflow-hidden rounded-4">
                    <img src="../img/women/collect32.webp" class="img-fluid img-fix" alt="Banner 2" />
                </div>
            </div>
        </div>
        <div class="row mt-5 ">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="col-md-4">
                <div class="card shadow-sm w-100 rounded-0" style="max-width: 380px;">
                    <a href="product.php?pId=<?php echo $row['id']; ?>">
                        <img src="../<?php echo $row['image_url']; ?>" class="card-img-top"
                            alt="<?php echo htmlspecialchars($row['name']); ?>">
                    </a>
                    <div class="card-body px-2 py-2">
                        <h6 class="card-title text-truncate text-secondary mb-2 fw-semibold"
                            style="font-size: 0.95rem;">
                            <?php echo $row['name']; ?>
                        </h6>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fw-bold text-dark"
                                style="font-size: 0.9rem;"><?php echo number_format($row['price']); ?>₫</span>
                            <a href="product.php?pId=<?=$row['id']?>" class="btn btn-outline-secondary">
                                <i class="bi bi-bag"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

        </div>

    </main>
    <script src="../bootstrap/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <?php include("footer.html"); ?>
</body>

</html>