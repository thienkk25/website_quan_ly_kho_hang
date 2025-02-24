<?php include "connection.php"; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $idSP = $_POST['idSP'];
    $idNCC = $_POST['idNCC'];
    $soLuong = $_POST['soLuong'];
    $giaNhap = $_POST['giaNhap'];

    $sql = "UPDATE nhapkho SET idSP=?, idNCC=?, soLuong=?, giaNhap=? WHERE id=?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("iiidi", $idSP, $idNCC, $soLuong, $giaNhap, $id);
        if ($stmt->execute()) {
            echo "<script>alert('Cập nhật thành công!');</script>";
        } else {
            echo "<script>alert('Lỗi cập nhật!');</script>";
        }
        $stmt->close();
    }
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM nhapkho WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
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
        <main class="main-content">
            <h1>Cập nhật phiếu nhập</h1>

            <form action="" method="post">
                <div class="info-section">
                    <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                    
                    <div class="form-group">
                        <label>ID Sản phẩm</label>
                        <input type="text" placeholder="ID Sản phẩm" name="idSP" value="<?php echo $row['idSP'] ?>">
                    </div>
                    <div class="form-group">
                        <label>ID Nhà cung cấp</label>
                        <input type="text" placeholder="ID Nhà cung cấp" name="idNCC" value="<?php echo $row['idNCC'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="soLuong">Số lượng</label>
                        <input type="number" id="soLuong" name="soLuong" placeholder="Số lượng" value="<?php echo $row['soLuong'] ?>"></input>
                    </div>
                    <div class="form-group">
                        <label for="giaNhap">Giá nhập</label>
                        <input type="number" id="giaNhap" name="giaNhap" placeholder="Giá nhập" value="<?php echo $row['giaNhap'] ?>"></input>
                    </div>
                </div>

                <div class="buttons">
                    <button type="submit">Sửa</button>
                </div>
            </form>

        </main>
    </div>
</body>

</html>
<?php
        } else {
            echo "<script>alert('Không tìm thấy dữ liệu!');</script>";
        }
        $stmt->close();
    }
}
$conn->close();
?>