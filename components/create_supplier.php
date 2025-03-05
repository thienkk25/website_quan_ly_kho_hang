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
    <title>Thêm nhà cung cấp</title>
</head>
<body>
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tenNCC = $_POST['tenNCC'];
            $thongTinLienHe = $_POST['thongTinLienHe'];
            if($conn -> query("INSERT INTO nhacungcap (tenNCC, thongTinLienHe) VALUES (N'$tenNCC', N'$thongTinLienHe')")){
                echo header('Location: supplier.php');
            }else {
                echo "<script>alert('Thêm nhà cung cấp thất bại!')</script>";
            }
        }$conn -> close();
    ?>

    <div class="container">
    <h1 style="text-align: center;">Thêm nhà cung cấp</h1><br>
            <div class="col-6" style="margin: auto; background-color: #e3f6fb; padding-bottom: 1px; border-radius: 10px;">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="tenNCC">Tên nhà cung cấp:</label>
                        <input class="form-control" type="text" name="tenNCC" required>
                    </div>
                    <div class="form-group">
                        <label for="thongTinLienHe">Thông tin liên hệ:</label>
                        <input class="form-control" type="text" name="thongTinLienHe" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" name="add">Thêm</button>
                    </div>
                </form>
            </div>
    </div>
</body>
</html>