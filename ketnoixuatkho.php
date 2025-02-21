<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "quanlykho";

    $conn = new mysqli_connect($servername, $username, $password, $database);
    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    else{
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $ma_phieu = $_POST['ma_phieu'];
            $kho_xuat = $_POST['kho_xuat'];
            $ngay_xuat = $_POST['ngay_xuat'];
            $nguoi_xuat = $_POST['nguoi_xuat'];
            $tong_tien = $_POST['tong_tien'];
        
            $sql = "INSERT INTO phieu_xuat (ma_phieu, kho_xuat, ngay_xuat, nguoi_xuat, tong_tien) 
                    VALUES ('$ma_phieu', '$kho_xuat', '$ngay_xuat', '$nguoi_xuat', '$tong_tien')";
        
            if ($conn->query($sql) === TRUE) {
                echo "Thêm phiếu xuất thành công!";
            } else {
                echo "Lỗi: " . $conn->error;
            }
            $conn->close();
        }
    }
    ?>

    
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
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Quản lý xuất kho</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    .search-bar, .export-form {
                        margin-bottom: 20px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    .summary {
                        margin-top: 20px;
                    }
                    .export-form {
                        display: flex;
                        flex-direction: column;
                        width: 300px;
                    }
                    .export-form input {
                        margin-bottom: 10px;
                        padding: 5px;
                    }
                    .export-form button {
                        padding: 5px 10px;
                        background-color: blue;
                        color: white;
                        border: none;
                        cursor: pointer;
                    }
                </style>
            </head>
            <body>
                <h2>Quản lý xuất kho</h2>
                
                <div class="search-bar">
                    <input type="text" placeholder="Nhập mã phiếu xuất để tìm kiếm">
                    <select>
                        <option>Phiếu xuất</option>
                    </select>
                    <input type="date">
                    <span>to</span>
                    <input type="date">
                    <button>Tìm kiếm</button>
                </div>
                
                <button class="add-btn">Thêm phiếu xuất</button>
                
                <table>
                    <thead>
                        <tr>
                            <th>Mã phiếu xuất</th>
                            <th>Kho xuất</th>
                            <th>Tình trạng</th>
                            <th>Ngày xuất</th>
                            <th>Người xuất</th>
                            <th>Tổng tiền</th>
    
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7">Không có dữ liệu</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="summary">
                    <p>Tổng số phiếu xuất: 0</p>
                    <p>Tổng tiền: 0</p>
                    <p>Tổng nợ: 0</p>
                </div>
                
                <div class="export-form">
                    <h3>Thêm phiếu xuất</h3>
                    <label>Mã phiếu xuất: <input type="text" name="ma_phieu" required></label>
                    <label>Kho xuất: <input type="text" name="kho_xuat" required></label>
                    <label>Ngày xuất: <input type="date" name="ngay_xuat" required></label>
                    <label>Người xuất: <input type="text" name="nguoi_xuat" required></label>
                    <label>Tổng tiền: <input type="number" name="tong_tien" required></label>
                    <button type="submit">Lưu</button>
                </div>
            </body>
            </html>
            
        </main>
    </div>
    <script src="script.js"></script>
</body>

</html>

   

