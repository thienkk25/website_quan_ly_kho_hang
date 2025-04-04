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
    <title>Thêm kho</title>
</head>
<body>
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tenKho = $_POST['tenKho'];

            $checkTenKho = $conn -> query("SELECT COUNT(*) FROM kho WHERE tenKho='".$tenKho."'");
            if($checkTenKho->num_rows > 0){
                echo "<script>alert('Đã tồn tại tên kho'); window.history.back();</script>";
                exit;
            }

            $diaChi = $_POST['diaChi'];
            if($conn -> query("INSERT INTO kho (tenKho, diaChi) VALUES (N'$tenKho', N'$diaChi')")){
                echo header('Location: warehouse.php');
            }else {
                echo "<script>alert('Thêm kho thất bại!')</script>";
            }
        }$conn -> close();
    ?>

    <div class="container">
    <h1 style="text-align: center;">Thêm kho</h1><br>
            <div class="col-6" style="margin: auto; background-color: #e3f6fb; padding-bottom: 1px; border-radius: 10px;">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="tenKho">Tên kho:</label>
                        <input class="form-control" type="text" name="tenKho" required>
                    </div>
                    <div class="form-group">
                        <label for="diaChi">Địa chỉ:</label>
                        <input class="form-control" type="text" name="diaChi" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" name="add">Thêm</button>
                    </div>
                </form>
            </div>
    </div>
</body>
</html>