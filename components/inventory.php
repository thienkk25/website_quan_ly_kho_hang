<?php
include '../connection.php';

// Xử lý tìm kiếm
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM sanpham WHERE tenSP LIKE '%$search%' OR id LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM sanpham";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<div class="container">
        <aside class="sidebar">
            <?php include "../sidebar.php" ?>
        </aside>
        <main class="main-content">
            <div class="header">
                <input type="text" placeholder="Nhập tên hoặc mã sản phẩm để tìm kiếm">
                <div class="date-info">19/05/2022 Ngày lập</div>
                <div class="stock-info">SL tồn kho: 39</div>
                <div class="total-value">Tổng vốn tồn kho: 6,520,000</div>
                <button class="view-button">Xem</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Ma hàng</th>
                        <th>Tên sản phẩm</th>
                        <th>SL</th>
                        <th>Vốn tồn kho</th>
                        <th>Giá trị tồn</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>SP00004</td>
                        <td>Đồng phục nam</td>
                        <td>14</td>
                        <td>3,500,000</td>
                        <td>3,500,000</td>
                    </tr>
                    <tr>
                        <td>SP00001</td>
                        <td>Quần nam</td>
                        <td>13</td>
                        <td>1,820,000</td>
                        <td>1,820,000</td>
                    </tr>
                    <tr>
                        <td>SP00003</td>
                        <td>Bệt ngọt</td>
                        <td>8</td>
                        <td>600,000</td>
                        <td>600,000</td>
                    </tr>
                    <tr>
                        <td>SP00002</td>
                        <td>Áo nữ</td>
                        <td>4</td>
                        <td>600,000</td>
                        <td>600,000</td>
                    </tr>
                </tbody>
            </table>
        </main>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="./js/app.js"></script>
</body>
        
</html>
