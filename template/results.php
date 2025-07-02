<?php include("header.php"); ?>

<head>
    <link rel="stylesheet" href="../static/results.css">
</head>
<!-- main -->
<main class="container search text-center w-75 p-5">
    <h2>Kết quả tìm kiếm</h2>
    <form method="GET" action="results.php">
        <div class="mb-3 search-box">
            <input class="form-control me-2 shadow-none border-0" type="search" name="search"
                placeholder="Tìm kiếm sản phẩm..." aria-label="Search" autocomplete="off"
                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button class="border-0 btn btn-r" type="submit" aria-label="Search">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img"
                    viewBox="0 0 24 24">
                    <title>Tìm kiếm</title>
                    <circle cx="10.5" cy="10.5" r="7.5" />
                    <path d="M21 21l-5.2-5.2" />
                </svg>
            </button>
        </div>
    </form>

    <div class="container search-result w-100">
        <div class="row">
            <!-- Phần bộ lọc bên trái -->
            <div class="col-md-3">
                <div class="card">
                    <form action="results.php" method="GET">
                        <!-- Giữ lại tìm kiếm hiện tại khi lọc -->
                        <?php if(isset($_GET['search'])): ?>
                        <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
                        <?php endif; ?>

                        <div class="card-header p-3">
                            <h5 class="h5-search">Bộ lọc
                                <button type="submit" class="btn btn-primary btn-sm float-end">Áp dụng</button>
                            </h5>
                        </div>

                        <div class="card-body p-3">
                            <h4 class="h4-search">Danh mục</h4>
                            <hr>
                            <?php 
                            $category_query = "SELECT * FROM categories";
                            $category_result = mysqli_query($con, $category_query);

                            if(mysqli_num_rows($category_result) > 0) {
                                $selected_category = isset($_GET['category']) ? intval($_GET['category']) : 0;
                                
                                while($category = mysqli_fetch_assoc($category_result)) {
                                    ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category"
                                    id="category_<?= $category['id'] ?>" value="<?= $category['id'] ?>"
                                    <?= ($selected_category == $category['id']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="category_<?= $category['id'] ?>">
                                    <?= htmlspecialchars($category['name']) ?>
                                </label>
                            </div>
                            <?php
                                }
                            } else {
                                echo "<p>Không tìm thấy danh mục nào</p>";
                            }
                            ?>
                        </div>

                        <div class="card-body p-3">
                            <h4 class="h4-search">Khoảng giá</h4>
                            <hr>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="min_price" placeholder="Giá từ"
                                        min="0"
                                        value="<?= isset($_GET['min_price']) ? intval($_GET['min_price']) : '' ?>">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="max_price" placeholder="Đến" min="0"
                                        value="<?= isset($_GET['max_price']) ? intval($_GET['max_price']) : '' ?>">
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-3">
                            <h4 class="h4-search">Sắp xếp theo</h4>
                            <hr>
                            <?php 
                            $sort_options = [
                                'newest' => 'Mới nhất',
                                'price_asc' => 'Giá: Thấp đến cao',
                                'price_desc' => 'Giá: Cao đến thấp',
                                'name_asc' => 'Tên: A-Z'
                            ];
                            
                            $selected_sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
                            
                            foreach($sort_options as $value => $label) {
                                ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sort" id="sort_<?= $value ?>"
                                    value="<?= $value ?>" <?= ($selected_sort == $value) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sort_<?= $value ?>">
                                    <?= $label ?>
                                </label>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Phần kết quả tìm kiếm bên phải -->
            <div class="col-md-9">
                <?php
                // Lấy tham số tìm kiếm
                $search_term = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
                $category_id = isset($_GET['category']) ? intval($_GET['category']) : 0;
                $min_price = isset($_GET['min_price']) && !empty($_GET['min_price']) ? intval($_GET['min_price']) : 0;
                $max_price = isset($_GET['max_price']) && !empty($_GET['max_price']) ? intval($_GET['max_price']) : 1000000000;
                $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
                $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                $products_per_page = 12;
                $offset = ($page - 1) * $products_per_page;

                // Xây dựng câu truy vấn
                $sql = "
                    SELECT p.id, p.name, p.price, p.tag, pi.image_url
                    FROM products p
                    JOIN subcategories s ON p.subcategory = s.id
                    LEFT JOIN product_images pi ON p.id = pi.product_id
                    WHERE 1=1";

                // Thêm điều kiện tìm kiếm
                if (!empty($search_term)) {
                    $sql .= " AND p.name LIKE '%$search_term%'";
                }

                // Thêm bộ lọc danh mục
                if ($category_id > 0) {
                    $sql .= " AND s.category_id = $category_id";
                }

                // Thêm khoảng giá
                $sql .= " AND p.price BETWEEN $min_price AND $max_price";
                
                // Lấy tổng số sản phẩm kết quả để phân trang
                $count_sql = str_replace("SELECT p.id, p.name, p.price, p.tag, pi.image_url", "SELECT COUNT(DISTINCT p.id) as total", $sql);
                $count_result = mysqli_query($con, $count_sql);
                $total_rows = mysqli_fetch_assoc($count_result)['total'];
                $total_pages = ceil($total_rows / $products_per_page);

                // Thêm phần GROUP BY để đảm bảo không có sản phẩm trùng lặp
                $sql .= " GROUP BY p.id";

                // Thêm phần ORDER BY dựa trên lựa chọn sắp xếp
                switch ($sort) {
                    case 'price_asc':
                        $sql .= " ORDER BY p.price ASC";
                        break;
                    case 'price_desc':
                        $sql .= " ORDER BY p.price DESC";
                        break;
                    case 'name_asc':
                        $sql .= " ORDER BY p.name ASC";
                        break;
                    case 'newest':
                    default:
                        $sql .= " ORDER BY p.id DESC";
                        break;
                }

                // Thêm giới hạn và offset cho phân trang
                $sql .= " LIMIT $offset, $products_per_page";

                // Thực hiện truy vấn
                $result = mysqli_query($con, $sql);
                if (!$result) {
                    die("Lỗi truy vấn: " . mysqli_error($con));
                }

                // Hiển thị số kết quả tìm kiếm
                ?>
                <div class="search-results-header mb-3 text-start">
                    <p>Tìm thấy <?= $total_rows ?> sản
                        phẩm<?= !empty($search_term) ? ' cho "' . htmlspecialchars($search_term) . '"' : '' ?></p>
                </div>

                <?php if(mysqli_num_rows($result) > 0): ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col">
                        <div class="card shadow-sm border-0 h-100">
                            <?php if($row['tag']): ?>
                            <span class="badge-sale text-uppercase"><?=htmlspecialchars($row['tag'])?>!</span>
                            <?php endif; ?>
                            <a href="product.php?pId=<?=$row['id']?>">
                                <img src="../<?=htmlspecialchars($row['image_url'])?>" class="card-img-top"
                                    alt="<?=htmlspecialchars($row['name'])?>">
                            </a>
                            <div class="card-body px-2 py-2">
                                <h6 class="card-title text-truncate mb-2" style="font-size:.95rem;">
                                    <?=htmlspecialchars($row['name'])?></h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold"
                                        style="font-size:.9rem;"><?=number_format($row['price'])?>₫</span>
                                    <a href="product.php?pId=<?=$row['id']?>" class="btn btn-outline-secondary">
                                        <i class="bi bi-bag"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

                <!-- Phân trang -->
                <?php if($total_pages > 1): ?>
                <nav aria-label="Product search pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="?<?= http_build_query(array_merge($_GET, ['page' => $page-1])) ?>"
                                aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                        <?php endfor; ?>

                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="?<?= http_build_query(array_merge($_GET, ['page' => $page+1])) ?>"
                                aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
                <?php else: ?>
                <div class="text-center py-5">
                    <p>Không tìm thấy sản phẩm nào phù hợp với tìm kiếm của bạn.</p>
                    <p>Vui lòng thử lại với từ khóa khác hoặc điều chỉnh bộ lọc.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', e => {
        const pid = e.currentTarget.dataset.productId;
        fetch('../handlers/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `product_id=${pid}&quantity=1`
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) window.location.href = 'cart.php';
                else alert(res.message || 'Lỗi thêm vào giỏ!');
            });
    });
});
</script>

<?php include("footer.html "); ?>