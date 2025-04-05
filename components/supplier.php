<?php
session_start();
include '../connection.php';
include "../role.php";
// Xử lý tìm kiếm
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM nhacungcap WHERE tenNCC LIKE '%$search%' OR id LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM nhacungcap";
}

$result = mysqli_query($conn, $sql);
include "../role.php";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách nhà cung cấp</title>
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
            <header>
            <h2>Danh sách nhà cung cấp</h2>
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Nhập mã hoặc tên nhà cung cấp" value="<?php echo $search; ?>">
                    <button type="submit" style="margin-top: 10px;">Tìm kiếm</button>
                </form>
                <?php if($userRole['idVaiTro'] == 1){?>
                    <a href="create_supplier.php"  name="add" ><button style="margin-top: 10px;">Thêm nhà cung cấp</button></a>
                     <?php } ?>
            </header>
            <table>
                <thead>
                    <tr>
                        <th>Mã nhà cung cấp</th>
                        <th>Tên nhà cung cấp</th>
                        <th>Thông tin liên hệ</th>
                        <?php 
                        if ($userRole['idVaiTro'] == 1): ?>
                        <th colspan="2">Chức năng</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <?php while($row = mysqli_fetch_assoc($result)):    ?>
                    <tr>
                        <td><?php echo $row['id'];  ?></td>
                        <td><?php echo $row['tenNCC'];  ?></td>
                        <td><?php echo $row['thongTinLienHe'];  ?></td>
                        <?php 
                            
                           if ($userRole['idVaiTro'] == 1): ?>
                           <td>
                            <a href="update_supplier.php?id=<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-primary" name="add">Sửa</button>
                            </a>
                            </td>
                           <td>
                            <a onclick="return confirm('Bạn có muốn xóa không!');"; href="delete_supplier.php?delete=<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-primary" name="add">Xóa</button></a>
                            </td>
                            
                             <?php endif;
                             ?>
                        
                    </tr>
                <?php endwhile; ?>
            </table>
        </main>
</body>
        
</html>
