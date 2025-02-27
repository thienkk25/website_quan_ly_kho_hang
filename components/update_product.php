<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <title>Cập nhật sản phẩm</title>
</head>
<body>

    <?php require_once '../connection.php' ?>
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $tenSP = $_POST['tenSP'];
            $motaSP = $_POST['motaSP'];
            $giaSP = $_POST['giaSP'];

            $sql = "UPDATE sanpham SET tenSP=?, motaSP=?, giaSP=?, created_at=NOW() WHERE id=?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ssdi", $tenSP, $motaSP, $giaSP, $id);
                if ($stmt->execute()) {
                    echo header('Location: product.php');
                } else {
                    echo "<script>alert('Lỗi cập nhật!');</script>";
                }
                $stmt->close();
            }
        }
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM sanpham WHERE id = ?";
            $stmt = $conn->prepare($sql);
        
            if ($stmt) {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
    ?>

    <div class="container">
    <h1 style="text-align: center;">Cập nhật sản phẩm</h1><br>
            <div class="col-6" style="margin: auto; background-color: #e3f6fb; padding-bottom: 1px; border-radius: 10px;">
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                    <div class="form-group">
                        <label for="tenSP">Tên sản phẩm:</label>
                        <input class="form-control" type="text" name="tenSP" required value="<?php echo $row['tenSP'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="motaSP">Mô tả sản phẩm:</label>
                        <input class="form-control" type="text" name="motaSP" required value="<?php echo $row['motaSP'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="giaSP">Giá</label>
                        <input class="form-control" type="number" name="giaSP" required value="<?php echo $row['giaSP'] ?>">
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