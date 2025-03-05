<?php 
session_start();
include '../connection.php';
include "../role.php";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <title>Cập nhật kho</title>
</head>
<body>
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $tenKho = $_POST['tenKho'];
            $diaChi = $_POST['diaChi'];

            $sql = "UPDATE kho SET tenKho = ?, diaChi = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ssi", $tenKho, $diaChi, $id);
                if ($stmt->execute()) {
                    echo header('Location: warehouse.php');
                } else {
                    echo "<script>alert('Lỗi cập nhật!');</script>";
                }
                $stmt->close();
            }
        }
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM kho WHERE id = ?";
            $stmt = $conn->prepare($sql);
        
            if ($stmt) {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
    ?>

    <div class="container">
    <h1 style="text-align: center;">Cập nhật kho</h1><br>
            <div class="col-6" style="margin: auto; background-color: #e3f6fb; padding-bottom: 1px; border-radius: 10px;">
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                    <div class="form-group">
                        <label for="tenKho">Tên kho:</label>
                        <input class="form-control" type="text" name="tenKho" required value="<?php echo $row['tenKho'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="diaChi">Thông tin liên hệ:</label>
                        <input class="form-control" type="text" name="diaChi" required value="<?php echo $row['diaChi'] ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Sửa</button>
                    </div>
                </form>
            </div>
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