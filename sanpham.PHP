<?php
include 'config.php';

// Xử lý tìm kiếm
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM sanpham WHERE ten_sanpham LIKE '%$search%' OR ma_sanpham LIKE '%$search%'";
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
</head>
<body>
    <h2>Danh sách sản phẩm</h2>
    <form method="GET" action="sanpham.php">
        <input type="text" name="search" placeholder="Nhập mã hoặc tên sản phẩm" value="<?php echo $search; ?>">
        <button type="submit">Tìm kiếm</button>
    </form>

    <table border="1">
        <tr>
            <th>Tên sản phẩm</th>
            <th>Mô tả sản phẩm</th>
            <th>ID</th>
            <th>Giá bán</th>

        </tr>

        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['tenSP']}</td>
                        <td>{$row['motaSP']}</td>
                        <td>{$row['giaSP']}</td>
                        <td>{$row['ID']}</td>
                        <td>
                            <a href='edit.php?id={$row['id']}'>Chỉnh sửa</a> |
                            <a href='delete.php?id={$row['id']}' onclick='return confirm(\"Bạn có chắc muốn xóa không?\")'>Xóa</a>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Không có sản phẩm nào</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
mysqli_close($conn);
?>
