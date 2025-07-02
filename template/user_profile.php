<?php
include("header.php");
require_once('../handlers/user_profile_handlers.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// echo $_SESSION['username'];
$userDetails = getUserDetails($con, $_SESSION['email']);
$username = $userDetails['username'];
$email = $userDetails['email'] ?? '';
$number = $userDetails['phone'] ?? 'Chưa cập nhật';
$gender = $userDetails['gender'] ?? 'Không xác định';
$birthday = $userDetails['birthday'] ?? 'Chưa cập nhật';
// echo $userDetails['id'];
// Lấy địa chỉ mặc định nếu có
$addressQuery = "SELECT * FROM address WHERE user_id = {$userDetails['id']} AND is_default = 1";
$addressResult = mysqli_query($con, $addressQuery);
$addressRow = mysqli_fetch_assoc($addressResult);
$fullAddress = isset($addressRow) ? $addressRow['detail'] . ', ' . $addressRow['ward'] . ', ' . $addressRow['district'] . ', ' . $addressRow['province'] : '';
// Lấy danh sách đơn hàng của người dùng
$orderQuery = "SELECT * FROM orders WHERE user_id = {$userDetails['id']} ORDER BY order_date DESC";
$orderResult = mysqli_query($con, $orderQuery);
$orders = mysqli_fetch_all($orderResult, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../static/user_profile.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="../js/user_profile.js"></script>

</head>

<body>
    <div class="container p-4">
        <div class="row g-4">
            <!-- Sidebar Menu -->
            <div class="col-lg-4 col-md-5">
                <div class="sidebar-menu">
                    <a href="#" class="menu-item active" onclick="showTab('thongtin',this)">
                        <div class="menu-icon">
                            <span class="icon"><i class="fas fa-user"></i></span>
                            <span>Thông tin tài khoản</span>
                        </div>
                        <i class="fas fa-chevron-right"></i>
                    </a>

                    <a href="#" class="menu-item " onclick="showTab('lichsu',this)">
                        <div class="menu-icon">
                            <span class="icon"><i class="fas fa-shopping-bag"></i></span>
                            <span>Lịch sử đơn hàng</span>
                        </div>
                        <i class="fas fa-chevron-right"></i>
                    </a>

                    <a href="#" class="menu-item " onclick="showTab('voucher',this)">
                        <div class="menu-icon">
                            <span class="icon"><i class="fas fa-ticket-alt"></i></span>
                            <span>Ví Voucher</span>
                        </div>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a href="#" class="menu-item" onclick="showTab('diachi',this)">
                        <div class="menu-icon">
                            <span class="icon"><i class="fas fa-map-marker-alt"></i></span>
                            <span>Đổi địa chỉ</span>
                        </div>
                        <i class="fas fa-chevron-right"></i>
                    </a>

                    <a href="Q&A.php" class="menu-item">
                        <div class="menu-icon">
                            <span class="icon"><i class="fas fa-question-circle"></i></span>
                            <span>Chính sách & Câu hỏi thường gặp</span>
                        </div>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a href="../handlers/logout.php" class="menu-item">
                        <div class="menu-icon">
                            <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                            <span>Đăng xuất</span>
                        </div>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>


            <div class="col-lg-8 col-md-7">
                <!-- Profile Content -->
                <div class="profile-content tab-pane active" id="thongtin">
                    <!-- Account Information Section -->
                    <div class="profile-section">
                        <h2 class="fs-3 fw-600 mb-3">Thông tin tài khoản</h2>

                        <div class="info-row">
                            <div class="info-label">Họ và tên</div>
                            <div class="info-value"><?php echo $username; ?></div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Số điện thoại</div>
                            <div class="info-value"><?php echo $number; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Giới tính</div>
                            <div class="info-value"><?php echo $gender; ?></div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                Ngày sinh <small>(ngày/tháng/năm)</small>
                            </div>
                            <div class="info-value"><?php echo $birthday; ?></div>
                        </div>

                        <div class="text-start mt-4">
                            <!-- Button mở modal Thông tin cá nhân -->
                            <button class="update-btn" data-bs-toggle="modal" data-bs-target="#editProfileModal">CẬP
                                NHẬT</button>

                        </div>
                    </div>

                    <!-- Login Information Section -->
                    <div class="profile-section">
                        <h2 fs-3 fw-600 mb-3>Thông tin đăng nhập</h2>

                        <div class="info-row">
                            <div class="info-label">Email</div>
                            <div class="info-value"><?php echo $email; ?></div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Mật khẩu</div>
                            <div class="info-value password-field">
                                •••••••••••••••
                            </div>
                        </div>

                        <div class="text-start mt-4">
                            <!-- Button mở modal Mật khẩu -->
                            <button class="update-btn" data-bs-toggle="modal" data-bs-target="#editPasswordModal">ĐỔI
                                MẬT
                                KHẨU</button>

                        </div>
                    </div>
                </div>
                <!-- Address content ----------------------->
                <div class="profile-content tab-pane" id="diachi">
                    <div class="container">
                        <h3 class="mb-4">Địa chỉ của tôi</h3>
                        <?php if(empty($fullAddress)): ?>
                        <div class="alert alert-info">Bạn chưa có địa chỉ. Vui lòng thêm địa chỉ.</div>
                        <?php else: ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0"><?php echo $username; ?></h5>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#addAddressModal">
                                        <i class="fas fa-edit me-1"></i>Cập nhật
                                    </button>
                                </div>
                                <p class="card-text mb-2"><i class="fas fa-phone me-2"></i><?php echo $number; ?></p>
                                <p class="card-text"><i
                                        class="fas fa-map-marker-alt me-2"></i><?php echo $fullAddress; ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if(empty($fullAddress)): ?>
                        <div class="text-center mt-4">
                            <button class="update-btn" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="fas fa-plus me-2"></i>Thêm địa chỉ
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Order History Section -->
                <div class="profile-content tab-pane" id="lichsu">
                    <h3 class="mb-4">Lịch sử đơn hàng</h3>
                    <?php if (!empty($orders)): ?>
                    <div class="list-group">
                        <?php foreach ($orders as $order): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span>Đơn hàng #<?php echo $order['id']; ?></span>
                                <span><?php echo number_format($order['total_price'], 0, ',', '.'); ?>₫</span>
                            </div>
                            <small class="text-muted">Ngày đặt: <?php echo $order['order_date']; ?></small>
                            <small class="text-muted d-block">Trạng thái:
                                <?php echo ucfirst($order['status']); ?></small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p>Bạn chưa có đơn hàng nào.</p>
                    <?php endif; ?>
                </div>

            </div>

        </div>
        <!-- Modal cập nhật thông tin cá nhân -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="../handlers/user_profile_handlers.php" method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cập nhật thông tin cá nhân</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Số điện thoại</label>
                                <input type="text" name="number" class="form-control" value="<?php echo $number; ?>">
                            </div>
                            <div class="mb-3">
                                <label>Giới tính</label>
                                <select name="gender" class="form-control">
                                    <option value="Nam" <?php if($gender=="Nam") echo "selected"; ?>>Nam</option>
                                    <option value="Nữ" <?php if($gender=="Nữ") echo "selected"; ?>>Nữ</option>
                                    <option value="Khác" <?php if($gender=="Khác") echo "selected"; ?>>Khác</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Ngày sinh</label>
                                <input type="date" name="birthday" class="form-control"
                                    value="<?php echo $birthday; ?>">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="update_info" class="btn btn-dark text-white">Lưu</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Modal đổi mật khẩu -->
        <div class="modal fade" id="editPasswordModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="../handlers/user_profile_handlers.php" method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Đổi mật khẩu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Mật khẩu mới</label>
                                <input type="password" name="newpass" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Xác nhận mật khẩu</label>
                                <input type="password" name="confirmpass" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="change_pass" class="btn btn-dark text-white">Cập nhật</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- modal thêm địa chỉ -->
        <div class="modal fade" id="addAddressModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="../handlers/user_profile_handlers.php" method="POST">
                    <div class="modal-content">
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="text" name="username" class="form-control"
                                            value="<?php echo $username; ?>" readonly>
                                        <input type="hidden" name="email" value="<?php echo $email; ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <!-- <label class="form-label">Số điện thoại</label> -->
                                        <input type="text" name="number" class="form-control"
                                            value="<?php echo $number; ?>"
                                            <?php echo !empty($number) ? 'readonly' : 'required'; ?>>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="text" name="address" class="form-control"
                                            placeholder="Số nhà, tên đường..." required>
                                    </div>

                                    <div class="col-md-6">
                                        <select class="form-select" id="province" name="province" required>
                                            <option value="">Chọn Tỉnh/Thành</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <select class="form-select" id="district" name="district" required>
                                            <option value="">Chọn Quận/Huyện</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <select class="form-select" id="ward" name="ward" required>
                                            <option value="">Chọn Phường/Xã</option>
                                        </select>
                                    </div>
                                    <input type="hidden" id="province_code" />
                                    <input type="hidden" id="district_code" />
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="default"
                                                id="defaultAddress">
                                            <label class="form-check-label" for="defaultAddress">Đặt làm mặc
                                                định</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-secondary me-2"
                                    data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" name="add_address" class="btn btn-primary">Thêm</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- <input type="text" name="username" class="form-control" value="<?php echo $address; ?>" readonly> -->
    </div>
</body>
<script src="../bootstrap/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

</html>
<?php include("footer.html"); ?>