<?php
session_start();
include "../connection.php";
include "../role.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idSP = $_POST['idSP'];
    $soLuong = $_POST['soLuong'];

    if(isset($_POST['idKho']) && $userRole['idVaiTro'] == 1){
        $userRole['idKho'] = $_POST['idKho'];
    }

    // Truy vấn số lượng tồn kho từ bảng hangtonkho
    $sql_check = "SELECT soLuong FROM hangtonkho WHERE idSP = ? AND idKho = ?";
    
    $stmt_check = $conn->prepare($sql_check);
    if ($stmt_check) {
        $stmt_check->bind_param("ii", $idSP, $userRole['idKho']);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        $row = $result->fetch_assoc();
        $soLuongTon = $row['soLuong'];
        $stmt_check->close();

        // Kiểm tra số lượng xuất có hợp lệ không
        if ($soLuong > $soLuongTon) {
            echo '<script>alert("Lỗi: Số lượng xuất vượt quá số lượng tồn kho!");</script>';
        } else {
            // Nếu hợp lệ, thêm vào bảng xuatkho
            $sql_xuat = "INSERT INTO xuatkho (idSP, idKho, soLuong) VALUES (?, ?, ?)";
            $stmt_xuat = $conn->prepare($sql_xuat);
            if ($stmt_xuat) {
                $stmt_xuat->bind_param("iii", $idSP, $userRole['idKho'], $soLuong);
                if ($stmt_xuat->execute()) {
                    // Cập nhật lại số lượng tồn kho sau khi xuất
                    $sql_update_ton = "UPDATE hangtonkho SET soLuong = soLuong - ? WHERE idSP = ? AND idKho = ?";
                    $stmt_update_ton = $conn->prepare($sql_update_ton);
                    if ($stmt_update_ton) {
                        $stmt_update_ton->bind_param("iii", $soLuong, $idSP, $userRole['idKho']);
                        $stmt_update_ton->execute();
                        $stmt_update_ton->close();
                    }

                    echo '<script>alert("Xuất phiếu kho thành công!");</script>';
                } else {
                    echo "Lỗi: " . $stmt_xuat->error;
                }
                $stmt_xuat->close();
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