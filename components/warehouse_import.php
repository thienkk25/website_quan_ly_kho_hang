<?php
 session_start();
 include "../connection.php";
 include "../role.php";

    $search = isset($_GET['search']) ? $_GET['search'] : "";
    $date_from = isset($_GET['date_from']) ? $_GET['date_from'] : "";
    $date_to = isset($_GET['date_to']) ? $_GET['date_to'] : "";
    $idKhoFilter = isset($_GET['idKho']) && is_numeric($_GET['idKho']) ? intval($_GET['idKho']) : null;
    $sql = "SELECT nhapkho.id, sanpham.tenSP, nhacungcap.tenNCC, kho.tenKho, nhapkho.soLuong, nhapkho.giaNhap, nhapkho.ngayNhap, kho.id as idKho, sanpham.id as idSP, sanpham.giaSP as giaSP
            FROM (((nhapkho 
            INNER JOIN sanpham ON nhapkho.idSP = sanpham.id) 
            INNER JOIN nhacungcap ON nhacungcap.id = nhapkho.idNCC)
            INNER JOIN kho ON kho.id = nhapkho.idKho) WHERE " . ($idKhoFilter !== null ? "nhapkho.idKho = $idKhoFilter" : ($userRole['idKho'] === null ? "1" : "nhapkho.idKho='" . $userRole['idKho'] . "'"));
    
    // Thêm điều kiện tìm kiếm nếu có `$search`
    if (!empty($search)) {
        $sql .= " AND (nhapkho.id LIKE '%$search%' OR sanpham.tenSP LIKE '%$search%')";
    }
    if($date_from > $date_to ){
        echo "<script>alert('Lỗi ngày nhập!'); window.history.back();</script>";
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
    <title>Trang chủ nhập kho</title>
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
            <h2>Danh sách phiếu nhập kho</h2>
            <div class="search-bar">
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Nhập mã phiếu nhập hoặc tên sản phẩm để tìm kiếm" value="<?php echo $search; ?>">
                    <span>Từ</span>
                    <input type="date" name="date_from">
                    <span>đến</span>
                    <input type="date" name="date_to">
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
                    <button type="submit">Tìm kiếm</button>
                </form>
                
                <a href="create_warehouse_import.php"
                    ><button>Tạo
                        phiếu nhập</button></a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Mã phiếu nhập</th>
                        <th>Tên sản phẩm</th>
                        <th>Tên nhà cung cấp</th>
                        <th>Tên kho</th>
                        <th>Số lượng</th>
                        <th>Giá nhập</th>
                        <th>Giá sản phẩm</th>
                        <th>Ngày nhập</th>
                        <th>Giờ nhập</th>
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
                                    <td>{$row['tenKho']}</td>
                                    <td>{$soLuong}</td>
                                    <td>".number_format($giaNhap, 2)." VND</td>
                                    <td>".number_format($row['giaSP'], 2)." VND</td>
                                    <td>".date("d/m/Y",strtotime($row['ngayNhap']))."</td>
                                    <td>".date("h:i:s",strtotime($row['ngayNhap']))."</td>
                                    <td>".number_format($tongTien, 2)." VND</td>
                                    <td>
                                        <form method='GET' action='update_warehouse_import.php'>
                                            <input type='hidden' name='id' value='{$row['id']}'>
                                            <input type='hidden' name='idKho' value='{$row['idKho']}'>
                                            <button type='submit'>Sửa</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form id='deleteForm{$row['id']}' method='POST' action='delete_warehouse_import.php'>
                                            <input type='hidden' name='id' value='{$row['id']}'>
                                            <input type='hidden' name='idSP' value='{$row['idSP']}'>
                                            <input type='hidden' name='idKho' value='{$row['idKho']}'>
                                        </form>
                                        <button onclick='confirmDelete({$row['id']})'>Xoá</button>
                                    </td>

                                  </tr>";
                                }
                                
                    } else {
                        echo "<tr>
                                    <td colspan='8'>Không có dữ liệu</td>
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
                                    FROM (((nhapkho 
                                    INNER JOIN sanpham ON nhapkho.idSP = sanpham.id) 
                                    INNER JOIN nhacungcap ON nhacungcap.id = nhapkho.idNCC)
                                    INNER JOIN kho ON kho.id = nhapkho.idKho) WHERE ".($userRole['idKho'] == null ? "1" : "idKho='".$userRole['idKho']."'");

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
                        echo number_format($tongTienTK, 2) . " VND";
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