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
    $idKhoMoi = isset($_POST['idKho']) ? $_POST['idKho'] : $userRole['idKho'];

    // Lấy thông tin phiếu nhập cũ
    $sql_old = "SELECT soLuong, idSP, idKho FROM nhapkho WHERE id = ?";
    $stmt_old = $conn->prepare($sql_old);
    $stmt_old->bind_param("i", $id);
    $stmt_old->execute();
    $result_old = $stmt_old->get_result();

    if ($result_old->num_rows == 0) {
        echo "<script>alert('Phiếu nhập kho không tồn tại!');</script>";
        exit();
    }

    $row_old = $result_old->fetch_assoc();
    $soLuongCu = $row_old['soLuong'];
    $idSPCu = $row_old['idSP'];
    $idKhoCu = $row_old['idKho'];
    $stmt_old->close();

    // Kiểm tra nếu đổi kho
    $doiKho = ($idKhoMoi != $idKhoCu);

    // Nếu đổi kho, trừ số lượng hàng cũ khỏi kho cũ
    if ($doiKho) {
        $sql_remove_old_kho = "UPDATE hangtonkho SET soLuong = soLuong - ? WHERE idSP = ? AND idKho = ?";
        $stmt_remove = $conn->prepare($sql_remove_old_kho);
        $stmt_remove->bind_param("iii", $soLuongCu, $idSPCu, $idKhoCu);
        $stmt_remove->execute();
        $stmt_remove->close();
    }

    // Cập nhật phiếu nhập kho với kho mới
    $sql_update = "UPDATE nhapkho SET idSP=?, idNCC=?, idKho=?, soLuong=?, giaNhap=?, ngayNhap=NOW() WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);

    if ($stmt_update) {
        $stmt_update->bind_param("iiiiii", $idSP, $idNCC, $idKhoMoi, $soLuongMoi, $giaNhap, $id);
        if ($stmt_update->execute()) {
            // Nếu không đổi kho, chỉ cập nhật số lượng tồn
            if (!$doiKho) {
                $chenhLech = $soLuongMoi - $soLuongCu;
                $sql_ton = "UPDATE hangtonkho SET soLuong = soLuong + ? WHERE idSP = ? AND idKho = ?";
                $stmt_ton = $conn->prepare($sql_ton);
                $stmt_ton->bind_param("iii", $chenhLech, $idSP, $idKhoMoi);
                $stmt_ton->execute();
                $stmt_ton->close();
            } else {
                // Nếu đổi kho, thêm vào kho mới
                $sql_ton_new = "INSERT INTO hangtonkho (idSP, idKho, soLuong) 
                                VALUES (?, ?, ?) 
                                ON DUPLICATE KEY UPDATE soLuong = soLuong + VALUES(soLuong)";
                $stmt_ton_new = $conn->prepare($sql_ton_new);
                $stmt_ton_new->bind_param("iii", $idSP, $idKhoMoi, $soLuongMoi);
                $stmt_ton_new->execute();
                $stmt_ton_new->close();
            }

            echo "<script>alert('Cập nhật thành công!'); window.location.href='warehouse_import.php';</script>";
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

    if ($userRole['idVaiTro'] != 1 && $userRole['idKho'] != $idKho) {
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
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <div class="form-group">
                    <label>Kho</label>
                    <?php
                    if ($userRole['idVaiTro'] == 1) {
                        $sqlKho = "SELECT * FROM kho";
                        $resultKho = $conn->query($sqlKho);

                        echo '<select name="idKho">';
                        while ($rowKho = $resultKho->fetch_assoc()) {
                            $selected = ($rowKho['id'] == $row['idKho']) ? "selected" : "";
                            echo "<option value='{$rowKho['id']}' {$selected}>{$rowKho['tenKho']}</option>";
                        }
                        echo '</select>';
                    } else {
                        echo "<input type='hidden' name='idKho' value='{$row['idKho']}'>";
                    }
                    ?>
                </div>

                <div class="form-group">
                    <label>Sản phẩm</label>
                    <select name="idSP">
                        <?php
                        $sqlSP = "SELECT * FROM sanpham";
                        $resultSP = $conn->query($sqlSP);
                        while ($rowSP = $resultSP->fetch_assoc()) {
                            $selected = ($rowSP['id'] == $row['idSP']) ? "selected" : "";
                            echo "<option value='{$rowSP['id']}' {$selected}>{$rowSP['tenSP']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nhà cung cấp</label>
                    <select name="idNCC">
                        <?php
                        $sqlNCC = "SELECT * FROM nhacungcap";
                        $resultNCC = $conn->query($sqlNCC);
                        while ($rowNCC = $resultNCC->fetch_assoc()) {
                            $selected = ($rowNCC['id'] == $row['idNCC']) ? "selected" : "";
                            echo "<option value='{$rowNCC['id']}' {$selected}>{$rowNCC['tenNCC']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="soLuong">Số lượng</label>
                    <input type="number" id="soLuong" name="soLuong" value="<?= $row['soLuong'] ?>">
                </div>

                <div class="form-group">
                    <label for="giaNhap">Giá nhập</label>
                    <input type="number" id="giaNhap" name="giaNhap" value="<?= $row['giaNhap'] ?>">
                </div>

                <button type="submit">Sửa</button>
            </form>
        </main>
    </div>
</body>

</html>
<?php
        }
    }
}
$conn->close();
?>
