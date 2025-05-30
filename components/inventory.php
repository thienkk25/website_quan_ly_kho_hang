<?php
 session_start();
 include '../connection.php';
 include '../role.php';


 $idKhoFilter = isset($_GET['idKho']) && is_numeric($_GET['idKho']) ? intval($_GET['idKho']) : null;

 // Lấy dữ liệu tổng hợp từ bảng hangtonkho
 $sql_summary = "
     SELECT 
         SUM(htk.soLuong) AS tongSLTon, 
         SUM(htk.soLuong * (SELECT AVG(nk.giaNhap) FROM nhapkho nk WHERE nk.idSP = htk.idSP AND nk.idKho = htk.idKho)) AS tongVonTonKho, 
         SUM(htk.soLuong * sp.giaSP) AS tongGiaTriTonKho
     FROM hangtonkho htk
     LEFT JOIN sanpham sp ON htk.idSP = sp.id
     LEFT JOIN kho k ON htk.idKho = k.id
     WHERE " . ($idKhoFilter !== null ? "htk.idKho = $idKhoFilter" : ($userRole['idKho'] === null ? "1" : "htk.idKho='" . $userRole['idKho'] . "'"));
 
 $result_summary = $conn->query($sql_summary);
 $summary = $result_summary->fetch_assoc();
 
 // Lấy danh sách sản phẩm (có tìm kiếm & lọc theo idKho nếu có)
 $search = isset($_GET['search']) ? $_GET['search'] : "";
 $sql_products = "
     SELECT 
         sp.id AS maSP, 
         sp.tenSP, 
         IFNULL(k.tenKho, ' ----- ') AS tenKho,
         SUM(IFNULL(htk.soLuong, 0)) AS soLuongTon,
         SUM(IFNULL(htk.soLuong, 0) * COALESCE(nk.giaNhap, 0)) AS vonTonKho,
         SUM(IFNULL(htk.soLuong, 0) * IFNULL(sp.giaSP, 0)) AS giaTriTonKho
     FROM sanpham sp
     LEFT JOIN hangtonkho htk ON sp.id = htk.idSP
     LEFT JOIN kho k ON htk.idKho = k.id
     LEFT JOIN (
         SELECT idSP, idKho, MAX(giaNhap) AS giaNhap  -- Chỉ lấy 1 giá nhập gần nhất
         FROM nhapkho 
         GROUP BY idSP, idKho
     ) nk ON sp.id = nk.idSP AND nk.idKho = htk.idKho
     WHERE " . ($idKhoFilter !== null ? "htk.idKho = $idKhoFilter" : ($userRole['idKho'] === null ? "1" : "htk.idKho='" . $userRole['idKho'] . "'")) . "
     AND (sp.tenSP LIKE '%$search%' OR sp.id LIKE '%$search%')
     GROUP BY sp.id, sp.tenSP, k.tenKho;
 ";
 


$result_products = $conn->query($sql_products);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="../styles.css">
     <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 200px;
            background-color: #292b2c;
            color: #fff;
            padding: 15px;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .header button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .header button:hover {
            background-color: #0056b3;
        }

        /* Thiết kế phần summary */
        #summary {
            display: flex;
            gap: 20px;
            font-family: Arial, sans-serif;
        }

        #box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px 25px;
            border-radius: 5px;
            text-align: center;
            min-width: 170px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            font-weight: bold;
        }

        #box:nth-child(1) {
            background: #e3f2c1;
            color: #6b8e23;
        }

        #box:nth-child(2) {
            background: #e3f2fd;
            color: #1e88e5;
        }

        #box:nth-child(3) {
            background: #ffe0b2;
            color: #f57c00;
        }

        #box:nth-child(4) {
            background: #ffcdd2;
            color: #d32f2f;
        }

        h3 {
            margin: 5px 0;
            font-size: 22px;
            font-weight: bold;
        }

        p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
        <aside class="sidebar" style="height: 100vh;">
            <?php include "../sidebar.php" ?>
        </aside>
        <main class="main-content">
            <?php include "../top_header_main.php" ?>
            <h2>Danh sách tồn kho</h2>
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Nhập mã sản phẩm hoặc tên sản phẩm để tìm kiếm" value="<?php echo $search; ?>">
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
                <button type="submit" style="margin: 5px 0 15px 0;">Tìm kiếm</button>
            </form>
            <div id="summary">
                <div id="box">
                    <p>Ngày hôm nay</p>
                    <h3>
                        <?php 
                        date_default_timezone_set('Asia/Ho_Chi_Minh');
                        echo date("d-m-Y"); 
                        ?>
                    </h3>
                </div>
                <div id="box">
                    <p>Tổng số lượng tồn kho</p>
                    <h3><?= number_format($summary['tongSLTon']) ?></h3>
                </div>
                <div id="box">
                    <p>Tổng vốn tồn kho</p>
                    <h3><?= number_format($summary['tongVonTonKho'], 2) ?> VND</h3>
                </div>
                <div id="box">
                    <p>Tổng giá trị tồn</p>
                    <h3><?= number_format($summary['tongGiaTriTonKho'], 2) ?> VND</h3>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng tồn</th>
                        <th>Tên kho</th>
                        <th>Vốn tồn kho</th>
                        <th>Giá trị tồn</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result_products) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result_products)): ?>
                            <tr>
                            <td><?= $row['maSP'] ?></td>
                            <td><?= $row['tenSP'] ?></td>
                            <td><?= number_format($row['soLuongTon']) ?></td>
                            <td><?= $row['tenKho'] ?></td>
                            <td><?= number_format($row['vonTonKho'], 2) ?> VND</td>
                            <td><?= number_format($row['giaTriTonKho'], 2) ?> VND</td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">
                                Không có dữ liệu
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="./js/app.js"></script>
</body>
        
</html>
