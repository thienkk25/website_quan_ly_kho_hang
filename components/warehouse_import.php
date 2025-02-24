<?php include "../connection.php"; ?>
<?php 
    $search = isset($_GET['search']) ? $_GET['search'] : "";
    $date_from = isset($_GET['date_from']) ? $_GET['date_from'] : "";
    $date_to = isset($_GET['date_to']) ? $_GET['date_to'] : "";
    
    $sql = "SELECT nhapkho.id, sanpham.tenSP, nhacungcap.tenNCC, nhapkho.soLuong, nhapkho.giaNhap, nhapkho.ngayNhap
            FROM ((nhapkho 
            INNER JOIN sanpham ON nhapkho.idSP = sanpham.id) 
            INNER JOIN nhacungcap ON nhacungcap.id = nhapkho.idNCC) 
            WHERE 1";  // `WHERE 1` để dễ dàng nối thêm điều kiện sau
    
    // Thêm điều kiện tìm kiếm nếu có `$search`
    if (!empty($search)) {
        $sql .= " AND (nhapkho.id LIKE '%$search%' OR sanpham.tenSP LIKE '%$search%')";
    }
    
    // Thêm điều kiện lọc theo ngày nhập
    if (!empty($date_from) && !empty($date_to)) {
        // Tìm kiếm trong khoảng ngày
        $sql .= " AND nhapkho.ngayNhap BETWEEN '$date_from' AND '$date_to'";
    } elseif (!empty($date_from)) {
        // Tìm kiếm chính xác theo ngày nhập
        $sql .= " AND DATE(nhapkho.ngayNhap) = '$date_from'";
    } elseif (!empty($date_to)) {
        // Lọc tất cả các bản ghi có ngày nhập <= `$date_to`
        $sql .= " AND DATE(nhapkho.ngayNhap) <= '$date_to'";
    }
    $sql .= " ORDER BY nhapkho.id"; // Sắp xếp theo id tăng dần, mặc định asc
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tổng quan</title>
    <link rel="stylesheet" href="../styles.css">
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
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Nhập mã phiếu nhập hoặc tên sản phẩm để tìm kiếm" value="<?php echo $search; ?>">
                    
                    <select>
                        <option>Phiếu nhập</option>
                    </select>
                    <span>Từ</span>
                    <input type="date" name="date_from">
                    <span>đến</span>
                    <input type="date" name="date_to">
                    <button type="submit">Tìm kiếm</button>
                </form>
                
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
                                    <td>".number_format($giaNhap, 2, ',', '.')." VND</td>
                                    <td>{$row['ngayNhap']}</td>
                                    <td>".number_format($tongTien, 2, ',', '.')." VND</td>
                                    <td>
                                        <form method='GET' action='update_warehouse_receipt.php'>
                                            <input type='hidden' name='id' value='{$row['id']}'>
                                            <button type='submit'>Sửa</button>
                                        </form>
                                        <form id='deleteForm{$row['id']}' method='POST' action='delete_warehouse_receipt.php'>
                                            <input type='hidden' name='id' value='{$row['id']}'>
                                        </form>
                                        <button onclick='confirmDelete({$row['id']})'>Xoá</button>
                                        
                                    </td>
                                  </tr>";
                                }
                                
                    } else {
                        echo "<tr>
                                    <td colspan='7'>Không có dữ liệu</td>
                                <tr>
                                    ";
                    }
                    ?>
                </tbody>
            </table>
            <div class="summary">
                <p>Tổng số phiếu nhập: <?php echo $result->num_rows; ?></p>
                <p>Tổng tiền: 
                    <?php
                        $sql_total = "SELECT SUM(nhapkho.soLuong * nhapkho.giaNhap) AS tongTienTK 
                                    FROM nhapkho 
                                    INNER JOIN sanpham ON nhapkho.idSP = sanpham.id 
                                    INNER JOIN nhacungcap ON nhacungcap.id = nhapkho.idNCC 
                                    WHERE 1";

                        if (!empty($search)) {
                            $sql_total .= " AND (nhapkho.id LIKE '%$search%' OR sanpham.tenSP LIKE '%$search%')";
                        }

                        if (!empty($date_from) && !empty($date_to)) {
                            $sql_total .= " AND nhapkho.ngayNhap BETWEEN '$date_from' AND '$date_to'";
                        } elseif (!empty($date_from)) {
                            $sql_total .= " AND DATE(nhapkho.ngayNhap) = '$date_from'";
                        } elseif (!empty($date_to)) {
                            $sql_total .= " AND DATE(nhapkho.ngayNhap) <= '$date_to'";
                        }

                        $tongTienTK = $conn->query($sql_total)->fetch_assoc()['tongTienTK'] ?? 0;
                        echo number_format($tongTienTK, 2, ',', '.') . " VND";
                    ?>
                </p>
            </div>
        </main>
    </div>
    <script>
    function confirmDelete(id) {
        if (confirm("Bạn có chắc chắn muốn xoá không?")) {
            document.getElementById(`deleteForm${id}`).submit();
        }
    }
    </script>
</body>

</html>

<?php
    
$conn->close();

?>