<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['email'])) {
    // Chuyển hướng đến trang đăng nhập
    header("Location: ../template/log-in.html");
    exit();
}
include("header.php");
require_once('../handlers/dbcon.php');
require_once('../handlers/user_profile_handlers.php');


// Lấy user
$userDetails = getUserDetails($con, $_SESSION['email']);
$user_id = $userDetails['id'];
// echo $user_id;
$username = $userDetails['username'];
$email = $userDetails['email'];
$number = $userDetails['phone'];
$gender = $userDetails['gender'] ?? '';
$birthday = $userDetails['birthday'] ?? '';

// Lấy địa chỉ mặc định
$addressQuery = "SELECT * FROM address WHERE user_id = $user_id AND is_default = 1";
$addressResult = mysqli_query($con, $addressQuery);
$addressRow = mysqli_fetch_assoc($addressResult);
$fullAddress = isset($addressRow) ? $addressRow['detail'] . ', ' . $addressRow['ward'] . ', ' . $addressRow['district'] . ', ' . $addressRow['province'] : '';

// Lấy sản phẩm trong giỏ hàng từ CSDL
$cartQuery = "
    SELECT c.id AS cart_id, c.product_id, p.name AS product_name, p.price AS product_price,
           pi.image_url AS product_image, c.quantity, s.name AS size_name, c.size_id
    FROM cart c
    JOIN products p ON c.product_id = p.id
    LEFT JOIN (
        SELECT product_id, MIN(image_url) AS image_url
        FROM product_images
        GROUP BY product_id
    ) pi ON pi.product_id = p.id
    LEFT JOIN sizes s ON c.size_id = s.id
    WHERE c.user_id = $user_id
";

$cartResult = mysqli_query($con, $cartQuery);
if (!$cartResult) {
    die("Lỗi truy vấn giỏ hàng: " . mysqli_error($con)); // In lỗi SQL
}
$cartItems = mysqli_fetch_all($cartResult, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../static/cart.css">
    <title>Cart</title>
</head>

<body>
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8">
                <!-- Thông tin đặt hàng -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Thông tin đặt hàng</h5>
                    <form action="../handlers/user_profile_handlers.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Họ và tên</label>
                                <div class="input-group">
                                    <select class="form-select" name="title" style="max-width: 100px">
                                        <option value="Anh" <?php echo ($gender == 'Nam') ? 'selected' : ''; ?>>Anh
                                        </option>
                                        <option value="Chị" <?php echo ($gender == 'Nữ') ? 'selected' : ''; ?>>Chị
                                        </option>
                                    </select>
                                    <input type="text" name="username" class="form-control" placeholder="Họ và tên"
                                        value="<?php echo $username; ?>" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" name="number" class="form-control" placeholder="Số điện thoại"
                                    value="<?php echo $number; ?>" required />
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Email"
                                    value="<?php echo $email; ?>" />
                            </div>

                            <?php if (!empty($addressRow)): ?>
                            <div class="col-12">
                                <label class="form-label">Địa chỉ cụ thể</label>
                                <input type="text" name="address" class="form-control" placeholder="Địa chỉ"
                                    value="<?php echo $addressRow['detail']; ?>" required />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tỉnh/Thành</label>
                                <select class="form-select" id="province" name="province" required>
                                    <option value="<?php echo $addressRow['province']; ?>" selected>
                                        <?php echo $addressRow['province']; ?></option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Quận/Huyện</label>
                                <select class="form-select" id="district" name="district" required>
                                    <option value="<?php echo $addressRow['district']; ?>" selected>
                                        <?php echo $addressRow['district']; ?></option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Phường/Xã</label>
                                <select class="form-select" id="ward" name="ward" required>
                                    <option value="<?php echo $addressRow['ward']; ?>" selected>
                                        <?php echo $addressRow['ward']; ?></option>
                                </select>
                            </div>
                            <?php else: ?>
                            <div class="col-12">
                                <label class="form-label">Địa chỉ cụ thể</label>
                                <input type="text" name="address" class="form-control"
                                    placeholder="Số nhà, tên đường..." required />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tỉnh/Thành</label>
                                <select class="form-select" id="province" name="province" required>
                                    <option value="">Chọn Tỉnh/Thành</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Quận/Huyện</label>
                                <select class="form-select" id="district" name="district" required>
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Phường/Xã</label>
                                <select class="form-select" id="ward" name="ward" required>
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                            </div>
                            <input type="hidden" id="province_code" />
                            <input type="hidden" id="district_code" />
                            <?php endif; ?>
                            <div class="col-12">
                                <button type="submit" name="add_address" class="btn btn-secondary">Lưu địa chỉ này cho
                                    lần
                                    sau</button>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Ghi chú</label>
                                <textarea class="form-control" name="note"
                                    placeholder="Ghi chú thêm (Ví dụ: Giao hàng giờ hành chính)"></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Hình thức thanh toán -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Hình thức thanh toán</h5>
                    <div class="payment-option mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" id="cod" checked />
                            <label class="form-check-label d-flex align-items-center" for="cod">
                                <img src="https://mcdn.coolmate.me/image/October2024/mceclip2_42.png" alt="COD"
                                    class="payment-logo" />
                                <span>Thanh toán khi nhận hàng</span>
                            </label>
                        </div>
                    </div>
                    <div class="payment-option mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" id="momo" />
                            <label class="form-check-label d-flex align-items-center" for="momo">
                                <img src="https://mcdn.coolmate.me/image/October2024/mceclip1_171.png" alt="MoMo"
                                    class="payment-logo" />
                                <span>Ví MoMo</span>
                            </label>
                        </div>
                    </div>
                    <div class="payment-option mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" id="zalopay" />
                            <label class="form-check-label d-flex align-items-center" for="zalopay">
                                <img src="https://mcdn.coolmate.me/image/October2024/mceclip3_6.png" alt="ZaloPay"
                                    class="payment-logo" />
                                <span>Thanh toán qua ZaloPay</span>
                            </label>
                        </div>
                    </div>
                    <div class="payment-option">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" id="vnpay" />
                            <label class="form-check-label d-flex align-items-center" for="vnpay">
                                <img src="https://mcdn.coolmate.me/image/October2024/mceclip0_81.png" alt="VnPay"
                                    class="payment-logo" />
                                <span>Ví điện tử VNPAY</span>
                            </label>
                        </div>
                    </div>
                    <p class="mt-3 small">
                        Nếu bạn không hài lòng với sản phẩm của chúng tôi? Bạn hoàn toàn
                        có thể trả lại sản phẩm.
                        <a href="Q&A.php" class="text-decoration-none">Tìm hiểu thêm tại đây</a>.
                    </p>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Giỏ hàng -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="fw-bold m-0">Giỏ hàng</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($cartItems)): ?>
                        <form id="cart-form">
                            <?php foreach ($cartItems as $index => $item):  ?>

                            <div class="d-flex align-items-center mb-3">
                                <div class="form-check me-3">
                                    <input class="form-check-input product-checkbox" type="checkbox"
                                        data-price="<?php echo $item['product_price']; ?>"
                                        data-quantity="<?php echo $item['quantity']; ?>"
                                        data-index="<?php echo $index; ?>" />
                                </div>
                                <div class="me-3">
                                    <img src="../<?php echo $item['product_image']; ?>"
                                        alt="<?php echo $item['product_name']; ?>" class="product-image" />
                                </div>
                                <!-- Trong cart.php, cập nhật hiển thị sản phẩm -->
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?php echo $item['product_name']; ?></h6>
                                    <p class="mb-1 text-muted small">
                                        Kích thước:
                                        <?php echo $item['size_name'] ? $item['size_name'] : 'Chưa chọn'; ?><br>
                                        Số lượng: <?php echo $item['quantity']; ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">
                                            <?php echo number_format($item['product_price'] , 0, ',', '.'); ?>₫
                                        </span>
                                        <button type="button" class="btn btn-danger btn-sm delete-product"
                                            data-product-id="<?php echo $item['product_id']; ?>"
                                            data-size-id="<?php echo $item['size_id']; ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </form>

                        <!-- Ưu đãi đi kèm -->
                        <div class="border rounded p-3 mb-3">
                            <p class="mb-3 fw-bold">
                                Có thể người dùng thêm cùng sản phẩm này vào giỏ hàng:
                            </p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="promo-item h-100">
                                        <span class="badge bg-danger mb-2">Save</span>
                                        <div class="text-center mb-2">
                                            <img src="../img/men/phukien/tui1.webp" alt="Túi" class="img-fluid w-50"
                                                style="max-height: 80px" />
                                        </div>
                                        <h6 class="mb-1">Túi Coolmate Clean Bag</h6>
                                        <p class="small mb-2">Sản phẩm mới tới<br />49.000₫</p>
                                        <button class="btn btn-get btn-sm w-100">Xem ngay</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="promo-item h-100">
                                        <span class="badge bg-danger mb-2">Save</span>
                                        <div class="text-center mb-2">
                                            <img src="../img/men/phukien/tat1.webp" alt="Tất" class="img-fluid w-50"
                                                style="max-height: 80px" />
                                        </div>
                                        <h6 class="mb-1">Combo 2 đôi tất cổ trung</h6>
                                        <p class="small mb-2">Sản phẩm mới tới<br />49.000₫</p>
                                        <button class="btn btn-get btn-sm w-100">Xem ngay</button>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <form method="POST" action="../handlers/place_order.php">
                            <!-- Tổng tiền -->
                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tạm tính</span>
                                    <span id="subtotal" class="fw-bold">
                                        <?php 
                $subtotal = array_sum(array_map(function ($item) {
                    return $item['product_price'] * $item['quantity'];
                }, $cartItems));
                echo number_format($subtotal, 0, ',', '.'); 
                ?>₫
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Phí giao hàng</span>
                                    <span>Miễn phí</span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold fs-5 mt-3">
                                    <span>Tổng</span>
                                    <span id="total-price">
                                        <?php 
                $total = $subtotal;
                echo number_format($total, 0, ',', '.') . '₫'; 
                ?>
                                    </span>
                                </div>
                                <input type="hidden" name="total_price" value="<?php echo $total; ?>">
                                <input type="hidden" name="user_email" value="<?php echo $email; ?>">
                                <input type="hidden" name="payment_method" id="payment-method" value="cod">
                                <button type="submit" name="place_order" class="btn btn-danger w-100 mt-3 py-2"
                                    id="checkout-button" <?php echo empty($cartItems) ? 'disabled' : ''; ?>>
                                    ĐẶT HÀNG
                                </button>

                            </div>
                        </form>
                        <?php else: ?>
                        <p>Giỏ hàng của bạn đang trống.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../js/user_profile.js"></script>
</body>
<script src="../js/cart.js"></script>

</html>
<?php include("footer.html"); ?>