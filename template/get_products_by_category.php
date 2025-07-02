<?php
require_once('../handlers/dbcon.php');

// kt category có tồn tại k ,Lấy giá trị category từ URL, nếu k có gán mặc định là 1 
$category_id = isset($_GET['category']) ? intval($_GET['category']) : 1;
$tag =$_GET['tag'] ;
// Truy vấn sản phẩm theo category
$sql = "
    SELECT p.id, p.name, p.price, p.tag, pi.image_url
    FROM products p
    JOIN subcategories s ON p.subcategory = s.id
    LEFT JOIN product_images pi ON p.id = pi.product_id
    WHERE s.category_id = $category_id AND p.tag = '$tag'
    ORDER BY RAND()
    LIMIT 5
";

$result = mysqli_query($con, $sql);
if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($con));
}
while($row = mysqli_fetch_assoc($result)): ?>
<div class="card shadow-sm border-0">
    <?php if($row['tag']): ?>
    <span class="badge-sale text-uppercase"><?=htmlspecialchars($row['tag'])?>!</span>
    <?php endif; ?>
    <a href="product.php?pId=<?=$row['id']?>">
        <img src="../<?=htmlspecialchars($row['image_url'])?>" class="card-img-top"
            alt="<?=htmlspecialchars($row['name'])?>">
    </a>
    <div class="card-body px-2 py-2">
        <h6 class="card-title text-truncate mb-2" style="font-size:.95rem;"><?=htmlspecialchars($row['name'])?></h6>
        <div class="d-flex justify-content-between align-items-center">
            <span class="fw-bold" style="font-size:.9rem;"><?=number_format($row['price'])?>₫</span>
            <a href="product.php?pId=<?=$row['id']?>" class="btn btn-outline-secondary">
                <i class="bi bi-bag"></i>
            </a>
        </div>
    </div>
</div>
<?php endwhile; ?>

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