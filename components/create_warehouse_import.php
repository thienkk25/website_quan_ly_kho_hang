<?php
session_start();
include "../connection.php";
include "../role.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idSP = $_POST['idSP'];
    $idNCC = $_POST['idNCC'];
    $soLuong = $_POST['soLuong'];
    $giaNhap = $_POST['giaNhap'];

    // Kiểm tra sản phẩm có tồn tại không
    $sql_check = "SELECT id FROM sanpham WHERE id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $idSP);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows == 0) {
        echo "<script>alert('Sản phẩm không tồn tại!');</script>";
        $stmt_check->close();
        exit;
    }
    $stmt_check->close();
    
    if(isset($_POST['idKho']) && $userRole['idVaiTro'] == 1){
        $userRole['idKho'] = $_POST['idKho'];
    }
    // Thêm phiếu nhập kho vào bảng nhapkho
    $sql = "INSERT INTO nhapkho (idSP, idNCC, idKho, soLuong, giaNhap) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("iiiid", $idSP, $idNCC, $userRole['idKho'], $soLuong, $giaNhap);
        if ($stmt->execute()) {
            // Nếu nhập kho thành công, cập nhật bảng hangtonkho
            $sql_nhap = "INSERT INTO hangtonkho (idSP, idKho, soLuong) 
                         VALUES (?, ?, ?) 
                         ON DUPLICATE KEY UPDATE soLuong = soLuong + VALUES(soLuong)";
            $stmtN = $conn->prepare($sql_nhap);
            $stmtN->bind_param("iii", $idSP, $userRole['idKho'], $soLuong);
            $stmtN->execute();
            $stmtN->close();

            echo "<script>alert('Thêm phiếu nhập kho thành công!');</script>";
        } else {
            echo "<script>alert('Lỗi khi nhập kho: ".$stmt->error."');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Lỗi chuẩn bị truy vấn: ".$conn->error."');</script>";
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
                <?php
                    if ($userRole['idVaiTro'] == 1) {
                        echo '
                        <div class="form-group">
                            <label>Kho</label>';
                        
                        $sqlKho = "SELECT * FROM kho";
                        $resultKho = $conn->query($sqlKho);
                        
                        if ($resultKho->num_rows > 0) {
                            echo '<select name="idKho">';
                            while ($row = $resultKho->fetch_assoc()) {
                                echo '<option value="'.$row['id'].'">'.$row['tenKho'].'</option>';
                            }
                            echo '</select>';
                        } else {
                            echo "Không có dữ liệu";
                        }

                        echo '</div>';
                    }
                ?>
                <div class="form-group">
                    <label>Sản phẩm</label>
                    <?php 
                             $sqlSP = "SELECT * FROM sanpham";
                             $resultSP = $conn->query($sqlSP);
                             if ($resultSP->num_rows > 0) { ?>
                                <select name="idSP">
                                    <?php 
                                        while ($row = $resultSP->fetch_assoc()) {
                                            echo "<option value=".$row['id'].">".$row['tenSP']." - Giá bán: ".$row['giaSP']." VND</option>";
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
