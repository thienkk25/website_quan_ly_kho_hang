<?php include "connection.php"; ?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tổng quan</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <h2><a style="text-decoration: none;color: white;display: block;" href="http://127.0.0.1/website_quan_ly_kho_hang/">TỔNG QUAN</a></h2>
            <ul>
                <li><a style="text-decoration: none;color: white;display: block;" href="#">Sản phẩm</a></li>
                <li><a style="text-decoration: none;color: white;display: block;" href="http://127.0.0.1/website_quan_ly_kho_hang/warehouse_import.php">Nhập kho</a></li>
                <li><a style="text-decoration: none;color: white;display: block;" href="#">Xuất kho</a></li>
                <li><a style="text-decoration: none;color: white;display: block;" href="#">Tồn kho</a></li>
                <li><a style="text-decoration: none;color: white;display: block;" href="#">Khách hàng</a></li>
                <li><a style="text-decoration: none;color: white;display: block;" href="#">Lợi nhuận</a></li>
                <li><a style="text-decoration: none;color: white;display: block;" href="#">Thiết lập</a></li>
            </ul>
        </aside>
        <main class="main-content">
            <h2>Danh sách phiếu xuất kho</h2>
            <div class="search-bar">
                <input type="text" placeholder="Nhập mã phiếu nhập để tìm kiếm">
                <input type="date">
                <span>đến</span>
                <input type="date">
                <button>Tìm kiếm</button>
                <a href="http://127.0.0.1/website_quan_ly_kho_hang/themphieuxuat.php"
                    target="_blank"><button>Xuất
                        phiếu</button></a>
            </div>
            <table>
                    <thead>
                        <tr>
                        <th>Mã phiếu xuất</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Ngày xuất</th>
                        <th>Tổng tiền</th>
    
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT * FROM xuatkho 
                    INNER JOIN sanpham ON xuatkho.idSP = sanpham.id";
                    $result = $conn->query($sql);
                
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $soLuong = (int) ($row['soLuong']);
                            $giaSP = (float) ($row['giaSP']);
                            $tongTien = $soLuong*$giaSP;
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['tenSP']}</td>
                                    <td>{$row['giaSP']}</td>
                                    <td>{$soLuong}</td>
                                    <td>{$row['ngayXuat']}</td>
                                    <td>{$tongTien}</td>
                                  </tr>";
                                }
                                
                    } else {
                        echo "<tr>
                                    <td colspan='6'>Không có dữ liệu</td>
                                <tr>
                                    ";
                    }
                    ?>
                    </tbody>
                </table>
            <div class="summary">
                <p>Tổng số phiếu xuất: <?php echo $result->num_rows ?? 0; ?></p>
                <p>Tổng tiền: <?php 
                                     echo $conn->query("SELECT SUM(soLuong * giaSP) AS tongTien FROM xuatkho 
                    INNER JOIN sanpham ON xuatkho.idSP = sanpham.id")->fetch_assoc()['tongTien'] ?? 0;
                                    ?>
                </p>
            </div>
        </main>
    </div>
</body>

</html>