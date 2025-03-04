<?php
session_start();
include "../connection.php";
include "../role.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $idSP = $_POST['idSP'];
    $idNCC = $_POST['idNCC'];
    $soLuongMoi = $_POST['soLuong'];
    $giaNhap = $_POST['giaNhap'];

    if(isset($_POST['idKho']) && $userRole['idVaiTro'] == 1 && $userRole['idKho'] == null ){
        $userRole['idKho'] = $_POST['idKho'];
    }

    // Lấy số lượng nhập cũ trước khi cập nhật
    $sql_old = "SELECT soLuong, idSP FROM nhapkho WHERE id = ? AND idKho = ?";
    $stmt_old = $conn->prepare($sql_old);
    $stmt_old->bind_param("ii", $id, $userRole['idKho']);
    $stmt_old->execute();
    $result_old = $stmt_old->get_result();
    
    if ($result_old->num_rows == 0) {
        $sqlDel = "DELETE FROM nhapkho WHERE id = ?";
        $stmtDel = $conn->prepare($sqlDel);
        $stmtDel->bind_param("i", $id);
        $stmtDel->execute();  
        $stmtDel->close();
        
        // Thêm phiếu nhập kho vào bảng nhapkho
        $sql = "INSERT INTO nhapkho (idSP, idNCC, idKho, soLuong, giaNhap) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iiiid", $idSP, $idNCC, $userRole['idKho'], $soLuongMoi, $giaNhap);
            if ($stmt->execute()) {
                // Nếu nhập kho thành công, cập nhật bảng hangtonkho
                $sql_nhap = "INSERT INTO hangtonkho (idSP, idKho, soLuong) 
                            VALUES (?, ?, ?) 
                            ON DUPLICATE KEY UPDATE soLuong = soLuong + VALUES(soLuong)";
                $stmtN = $conn->prepare($sql_nhap);
                $stmtN->bind_param("iii", $idSP, $userRole['idKho'], $soLuongMoi);
                $stmtN->execute();
                $stmtN->close();

                echo "<script>alert('Sửa phiếu nhập kho thành công!');</script>";
            } else {
                echo "<script>alert('Lỗi khi nhập kho: ".$stmt->error."');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Lỗi chuẩn bị truy vấn: ".$conn->error."');</script>";
        }
        header("Location: warehouse_import.php");
        exit(200);
    }

    $row_old = $result_old->fetch_assoc();
    $soLuongCu = $row_old['soLuong'];
    $idSPCu = $row_old['idSP'];
    $stmt_old->close();

    // Cập nhật phiếu nhập kho
    $sql_update = "UPDATE NhapKho SET idSP=?, idNCC=?, soLuong=?, giaNhap=?, ngayNhap=NOW() WHERE id = ? AND idKho = ?";
    $stmt_update = $conn->prepare($sql_update);

    if ($stmt_update) {
        $stmt_update->bind_param("iiidii", $idSP, $idNCC, $soLuongMoi, $giaNhap, $id, $userRole['idKho']);
        if ($stmt_update->execute()) {
            // Cập nhật bảng HangTonKho
            if ($idSP == $idSPCu) {
                // Nếu không đổi sản phẩm, chỉ cập nhật số lượng tồn
                $chenhLech = $soLuongMoi - $soLuongCu;
                $sql_ton = "UPDATE hangtonkho SET soLuong = soLuong + ? WHERE idSP = ? AND idKho = ?";
                $stmt_ton = $conn->prepare($sql_ton);
                $stmt_ton->bind_param("iii", $chenhLech, $idSP, $userRole['idKho']);
            } else {
                // Nếu đổi sản phẩm, cập nhật tồn kho của cả 2 sản phẩm
                $sql_ton1 = "UPDATE hangtonkho SET soLuong = soLuong - ? WHERE idSP = ? AND idKho = ?";
                $stmt_ton1 = $conn->prepare($sql_ton1);
                $stmt_ton1->bind_param("iii", $soLuongCu, $idSPCu, $userRole['idKho']);
                $stmt_ton1->execute();
                $stmt_ton1->close();

                $sql_ton2 = "INSERT INTO hangtonkho (idSP, idKho, soLuong) 
                             VALUES (?, ?, ?) 
                             ON DUPLICATE KEY UPDATE soLuong = soLuong + VALUES(soLuong)";
                $stmt_ton2 = $conn->prepare($sql_ton2);
                $stmt_ton2->bind_param("iii", $idSP, $userRole['idKho'], $soLuongMoi);
                $stmt_ton2->execute();
                $stmt_ton2->close();
            }

            // Nếu không đổi sản phẩm, chỉ cần cập nhật số lượng
            if (isset($stmt_ton)) {
                $stmt_ton->execute();
                $stmt_ton->close();
            }

            echo "<script>alert('Cập nhật thành công!');</script>";
            echo "<script>window.location.href='warehouse_import.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi cập nhật phiếu nhập kho!');</script>";
        }
        $stmt_update->close();
    } else {
        echo "<script>alert('Lỗi chuẩn bị truy vấn: ".$conn->error."');</script>";
    }
}
if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['idKho']) && is_numeric($_GET['idKho'])) {
    $id = $_GET['id'];
    $idKho = $_GET['idKho'];

    if($userRole['idVaiTro'] != 1 && $userRole['idKho'] != $idKho){
        echo "Lỗi";
        exit();
    }

    $sql = "SELECT * FROM nhapkho WHERE id = ? AND idKho = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ii", $id, $idKho);
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
    <title>Cập nhật phiếu nhập</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <?php include "../sidebar.php" ?>
        </aside>
        <main class="main-content">
            <h1>Cập nhật phiếu nhập</h1>

            <form action="" method="post">
                <div class="info-section">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <?php
                        if ($userRole['idVaiTro'] == 1) {
                            echo '
                            <div class="form-group">
                                <label>Kho</label>';
                            
                            $sqlKho = "SELECT * FROM kho";
                            $resultKho = $conn->query($sqlKho);
                            
                            if ($resultKho->num_rows > 0) {
                                echo '<select name="idKho">';
                                while ($rowKho = $resultKho->fetch_assoc()) {
                                    $selected = ($rowKho['id'] == $row['idKho']) ? "selected" : "";
                                    echo "<option value='{$rowKho['id']}' {$selected}>{$rowKho['tenKho']}</option>";
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
                                        while ($rowSP = $resultSP->fetch_assoc()) {
                                            $selected = ($rowSP['id'] == $row['idSP']) ? "selected" : "";
                                            echo "<option value='{$rowSP['id']}' {$selected}>{$rowSP['tenSP']}</option>";
                                        }
                                    ?>
                                </select>
                            <?php } else {
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
                                        while ($rowNCC = $resultNCC->fetch_assoc()) {
                                            $selected = ($rowNCC['id'] == $row['idNCC']) ? "selected" : "";
                                            echo "<option value='{$rowNCC['id']}' {$selected}>{$rowNCC['tenNCC']}</option>";
                                        }
                                    ?>
                                </select>
                            <?php } else {
                                echo "Không có dữ liệu";
                            }
                        ?>
                    </div>

                    <div class="form-group">
                        <label for="soLuong">Số lượng</label>
                        <input type="number" id="soLuong" name="soLuong" placeholder="Số lượng" value="<?= $row['soLuong'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="giaNhap">Giá nhập</label>
                        <input type="number" id="giaNhap" name="giaNhap" placeholder="Giá nhập" value="<?= $row['giaNhap'] ?>">
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
