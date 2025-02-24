<?php include "connection.php"; ?>
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
    <title>Tổng quan</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container"> 
 <!-- Danh sách menu để người dùng dễ dàng chuyển trang. -->
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
 <!-- Ô nhập tìm kiếm theo mã phiếu xuất hoặc tên sản phẩm,   Bộ lọc từ ngày - đến ngày.-->
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