<?php
session_start();
include '../connection.php';
include "../role.php";

if($userRole['idVaiTro'] != 1){
    echo "<script>alert('Bạn không có quyền truy cập!'); window.history.back();</script>";
    exit;
}

// Xử lý tìm kiếm
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM kho WHERE tenKho LIKE '%$search%' OR id LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM kho";
}

$result = mysqli_query($conn, $sql);
include "../role.php";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách kho</title>
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
        <aside class="sidebar" style="height: 100vh;">
            <?php include "../sidebar.php" ?>
        </aside>
        <main class="main-content">
            <header>
            <h2>Danh sách kho</h2>
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Nhập mã hoặc tên kho" value="<?php echo $search; ?>">
                    <button type="submit" style="margin-top: 10px;">Tìm kiếm</button>
                </form>
                <?php if($userRole['idVaiTro'] == 1){?>
                    <a href="create_warehouse.php"  name="add" ><button style="margin-top: 10px;">Thêm kho</button></a>
                     <?php } ?>
            </header>
            <table>
                <thead>
                    <tr>
                        <th>Mã kho</th>
                        <th>Tên kho</th>
                        <th>Địa chỉ</th>
                        <th colspan="2">Chức năng</th>
                    </tr>
                </thead>
                <?php while($row = mysqli_fetch_assoc($result)):    ?>
                    <tr>
                        <td><?php echo $row['id'];  ?></td>
                        <td><?php echo $row['tenKho'];  ?></td>
                        <td><?php echo $row['diaChi'];  ?></td>
                        <?php 
                            
                           if ($userRole['idVaiTro'] == 1): ?>
                           <td>
                                <a href="update_warehouse.php?id=<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-primary" name="add">Sửa</button>
                                </a>
                            </td>
                           <td>
                                <a onclick="return confirm('Bạn có muốn xóa không!');"; href="delete_warehouse.php?delete=<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-primary" name="add">Xóa</button>
                                </a>
                            </td>
                            
                             <?php endif;
                             ?>
                        
                    </tr>
                <?php endwhile; ?>
            </table>
        </main>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="./js/app.js"></script>
</body>
        
</html>
