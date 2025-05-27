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
    $sql = "SELECT tk.id, tk.username, tk.password, vt.tenVaiTro, k.tenKho, tk.created_at FROM ((taikhoan tk INNER JOIN vaitro vt ON tk.idVaiTro = vt.id) INNER JOIN kho k ON tk.idKho = k.id) WHERE tk.username LIKE '%$search%' OR tk.id LIKE '%$search%'";
} else {
    $sql = "SELECT tk.id, tk.username, tk.password, vt.tenVaiTro, k.tenKho, tk.created_at FROM ((taikhoan tk INNER JOIN vaitro vt ON tk.idVaiTro = vt.id) INNER JOIN kho k ON tk.idKho = k.id)";
}

$result = mysqli_query($conn, $sql);
include "../role.php";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách tài khoản</title>
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
            <h2>Danh sách tài khoản</h2>
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Nhập mã hoặc tên tài khoản" value="<?php echo $search; ?>">
                    <button type="submit" style="margin-top: 10px;">Tìm kiếm</button>
                </form>
                <?php if($userRole['idVaiTro'] == 1){?>
                    <a href="create_account_management.php"  name="add" ><button style="margin-top: 10px;">Thêm tài khoản</button></a>
                     <?php } ?>
            </header>
            <table>
                <thead>
                    <tr>
                        <th>Mã tài khoản</th>
                        <th>Tên tài khoản</th>
                        <th>Vai trò</th>
                        <th>Quản lý kho</th>
                        <th>Ngày tạo</th>
                        <th colspan="2">Chức năng</th>
                    </tr>
                </thead>
                <?php while($row = mysqli_fetch_assoc($result)):    ?>
                    <tr>
                        <td><?php echo $row['id'];  ?></td>
                        <td><?php echo $row['username'];  ?></td>
                        <td><?php echo $row['tenVaiTro'];  ?></td>
                        <td><?php echo $row['tenKho'];  ?></td>
                        <td><?php echo $row['created_at'];  ?></td>
                        <?php 
                            
                           if ($userRole['idVaiTro'] == 1){ ?>
                            <td>
                                <form method='GET' action='update_account_management.php'>
                                    <input type='hidden' name='id' value="<?= $row['id'] ?>">
                                    <button type='submit'>Sửa</button>
                                </form>
                            </td>
                            <td>
                                <form id="deleteForm<?= $row['id'] ?>" method='POST' action='delete_account_management.php'>
                                    <input type='hidden' name='id' value="<?= $row['id'] ?>">
                                </form>
                                <button onclick="confirmDelete(<?= $row['id'] ?>)">Xoá</button>
                            </td>
                             <?php }
                             ?>
                        
                    </tr>
                <?php endwhile; ?>
            </table>
        </main>
    <script>
    function confirmDelete(id) {
        if (confirm("Bạn có chắc chắn muốn xoá không?")) {
            document.getElementById(`deleteForm${id}`).submit();
        }
    }
    </script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="./js/app.js"></script>
</body>
        
</html>
