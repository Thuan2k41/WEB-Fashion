<!-- filepath: e:\VS code\xampp\htdocs\smart-fashion\template\list_product.php -->
<?php
include("header.php");
require_once('../handlers/dbcon.php');

// Lấy tham số từ URL
$subcategory_id = isset($_GET['subcategory']) ? intval($_GET['subcategory']) : 0;
$tag = isset($_GET['tag']) ? $_GET['tag'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$price_filters = isset($_GET['price']) ? $_GET['price'] : [];

// Xử lý sắp xếp
$order_by = match ($sort) {
    'price_asc' => 'ORDER BY p.price ASC',
    'price_desc' => 'ORDER BY p.price DESC',
    'newest' => 'ORDER BY p.created_at DESC',
    'best_seller' => 'ORDER BY p.sold DESC',
    default => 'ORDER BY RAND()',
};

// Xử lý bộ lọc mức giá
$price_conditions = [];
foreach ($price_filters as $price_range) {
    $price_conditions[] = match ($price_range) {
        '0-500000' => "(p.price >= 0 AND p.price <= 500000)",
        '500000-1000000' => "(p.price > 500000 AND p.price <= 1000000)",
        '1000000+' => "(p.price > 1000000)",
        default => '',
    };
}
$price_query = !empty($price_conditions) ? ' AND (' . implode(' OR ', $price_conditions) . ')' : '';

// Truy vấn sản phẩm từ cơ sở dữ liệu
if ($subcategory_id > 0) {
    $sql = "
        SELECT p.id, p.name, p.price, p.tag, pi.image_url
        FROM products p
        LEFT JOIN product_images pi ON p.id = pi.product_id
        WHERE p.subcategory = $subcategory_id
        $price_query
        $order_by
    ";
} elseif (!empty($tag)) {
    $sql = "
        SELECT p.id, p.name, p.price, p.tag, pi.image_url
        FROM products p
        JOIN subcategories s ON p.subcategory = s.id
        LEFT JOIN product_images pi ON p.id = pi.product_id
        WHERE p.tag = '" . mysqli_real_escape_string($con, $tag) . "'
        $price_query
        $order_by
    ";
} else {
    $sql = "
        SELECT p.id, p.name, p.price, p.tag, pi.image_url
        FROM products p
        LEFT JOIN product_images pi ON p.id = pi.product_id
        WHERE 1
        $price_query
        $order_by
    ";
}

$result = mysqli_query($con, $sql);
if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($con));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="../static/list_product.css">
    <link rel="stylesheet" href="../static/home.css">
    <link rel="stylesheet" href="../bootstrap/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home.php">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php
                    if ($subcategory_id > 0) {
                        $sql_subcategory = "SELECT name FROM subcategories WHERE id = $subcategory_id";
                        $result_subcategory = mysqli_query($con, $sql_subcategory);
                        $subcategory = mysqli_fetch_assoc($result_subcategory);
                        echo htmlspecialchars($subcategory['name']);
                    } elseif (!empty($tag)) {
                        echo $tag === 'sale' ? 'Sản phẩm giảm giá' : 'Sản phẩm mới';
                    } else {
                        echo 'Danh sách sản phẩm';
                    }
                    ?>
                </li>
            </ol>
        </nav>

        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-2">
                <form method="GET" action="">
                    <!-- Size Filter -->
                    <div class="filter-section d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Size</h5>
                        <button class="btn btn-toggle" type="button" data-bs-toggle="collapse"
                            data-bs-target="#sizeCollapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="collapse" id="sizeCollapse">
                        <div class="filter-content">
                            <label><input type="checkbox" name="size" value="S"> S</label>
                            <label><input type="checkbox" name="size" value="M"> M</label>
                            <label><input type="checkbox" name="size" value="L"> L</label>
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="filter-section d-flex justify-content-between align-items-center mt-4">
                        <h5 class="mb-0 fw-bold">Mức giá</h5>
                        <button class="btn btn-toggle" type="button" data-bs-toggle="collapse"
                            data-bs-target="#priceCollapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="collapse" id="priceCollapse">
                        <div class="filter-content">
                            <label><input type="checkbox" name="price[]" value="0-500000"
                                    <?php if (in_array('0-500000', $price_filters)) echo 'checked'; ?>> Dưới
                                500,000₫</label>
                            <label><input type="checkbox" name="price[]" value="500000-1000000"
                                    <?php if (in_array('500000-1000000', $price_filters)) echo 'checked'; ?>> 500,000₫ -
                                1,000,000₫</label>
                            <label><input type="checkbox" name="price[]" value="1000000+"
                                    <?php if (in_array('1000000+', $price_filters)) echo 'checked'; ?>> Trên
                                1,000,000₫</label>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <button type="reset" class="filter-button" onclick="window.location.href='list_product.php';">BỎ
                            LỌC</button>
                        <button type="submit" class="filter-button active">LỌC</button>
                    </div>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3">Danh sách sản phẩm:</h2>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Sắp xếp theo
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item"
                                    href="?subcategory=<?php echo $subcategory_id; ?>&sort=price_asc">Giá: Thấp đến
                                    cao</a></li>
                            <li><a class="dropdown-item"
                                    href="?subcategory=<?php echo $subcategory_id; ?>&sort=price_desc">Giá: Cao đến
                                    thấp</a></li>
                            <li><a class="dropdown-item"
                                    href="?subcategory=<?php echo $subcategory_id; ?>&sort=newest">Mới nhất</a></li>
                            <li><a class="dropdown-item"
                                    href="?subcategory=<?php echo $subcategory_id; ?>&sort=best_seller">Bán chạy</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '
                        <div class="col-6 col-md-4 col-lg-3 mb-4">
                            <div class="card shadow-sm border-0">
                                <span class="badge-new text-uppercase">' . htmlspecialchars($row['tag']) . '</span>
                                <a href="product.php?pId=' . $row['id'] . '">
                                    <img src="../' . htmlspecialchars($row['image_url']) . '" class="card-img-top" alt="' . htmlspecialchars($row['name']) . '">
                                </a>
                                <div class="card-body px-2 py-2">
                                    <h6 class="card-title text-truncate text-secondary mb-2 fw-semibold" style="font-size: 0.95rem;">
                                        ' . htmlspecialchars($row['name']) . '
                                    </h6>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fw-bold text-dark" style="font-size: 0.9rem;">' . number_format($row['price']) . '₫</span>
                                        <a href="product.php?pId=' . $row['id'] . '" class="btn btn-outline-secondary">
                                            <i class="bi bi-bag"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/list_product.js"></script>
<script src="../bootstrap/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

</html>
<?php include("footer.html"); ?>