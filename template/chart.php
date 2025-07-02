<?php

require_once '../handlers/dbcon.php';

// 1. Thống kê doanh thu theo tháng
$revenueQuery = "
    SELECT 
        MONTH(order_date) as month,
        YEAR(order_date) as year,
        SUM(total_price) as total_revenue
    FROM orders
    WHERE status = 'delivered'
    AND YEAR(order_date) = YEAR(CURRENT_DATE)
    GROUP BY MONTH(order_date)
    ORDER BY month
";
$revenueResult = mysqli_query($con, $revenueQuery);
$revenueData = array_fill(1, 12, 0);
while ($row = mysqli_fetch_assoc($revenueResult)) {
    $revenueData[$row['month']] = $row['total_revenue'];
}

// 2. Thống kê số lượng sản phẩm theo danh mục
$categoryQuery = "
    SELECT 
        c.name as category_name,
        COUNT(p.id) as product_count,
        SUM(p.sold) as total_sold
    FROM categories c
    LEFT JOIN subcategories s ON c.id = s.category_id
    LEFT JOIN products p ON s.id = p.subcategory
    GROUP BY c.id, c.name
    ";
$categoryResult = mysqli_query($con, $categoryQuery);

// 3. Thống kê top 5 sản phẩm bán chạy
$topProductsQuery = "
    SELECT 
        name,
        sold,
        price * sold as revenue
    FROM products
    WHERE sold > 0
    ORDER BY sold DESC
    LIMIT 5
";
$topProductsResult = mysqli_query($con, $topProductsQuery);

// 4. Thống kê trạng thái đơn hàng
$orderStatusQuery = "
    SELECT 
        status,
        COUNT(*) as count
    FROM orders
    GROUP BY status
";
$orderStatusResult = mysqli_query($con, $orderStatusQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê - Admin Dashboard</title>
    <link rel="stylesheet" href="../bootstrap/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container-fluid py-4">
        <a href="admin_home.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Trở về trang chủ
        </a>
        <h2 class="mb-4">Thống kê và Phân tích</h2>

        <!-- Row 1: Doanh thu và Danh mục -->
        <div class="row mb-4">
            <!-- Biểu đồ doanh thu -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Top 5 sản phẩm bán chạy</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ phân bố sản phẩm theo danh mục -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Phân bố sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <script>
    // 2. Biểu đồ phân bố sản phẩm theo danh mục
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: [<?php
                mysqli_data_seek($categoryResult, 0);
                $labels = [];
                $data = [];
                while ($row = mysqli_fetch_assoc($categoryResult)) {
                    $labels[] = "'".$row['category_name']."'";
                    $data[] = $row['product_count'];
                }
                echo implode(',', $labels);
            ?>],
            datasets: [{
                data: [<?php echo implode(',', $data); ?>],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // 3. Biểu đồ top 5 sản phẩm bán chạy
    new Chart(document.getElementById('topProductsChart'), {
        type: 'bar',
        data: {
            labels: [<?php
                mysqli_data_seek($topProductsResult, 0);
                $labels = [];
                $soldData = [];
                while ($row = mysqli_fetch_assoc($topProductsResult)) {
                    $labels[] = "'".$row['name']."'";
                    $soldData[] = $row['sold'];
                }
                echo implode(',', $labels);
            ?>],
            datasets: [{
                label: 'Số lượng đã bán',
                data: [<?php echo implode(',', $soldData); ?>],
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgb(75, 192, 192)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>

    <script src="../bootstrap/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>