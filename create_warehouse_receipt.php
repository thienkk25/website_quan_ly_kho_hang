<?php include "connection.php"; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idSP = $_POST['idSP'];
    $idNCC = $_POST['idNCC'];
    $soLuong = $_POST['soLuong'];
    $giaNhap = $_POST['giaNhap'];

    $sql = "INSERT INTO NhapKho (idSP, idNCC, soLuong, giaNhap) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("iiid", $idSP, $idNCC, $soLuong, $giaNhap);
        if ($stmt->execute()) {
            echo "Thêm phiếu nhập kho thành công!";
        } else {
            echo "Lỗi: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Lỗi chuẩn bị truy vấn: " . $conn->error;
    }
}
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
            <h1>Tạo phiếu nhập</h1>

            <form action="" method="post">
            <div class="info-section">
                <div class="form-group">
                    <label>ID Sản phẩm</label>
                    <input type="text" placeholder="Nhà cung cấp" name="idSP">
                </div>
                <div class="form-group">
                    <label>ID Nhà cung cấp</label>
                    <input type="text" placeholder="Nhà cung cấp" name="idNCC">
                </div>
                <div class="form-group">
                    <label for="soLuong">Số lượng</label>
                    <input type="number" id="soLuong" name="soLuong" placeholder="Số lượng"></input>
                </div>
                <div class="form-group">
                    <label for="giaNhap">Giá nhập</label>
                    <input type="number" id="giaNhap" name="giaNhap" placeholder="Giá nhập"></input>
                </div>
            </div>

            <div class="buttons">
                <button type="submit">Tạo</button>
            </div>
            </form>
        </main>
    </div>
</body>

</html>

<?php
    
// Đóng kết nối
$conn->close();

?>
