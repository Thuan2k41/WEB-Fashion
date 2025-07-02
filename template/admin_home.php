<?php
// Kết nối cơ sở dữ liệu
require_once '../handlers/dbcon.php';

// Truy vấn danh sách khách hàng
$customerQuery = "SELECT id, username, email, phone FROM users";
$customerResult = mysqli_query($con, $customerQuery);
$customers = mysqli_fetch_all($customerResult, MYSQLI_ASSOC);
// Xử lý lọc sản phẩm
$productQuery = "SELECT id, name, price, quantity, subcategory, description, tag, sold, created_at FROM products";
if (isset($_GET['sort'])) {
    switch ($_GET['sort']) {
        case 'price_asc':
            $productQuery .= " ORDER BY price ASC";
            break;
        case 'price_desc':
            $productQuery .= " ORDER BY price DESC";
            break;
        case 'best_selling':
            $productQuery .= " ORDER BY sold DESC";
            break;
        default:
            // Không thêm điều kiện nếu tham số không hợp lệ
            break;
    }
}
$productResult = mysqli_query($con, $productQuery);
$products = mysqli_fetch_all($productResult, MYSQLI_ASSOC);

// Xử lý lọc đơn hàng (nếu cần)
$orderQuery = "
    SELECT 
        o.id AS order_id,
        u.username AS customer_name,
        u.email AS customer_email,
        o.order_date,
        COUNT(oi.id) AS product_count,
        SUM(oi.quantity * oi.price) AS total,
        o.status,
        o.payment_method
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN order_items oi ON o.id = oi.order_id
    GROUP BY o.id
    ORDER BY o.order_date DESC
";
$orderResult = mysqli_query($con, $orderQuery);
if (!$orderResult) {
    die("Lỗi truy vấn đơn hàng: " . mysqli_error($con));
}

$orders = mysqli_fetch_all($orderResult, MYSQLI_ASSOC);
// Thống kê tổng số sản phẩm
$totalProductsQuery = "SELECT COUNT(*) AS total_products FROM products";
$totalProductsResult = mysqli_query($con, $totalProductsQuery);
$totalProducts = mysqli_fetch_assoc($totalProductsResult)['total_products'];

// Thống kê sản phẩm bán chạy nhất
$bestSellingProductQuery = "SELECT name, sold FROM products ORDER BY sold DESC LIMIT 1";
$bestSellingProductResult = mysqli_query($con, $bestSellingProductQuery);
$bestSellingProduct = mysqli_fetch_assoc($bestSellingProductResult);

// Thống kê tổng số lượng sản phẩm đã bán
$totalSoldQuery = "SELECT SUM(sold) AS total_sold FROM products";
$totalSoldResult = mysqli_query($con, $totalSoldQuery);
$totalSold = mysqli_fetch_assoc($totalSoldResult)['total_sold'];
// Thống kê thu nhập 
$total = "SELECT SUM(total_price) AS total_price FROM orders";
$totalResult = mysqli_query($con, $total);
$totalP = mysqli_fetch_assoc($totalResult)['total_price'];
// Thống kê tổng số đơn hàng
$totalOrdersQuery = "SELECT COUNT(*) AS total_orders FROM orders";
$totalOrdersResult = mysqli_query($con, $totalOrdersQuery);
$totalOrders = mysqli_fetch_assoc($totalOrdersResult)['total_orders'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    <link rel="shortcut icon" href="../img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../static/admin.css">
    <link rel="stylesheet" href="../static/user_profile.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../bootstrap/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!--  -->
    <script src="../js/admin.js"></script>
</head>

<body>
    <div class="container-fluid">
        <!-- navbar -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container">
                <a class="navbar-brand" href="#"><img src="../img/main/logo.png" alt=""
                        style="height:40px; width: 60px;"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <form class="d-flex ms-auto me-2" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-dark" type="submit"><i class="bi bi-search"></i></button>

                    </form>

                    <a href="home.php" class="btn btn-outline-light">
                        <i class="bi bi-house-door-fill text-black fs-4"></i>
                    </a>

                    <a href="../handlers/logout.php">
                        <i class="bi bi-box-arrow-in-right text-dark fs-4"></i>
                    </a>

                </div>
            </div>
        </nav>
        <!--  -->
        <div class="row g-4">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4 ">
                <div class="sidebar-menu">
                    <a href="#" class="menu-item active" onclick="showTab('sanpham',this)">
                        <div class="menu-icon">
                            <span class="icon"><i class="bi bi-box-seam"></i></span>
                            <span>Các sản phẩm</span>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                    <a href="#" class="menu-item " onclick="showTab('danhmuc',this)">
                        <div class="menu-icon">
                            <span class="icon"><i class="bi bi-folder-fill"></i></span>
                            <span>Danh mục</span>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="#" class="menu-item" onclick="showTab('donhang',this)">
                        <div class="menu-icon">
                            <span class="icon"><i class="bi bi-cart-fill"></i></span>
                            <span>Đơn hàng</span>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                    <a href="#" class="menu-item" onclick="showTab('khuyenmai',this)">
                        <div class="menu-icon">
                            <span class="icon"><i class="bi bi-tag-fill"></i></span>
                            <span>Khuyến mãi</span>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="#" class="menu-item" onclick="showTab('khachhang',this)">
                        <div class="menu-icon">
                            <span class="icon"><i class="bi bi-people-fill"></i></span>
                            <span>Khách hàng</span>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                </div>
            </div>

            <!-- Main content -->
            <div class=" col-lg-9 col-md-8">
                <!-- san pham --------------------- -->
                <div class="profile-content tab-pane active" id="sanpham">
                    <div class="row d-flex">
                        <!-- Tổng số sản phẩm -->
                        <div class="col-md-3">
                            <div class="bg-white p-3 shadow-sm rounded">
                                <h6>Tổng số sản phẩm</h6>
                                <h4><?php echo $totalProducts; ?></h4>
                                <small class="text-muted">Tổng số sản phẩm hiện có trong kho</small>
                            </div>
                        </div>

                        <!-- Sản phẩm bán chạy nhất -->
                        <div class="col-md-3">
                            <div class="bg-white p-3 shadow-sm rounded">
                                <h6>Sản phẩm bán chạy nhất</h6>
                                <h4><?php echo htmlspecialchars($bestSellingProduct['name']); ?></h4>
                                <small class="text-success">Đã bán: <?php echo $bestSellingProduct['sold']; ?> sản
                                    phẩm</small>
                            </div>
                        </div>

                        <!-- Tổng số lượng sản phẩm đã bán -->
                        <div class="col-md-3">
                            <div class="bg-white p-3 shadow-sm rounded">
                                <h6>Tổng số lượng sản phẩm đã bán</h6>
                                <h4><?php echo $totalSold; ?></h4>
                                <small class="text-muted">Tổng số lượng sản phẩm đã bán được</small>
                            </div>
                        </div>
                    </div>
                    <nav class="navbar navbar-expand-lg bg-body-tertiary">
                        <div class="container">
                            <h5>Tổng quan sản phẩm</h5>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <div class="dropdown d-flex ms-auto">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Lọc sản phẩm
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="?sort=price_asc">Giá thấp - cao</a></li>
                                        <li><a class="dropdown-item" href="?sort=price_desc">Giá cao - thấp</a></li>
                                        <li><a class="dropdown-item" href="?sort=best_selling">Mặt hàng bán chạy</a>
                                        </li>
                                    </ul>
                                </div>

                                <button class="btn btn-secondary ms-2" data-bs-toggle="modal"
                                    data-bs-target="#addProductModal">Thêm sản phẩm</button>
                                <a href="../template/chart.php" class="btn btn-secondary ms-2">Biểu đồ</a>
                                <form class="d-flex ms-auto me-2" role="search">
                                    <input class="form-control me-2" type="search" placeholder="Search"
                                        aria-label="Search">
                                    <button class="btn btn-outline-dark" type="submit"><i
                                            class="bi bi-search"></i></button>
                                </form>
                            </div>
                        </div>
                    </nav>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Danh mục con</th>
                                <th>Mô tả</th>
                                <th>Tag</th>
                                <th>Đã bán</th>

                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo number_format($product['price'], 0, ',', '.'); ?> VND</td>
                                <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($product['subcategory']); ?></td>
                                <td><?php echo htmlspecialchars($product['description']); ?></td>
                                <td><?php echo htmlspecialchars($product['tag']); ?></td>
                                <td><?php echo htmlspecialchars($product['sold']); ?></td>
                                <td>
                                    <!-- Nút sửa -->
                                    <a href="#" class="text-primary me-3 edit-product"
                                        data-id="<?php echo htmlspecialchars($product['id']); ?>"
                                        data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                        data-price="<?php echo htmlspecialchars($product['price']); ?>"
                                        data-quantity="<?php echo htmlspecialchars($product['quantity']); ?>"
                                        data-subcategory="<?php echo htmlspecialchars($product['subcategory']); ?>"
                                        data-description="<?php echo htmlspecialchars($product['description']); ?>"
                                        data-tag="<?php echo htmlspecialchars($product['tag']); ?>"
                                        data-sold="<?php echo htmlspecialchars($product['sold']); ?>" title="Chỉnh sửa"
                                        data-bs-toggle="modal" data-bs-target="#editProductModal">
                                        <i class="bi bi-pencil-square fs-5"></i>
                                    </a>

                                    <!-- Nút xóa -->
                                    <a href="../handlers/admin_products.php?action=delete&id=<?php echo htmlspecialchars($product['id']); ?>"
                                        class="text-danger delete-product" title="Xóa"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                        <i class="bi bi-trash fs-5"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Không có sản phẩm nào.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- đơn hàng  -->
                <div class="profile-content tab-pane" id="donhang">
                    <div class="row d-flex">
                        <div class="col-md-3">
                            <div class="bg-white p-3 shadow-sm rounded">
                                <h6>Tổng doanh thu</h6>
                                <h4><?php echo $totalP; ?>VNĐ</h4>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-white p-3 shadow-sm rounded">
                                <h6>Tổng số đơn hàng</h6>
                                <h4><?php echo $totalOrders; ?></h4>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="bg-white p-3 shadow-sm rounded">
                                <h6>Tổng số lượng sản phẩm đã bán</h6>
                                <h4><?php echo $totalSold; ?></h4>

                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <input type="text" class="form-control w-25" placeholder="🔍 Tìm kiếm...">
                        <div>
                            <div class="dropdown d-flex ms-auto">
                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Lọc
                                </button>
                                <div class="dropdown d-flex ms-auto">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Lọc đơn hàng
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="?order_status=delivered">Đã giao</a></li>
                                        <li><a class="dropdown-item" href="?order_status=shipping">Đang vận chuyển</a>
                                        </li>
                                        <li><a class="dropdown-item" href="?order_status=canceled">Đã hủy</a></li>
                                        <li><a class="dropdown-item" href="?order_status=processing">Đang xử lý</a></li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- danh mục đơn hàng  -->
                    <div class="table-responsive bg-white rounded shadow-sm p-3">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Khách hàng</th>
                                    <th>Ngày</th>
                                    <th>Các sản phẩm</th>
                                    <th>Tổng cộng</th>
                                    <th>Trạng thái</th>
                                    <th>Sự chi trả</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($order['order_id']); ?></td>
                                    <td><strong><?php echo htmlspecialchars($order['customer_name']); ?></strong><br>
                                        <small
                                            class="text-muted"><?php echo htmlspecialchars($order['customer_email']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                    <td><?php echo htmlspecialchars($order['product_count']); ?></td>
                                    <td><?php echo number_format($order['total'], 0, ',', '.'); ?> VND</td>
                                    <td><span
                                            class="badge bg-<?php echo $order['status'] === 'delivered' ? 'success' : ($order['status'] === 'shipping' ? 'primary' : ($order['status'] === 'canceled' ? 'danger' : 'warning')); ?>">
                                            <?php echo htmlspecialchars($order['status']); ?>
                                        </span></td>
                                    <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                                    <td>
                                        <i class="bi bi-eye me-2 text-dark" title="Xem chi tiết"></i>
                                        <i class="bi bi-pencil-square me-2 text-warning" title="Chỉnh sửa"></i>
                                        <i class="bi bi-trash text-danger" title="Xóa"></i>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">Không có đơn hàng nào.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>



                </div>
                <!-- khuyen mai ------------------------>

                <!-- khach hang  -->
                <div class="profile-content tab-pane" id="khachhang">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Quản lý khách hàng</h5>
                        <div>
                            <form id="filterCustomerForm" class="d-inline-block" method="GET" action="">
                                <input type="text" name="search" class="form-control d-inline-block w-auto"
                                    placeholder="Tìm kiếm..."
                                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <button type="submit" class="btn btn-outline-secondary">Lọc</button>
                            </form>
                            <button class="btn btn-pink text-white" style="background-color:rgb(160, 144, 150);"
                                data-bs-toggle="modal" data-bs-target="#addCustomerModal">Thêm khách hàng mới</button>
                        </div>
                    </div>

                    <div class="table-responsive bg-white rounded shadow-sm p-3">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Khách hàng</th>
                                    <th>Email</th>
                                    <th>Điện thoại</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($customers)): ?>
                                <?php foreach ($customers as $customer): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($customer['username']); ?></td>
                                    <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                    <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                                    <td>
                                        <a href="#" class="edit-customer me-3"
                                            data-id="<?php echo htmlspecialchars($customer['id']); ?>"
                                            data-name="<?php echo htmlspecialchars($customer['username']); ?>"
                                            data-email="<?php echo htmlspecialchars($customer['email']); ?>"
                                            data-phone="<?php echo htmlspecialchars($customer['phone']); ?>">
                                            <i class="bi bi-pencil-square text-warning fs-5" title="Chỉnh sửa"></i>
                                        </a>
                                        <a href="#" class="delete-customer"
                                            data-id="<?php echo htmlspecialchars($customer['id']); ?>">
                                            <i class="bi bi-trash text-danger fs-5" title="Xóa"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Không tìm thấy khách hàng nào.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>





    <!-- them san pham  -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="addProductForm" action="../handlers/admin_products.php" method="POST"
                enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Thêm sản phẩm mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">

                        <!-- Tên sản phẩm -->
                        <div class="mb-3">
                            <label for="addProductName" class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="addProductName" name="name" required>
                        </div>

                        <!-- Giá -->
                        <div class="mb-3">
                            <label for="addProductPrice" class="form-label">Giá</label>
                            <input type="number" class="form-control" id="addProductPrice" name="price" required>
                        </div>

                        <!-- Danh mục con -->
                        <div class="mb-3">
                            <label for="addProductSubcategory" class="form-label">Danh mục con</label>
                            <input type="text" class="form-control" id="addProductSubcategory" name="subcategory"
                                required>
                        </div>

                        <!-- Mô tả -->
                        <div class="mb-3">
                            <label for="addProductDescription" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="addProductDescription" name="description" rows="3"
                                required></textarea>
                        </div>

                        <!-- Tag -->
                        <div class="mb-3">
                            <label for="addProductTag" class="form-label">Tag</label>
                            <input type="text" class="form-control" id="addProductTag" name="tag" required>
                        </div>

                        <!-- Số lượng -->
                        <div class="mb-3">
                            <label for="addProductQuantity" class="form-label">Số lượng</label>
                            <input type="number" class="form-control" id="addProductQuantity" name="quantity" required>
                        </div>
                        <!-- Thêm ảnh sản phẩm -->
                        <div class="mb-3">
                            <label for="addProductImages" class="form-label">Thêm ảnh sản phẩm</label>
                            <input type="file" class="form-control" id="addProductImages" name="images[]" multiple
                                required>
                            <small class="text-muted">Bạn có thể chọn nhiều ảnh (JPG, PNG, GIF).</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Thêm size và số lượng</label>
                            <div class="row">
                                <?php
        // Truy vấn danh sách size từ bảng `sizes`
        require_once('../handlers/dbcon.php');
        $query = "SELECT id, name FROM sizes";
        $result = mysqli_query($con, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            echo "
            <div class='col-md-4'>
                <div class='form-group'>
                    <label for='size_{$row['id']}'>{$row['name']}</label>
                    <input type='number' class='form-control' id='size_{$row['id']}' name='sizes[{$row['id']}]' placeholder='Nhập số lượng' min='0'>
                </div>
            </div>";
        }
        ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-success">Thêm sản phẩm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- modal sua san pham  -->
    <div class="modal fade " id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="editProductForm" action="../handlers/admin_products.php" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel">Chỉnh sửa sản phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="editProductId">

                        <!-- Tên sản phẩm -->
                        <div class="mb-3">
                            <label for="editProductName" class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="editProductName" name="name" required>
                        </div>

                        <!-- Giá -->
                        <div class="mb-3">
                            <label for="editProductPrice" class="form-label">Giá</label>
                            <input type="number" class="form-control" id="editProductPrice" name="price" required>
                        </div>

                        <!-- Số lượng -->
                        <div class="mb-3">
                            <label for="editProductQuantity" class="form-label">Số lượng</label>
                            <input type="number" class="form-control" id="editProductQuantity" name="quantity" required>
                        </div>

                        <!-- Danh mục con -->
                        <div class="mb-3">
                            <label for="editProductSubcategory" class="form-label">Danh mục con</label>
                            <input type="text" class="form-control" id="editProductSubcategory" name="subcategory"
                                required>
                        </div>

                        <!-- Mô tả -->
                        <div class="mb-3">
                            <label for="editProductDescription" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="editProductDescription" name="description" rows="3"
                                required></textarea>
                        </div>

                        <!-- Tag -->
                        <div class="mb-3">
                            <label for="editProductTag" class="form-label">Tag</label>
                            <input type="text" class="form-control" id="editProductTag" name="tag" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- modal sua tt khach hang  -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="editCustomerForm" action="../handlers/admin_customer.php" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCustomerModalLabel">Chỉnh sửa thông tin khách hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="editCustomerId">
                        <div class="mb-3">
                            <label for="editCustomerName" class="form-label">Tên khách hàng</label>
                            <input type="text" class="form-control" id="editCustomerName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCustomerEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editCustomerEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCustomerPhone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="editCustomerPhone" name="phone" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- them moi khach hang  -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="addCustomerForm" action="../handlers/admin_customer.php" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCustomerModalLabel">Thêm khách hàng mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="addCustomerName" class="form-label">Tên khách hàng</label>
                            <input type="text" class="form-control" id="addCustomerName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="addCustomerEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="addCustomerEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="addCustomerPhone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="addCustomerPhone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="addCustomerPassword" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="addCustomerPassword" name="password"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-success">Thêm khách hàng</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
<script src="../bootstrap/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

</html>