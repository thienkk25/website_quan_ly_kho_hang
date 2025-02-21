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
            <h2>Danh sách phiếu nhập hàng</h2>
            <div class="search-bar">
                <input type="text" placeholder="Nhập mã phiếu nhập để tìm kiếm">
                <select>
                    <option>Phiếu nhập</option>
                    <option>Phiếu nhập</option>
                    <option>Phiếu nhập</option>
                </select>
                <input type="date">
                <span>đến</span>
                <input type="date">
                <button>Tìm kiếm</button>
                <a href="http://127.0.0.1/website_quan_ly_kho_hang/create_warehouse_receipt.php"
                    target="_blank"><button>Tạo
                        phiếu nhập</button></a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Mã phiếu nhập</th>
                        <th>Tên sản phẩm</th>
                        <th>Tên nhà cung cấp</th>
                        <th>Số lượng</th>
                        <th>Giá Nhập</th>
                        <th>Ngày Nhập</th>
                        <th>Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM ((nhapkho 
                    INNER JOIN sanpham ON nhapkho.idSP = sanpham.id) 
                    INNER JOIN nhacungcap ON nhacungcap.id = nhapkho.idNCC)";
                    $result = $conn->query($sql);
                
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $soLuong = (int) ($row['soLuong']);
                            $giaNhap = (float) ($row['giaNhap']);
                            $tongTien = $soLuong*$giaNhap;
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['tenSP']}</td>
                                    <td>{$row['tenNCC']}</td>
                                    <td>{$soLuong}</td>
                                    <td>{$giaNhap}</td>
                                    <td>{$row['ngayNhap']}</td>
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
                <p>Tổng số phiếu nhập: <?php echo $result->num_rows; ?></p>
                <p>Tổng tiền: <?php 
                                     echo $conn->query("SELECT SUM(soLuong * giaNhap) AS tongTien FROM nhapkho")->fetch_assoc()['tongTien'];
                                    ?>
                </p>
            </div>
        </main>
    </div>
</body>

</html>