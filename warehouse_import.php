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
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['tenSP']}</td>
                                    <td>{$row['tenNCC']}</td>
                                    <td>{$row['soLuong']}</td>
                                    <td>{$row['giaNhap']}</td>
                                    <td>{$row['ngayNhap']}</td>
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
                <p>Tổng số phiếu nhập: 0</p>
                <p>Tổng tiền: 0</p>
                <p>Tổng nợ: 0</p>
            </div>
        </main>
    </div>
</body>

</html>