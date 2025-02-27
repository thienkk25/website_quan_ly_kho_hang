<?php include "../connection.php"; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idSP = $_POST['idSP'];
    $soLuong = $_POST['soLuong'];

    // Truy vấn số lượng tồn kho hiện tại
    $sql_check = "
        SELECT (COALESCE(SUM(nk.soLuong), 0) - COALESCE(SUM(xk.soLuong), 0)) AS soLuongTon
        FROM SanPham sp
        LEFT JOIN NhapKho nk ON sp.id = nk.idSP
        LEFT JOIN XuatKho xk ON sp.id = xk.idSP
        WHERE sp.id = ?
        GROUP BY sp.id;
    ";
    
    $stmt_check = $conn->prepare($sql_check);
    if ($stmt_check) {
        $stmt_check->bind_param("i", $idSP);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        $row = $result->fetch_assoc();
        $soLuongTon = $row['soLuongTon'] ?? 0; // Nếu không có, mặc định là 0
        $stmt_check->close();

        // Kiểm tra số lượng xuất có hợp lệ không
        if ($soLuong > $soLuongTon) {
            echo '<script>alert("Lỗi: Số lượng xuất vượt quá số lượng tồn kho!");</script>';
        } else {
            // Nếu hợp lệ, thực hiện xuất kho
            $sql = "INSERT INTO XuatKho (idSP, soLuong) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ii", $idSP, $soLuong);
                if ($stmt->execute()) {
                    echo '<script>alert("Xuất phiếu kho thành công!");</script>';
                } else {
                    echo "Lỗi: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Lỗi chuẩn bị truy vấn: " . $conn->error;
            }
        }
    } else {
        echo "Lỗi khi kiểm tra tồn kho: " . $conn->error;
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
                    <label for="soLuong">Số lượng</label>
                    <input type="number" id="soLuong" name="soLuong" placeholder="Số lượng"></input>
                </div>
            </div>

            <div class="buttons">
                <button type="submit">Xuất</button>
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