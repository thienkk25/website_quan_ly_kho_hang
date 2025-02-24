<?php
include 'config.php';

// Xử lý tìm kiếm
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM sanpham WHERE tenSP LIKE '%$search%' OR id LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM sanpham";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
        <aside class="sidebar">
            <h2>TỔNG QUAN</h2>
            <ul>
                <li>Sản phẩm</li>
                <li>Nhập kho</li>
                <li>Xuất kho</li>
                <li>Tồn kho</li>
                <li>Khách hàng</li>
                <li>Lợi nhuận</li>
                <li>Thiết lập</li>
            </ul>
        </aside>
        <main class="main-content">
            <header>
            <h2>Danh sách sản phẩm</h2>
                <form method="GET" action="sanpham.php">
                    <input type="text" name="search" placeholder="Nhập mã hoặc tên sản phẩm" value="<?php echo $search; ?>">
                    <button type="submit">Tìm kiếm</button>
                    <a href="themsanpham.php"  name="add">Thêm sản phẩm</button></a>
                </form>
            </header>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Mô tả sản phẩm</th>
                        <th>Giá bán</th>
                        <th>Ngày tạo</th>
                    </tr>
                </thead>
        <?php while($row = mysqli_fetch_assoc($result)):    ?>
                <tr>
                    <td><?php echo $row['id'];  ?></td>
                    <td><?php echo $row['tenSP'];  ?></td>
                    <td><?php echo $row['motaSP'];  ?></td>
                    <td><?php echo $row['giaSP'];  ?></td>
                    <td><?php echo $row['created_at'];  ?></td>
                    <td>
                    <a onclick="return confirm('Bạn có muốn xóa không!');"; href="delete.php?delete=<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-primary" name="add">Xóa</button></a>
                    </td>
                    <td>
                    <a href="edit.php?sid=<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-primary" name="add">Sửa</button>
                    </a>
                    </td>
                </tr>
                <?php endwhile; ?>
    </table>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="./js/app.js"></script>
</body>
        
</html>

