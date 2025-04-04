<?php
 session_start();
 include "../connection.php";
 include "../role.php";

$search = isset($_GET['search']) ? $_GET['search'] : "";
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : "";
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : "";

$idKhoFilter = isset($_GET['idKho']) && is_numeric($_GET['idKho']) ? intval($_GET['idKho']) : null;

$sql = "SELECT xuatkho.id, sanpham.tenSP, xuatkho.soLuong, sanpham.giaSP, xuatkho.ngayXuat, kho.tenKho
        FROM ((xuatkho 
        INNER JOIN sanpham ON xuatkho.idSP = sanpham.id)
        INNER JOIN kho ON kho.id = xuatkho.idKho) WHERE " . ($idKhoFilter !== null ? "xuatkho.idKho = $idKhoFilter" : ($userRole['idKho'] === null ? "1" : "xuatkho.idKho='" . $userRole['idKho'] . "'"));

// Thêm điều kiện tìm kiếm nếu có $search
if (!empty($search)) {
    $sql .= " AND (xuatkho.id LIKE '%$search%' OR sanpham.tenSP LIKE '%$search%')";
}
if($date_from > $date_to ){
    echo "<script>alert('Lỗi ngày nhập!'); window.history.back();</script>";
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
            <?php include "../top_header_main.php" ?>
            <h2>Danh sách phiếu xuất kho</h2>
            <div class="search-bar">
            <form method="GET" action="">
                    <input type="text" name="search" placeholder="Nhập mã phiếu xuất hoặc tên sản phẩm để tìm kiếm" value="<?php echo $search; ?>">
                    <span>Từ</span>
                    <input type="date" name="date_from" style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; outline: none; transition: border-color 0.3s; margin: 0 10px;color: blue">
                    <span>đến</span>
                    <input type="date" name="date_to" style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; outline: none; transition: border-color 0.3s; margin-left: 10px;color: blue">

                    <?php
                    if ($userRole['idVaiTro'] == 1):
                    ?>
                        <select name="idKho" style="margin: 5px 0"> 
                            <option value="null" selected>Tất cả</option>
                            <?php
                            $sqlKho = "SELECT * FROM kho";
                            $resultKho = mysqli_query($conn, $sqlKho);
                            while ($row = mysqli_fetch_assoc($resultKho)): 
                                $selected = (isset($_GET['idKho']) && $_GET['idKho'] == $row['id']) ? 'selected' : '';
                            ?>
                                <option value="<?= $row['id'] ?>" <?= $selected ?>><?= $row['tenKho']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    <?php
                    endif;
                    ?>
                    <button type="submit" style="margin-bottom:5px">Tìm kiếm</button>
                </form>
                <a href="create_warehouse_export.php"
                    ><button>Xuất
                        phiếu</button></a>
            </div>
            <table>
                    <thead>
                        <tr>
                        <th>Mã phiếu xuất</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá sản phẩm</th>
                        <th>Tên kho</th>
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
                                    <td>{$row['tenKho']}</td>
                                    <td>{$soLuong}</td>
                                    <td>{$row['ngayXuat']}</td>
                                      <td>".number_format($tongTien, 2)." VND</td>
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
                <p>Tổng số phiếu xuất: <?php echo $result->num_rows ?? 0; ?></p>
                <p>Tổng tiền: 
                    <?php
                        $sql_total = "SELECT SUM(xuatkho.soLuong * sanpham.giaSP) AS tongTienTK 
                                        FROM ((xuatkho 
                                        INNER JOIN sanpham ON xuatkho.idSP = sanpham.id)
                                        INNER JOIN kho ON kho.id = xuatkho.idKho) WHERE " . ($idKhoFilter !== null ? "xuatkho.idKho = $idKhoFilter" : ($userRole['idKho'] === null ? "1" : "xuatkho.idKho='" . $userRole['idKho'] . "'"));

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