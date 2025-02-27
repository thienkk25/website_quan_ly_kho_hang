<?php include "../connection.php"; ?>
<?php
$search = isset($_GET['search']) ? $_GET['search'] : "";
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : "";
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : "";

$sql = "SELECT xuatkho.id, sanpham.tenSP, xuatkho.soLuong, sanpham.giaSP, xuatkho.ngayXuat
        FROM xuatkho 
        INNER JOIN sanpham ON xuatkho.idSP = sanpham.id
        WHERE 1";  // WHERE 1 để dễ dàng nối thêm điều kiện sau

// Thêm điều kiện tìm kiếm nếu có $search
if (!empty($search)) {
    $sql .= " AND (xuatkho.id LIKE '%$search%' OR sanpham.tenSP LIKE '%$search%')";
}

// Thêm điều kiện lọc theo ngày xuat
if (!empty($date_from) && !empty($date_to)) {
    // Tìm kiếm trong khoảng ngày
    $sql .= " AND xuatkho.ngayXuat BETWEEN '$date_from' AND '$date_to'";
} elseif (!empty($date_from)) {
    // Tìm kiếm chính xác theo ngày xuat
    $sql .= " AND DATE(xuatkho.ngayXuat) = '$date_from'";
} elseif (!empty($date_to)) {
    // Lọc tất cả các bản ghi có ngày xuat <= $date_to
    $sql .= " AND DATE(xuatkho.ngayXuat) <= '$date_to'";
}
$sql .= " ORDER BY xuatkho.id"; // Sắp xếp theo id tăng dần, mặc định asc 
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ xuất kho</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <?php include "../sidebar.php" ?>
        </aside>
        <main class="main-content">
            <h2>Danh sách phiếu xuất kho</h2>
            <div class="search-bar">
            <form method="GET" action="">
                    <input type="text" name="search" placeholder="Nhập mã phiếu xuất hoặc tên sản phẩm để tìm kiếm" value="<?php echo $search; ?>">
                    
                    <select>
                        <option>Phiếu Xuất</option>
                    </select>
                    <span>Từ</span>
                    <input type="date" name="date_from">
                    <span>đến</span>
                    <input type="date" name="date_to">
                    <button type="submit">Tìm kiếm</button>
                </form>
                <a href="create_warehouse_export.php"
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
                    
                    $result = $conn->query($sql);
                
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $soLuong = (int) ($row['soLuong']);
                            $giaSP = (float) ($row['giaSP']);
                            $tongTien = $soLuong*$giaSP;
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['tenSP']}</td>
                                    <td>".number_format($giaSP, 2)." VND</td>
                                    <td>{$soLuong}</td>
                                    <td>{$row['ngayXuat']}</td>
                                      <td>".number_format($tongTien, 2)." VND</td>
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
                <p>Tổng tiền: 
                    <?php
                        $sql_total = "SELECT SUM(xuatkho.soLuong * sanpham.giaSP) AS tongTienTK 
                                        FROM xuatkho 
                                        INNER JOIN sanpham ON xuatkho.idSP = sanpham.id
                                        WHERE 1";

                        if (!empty($search)) {
                            $sql_total .= " AND (xuatkho.id LIKE '%$search%' OR sanpham.tenSP LIKE '%$search%')";
                        }

                        if (!empty($date_from) && !empty($date_to)) {
                            $sql_total .= " AND xuatkho.ngayXuat BETWEEN '$date_from' AND '$date_to'";
                        } elseif (!empty($date_from)) {
                            $sql_total .= " AND DATE(xuatkho.ngayXuat) = '$date_from'";
                        } elseif (!empty($date_to)) {
                            $sql_total .= " AND DATE(xuatkho.ngayXuat) <= '$date_to'";
                        }

                        $tongTienTK = $conn->query($sql_total)->fetch_assoc()['tongTienTK'] ?? 0;
                        echo number_format($tongTienTK, 2) . " VND";
                    ?>
                </p>
            </div>
        </main>
    </div>
</body>

</html>