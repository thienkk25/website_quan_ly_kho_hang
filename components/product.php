<?php
session_start();
include '../connection.php';
include "../role.php";
// Xử lý tìm kiếm
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM sanpham WHERE tenSP LIKE '%$search%' OR id LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM sanpham";
}

$result = mysqli_query($conn, $sql);
include "../role.php";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
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
            <h2>Danh sách sản phẩm</h2>
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Nhập mã hoặc tên sản phẩm" value="<?php echo $search; ?>">
                    <button type="submit" style="margin-top: 10px;">Tìm kiếm</button>
                </form>
                <?php if($userRole['idVaiTro'] == 1){?>
                    <a href="create_product.php"  name="add" ><button style="margin-top: 10px;">Thêm sản phẩm</button></a>
                     <?php } ?>
            </header>
            <table>
                <thead>
                    <tr>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Mô tả sản phẩm</th>
                        <th>Giá bán</th>
                        <?php 
                        if ($userRole['idVaiTro'] == 1): ?>
                        <th colspan="2">Chức năng</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <?php while($row = mysqli_fetch_assoc($result)):    ?>
                    <tr>
                        <td><?php echo $row['id'];  ?></td>
                        <td><?php echo $row['tenSP'];  ?></td>
                        <td><?php echo $row['motaSP'];  ?></td>
                        <td><?php echo number_format($row['giaSP'], 2)  ?> VND</td>
                        <?php 
                            
                           if ($userRole['idVaiTro'] == 1): ?>
                           <td>
                            <a href="update_product.php?id=<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-primary" name="add">Sửa</button>
                            </a>
                            </td>
                           <td>
                            <a onclick="return confirm('Bạn có muốn xóa không!');"; href="delete_product.php?delete=<?php echo $row['id']; ?>">
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
