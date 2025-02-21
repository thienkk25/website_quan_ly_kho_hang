<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <h2>TỔNG QUAN</h2>
            <ul>
                <li>Sản phẩm</li>
                <li>Nhập kho</li>
                <li>Xuất kho</li>
                <li>Tồn kho</li>
                <li>Khách hàng</li>
                <li>Lợi nhuận</li>
                <li>Thiết lập</li>
            </ul>
        </aside>
        <main class="main-content">
            <?php include "components\overview.php" ?>
        </main>
    </div>
    <script src="script.js"></script>
</body>

</html>