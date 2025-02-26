<?php include "../connection.php"; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idSP = $_POST['idSP'];
    $soLuong = $_POST['soLuong'];

    $sql = "INSERT INTO xuatkho (idSP, soLuong) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $idSP, $soLuong);
        if ($stmt->execute()) {
            echo '<script language="javascript">';
            echo 'alert("Xuất phiếu kho thành công!")';
            echo '</script>';
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
    <title>Tạo phiếu xuất kho</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <?php include "../sidebar.php" ?>
        </aside>
        <main class="main-content">
            <h1>Tạo phiếu xuất kho</h1>

            <form action="" method="post">
            <div class="info-section">
                <div class="form-group">
                    <label>ID Sản phẩm</label>
                    <input type="text" placeholder="ID Sản phẩm" name="idSP">
                </div>
                <div class="form-group">
                    <label for="soLuong">Số lượng</label>
                    <input type="number" id="soLuong" name="soLuong" placeholder="Số lượng"></input>
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