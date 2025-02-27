<?php include "../connection.php"; ?>
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
            echo "<script>alert('Thêm phiếu nhập kho thành công!')</script>";
        } else {
            echo "<script>alert('Lỗi không tồn tại sản phẩm trong kho')</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Lỗi chuẩn bị truy vấn: ".$conn->error."')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo phiếu nhập</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <?php include "../sidebar.php" ?>
        </aside>
        <main class="main-content">
            <h1>Tạo phiếu nhập</h1>

            <form action="" method="post">
            <div class="info-section">
                <div class="form-group">
                    <label>Sản phẩm</label>
                    <?php 
                             $sqlSP = "SELECT * FROM sanpham";
                             $resultSP = $conn->query($sqlSP);
                             if ($resultSP->num_rows > 0) { ?>
                                <select name="idSP">
                                    <?php 
                                        while ($row = $resultSP->fetch_assoc()) {
                                            echo "<option value=".$row['id'].">".$row['tenSP']."</option>";
                                        }
                                    ?>
                                </select>
                             <?php
                             }
                             else {
                                echo "Không có dữ liệu";
                             }
                         ?>
                </div>
                <div class="form-group">
                    <label>Nhà cung cấp</label>
                    <?php 
                             $sqlNCC = "SELECT * FROM nhacungcap";
                             $resultNCC = $conn->query($sqlNCC);
                             if ($resultNCC->num_rows > 0) { ?>
                                <select name="idNCC">
                                    <?php 
                                        while ($row = $resultNCC->fetch_assoc()) {
                                            echo "<option value=".$row['id'].">".$row['tenNCC']."</option>";
                                        }
                                    ?>
                                </select>
                             <?php
                             }
                             else {
                                echo "Không có dữ liệu";
                             }
                         ?>
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
    
$conn->close();

?>
