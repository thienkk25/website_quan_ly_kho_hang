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
    <title>Thêm sản phẩm</title>
</head>
<body>
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {  //Tự động gửi form
            $tenSP = $_POST['tenSP'];
            $motaSP = $_POST['motaSP'];
            $giaSP = $_POST['giaSP'];
            if($conn -> query("INSERT INTO sanpham (tenSP,motaSP,giaSP) VALUES (N'$tenSP', N'$motaSP', N'$giaSP')")){
                echo header('Location: product.php');
            }else {
                echo "<script>alert('Thêm sản phẩm thất bại!')</script>";
            }
        }$conn -> close();
    ?>

    <div class="container">
    <h1 style="text-align: center;">Thêm sản phẩm</h1><br>
            <div class="col-6" style="margin: auto; background-color: #e3f6fb; padding-bottom: 1px; border-radius: 10px;">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="tenSP">Tên sản phẩm:</label>
                        <input class="form-control" type="text" name="tenSP" required>
                    </div>
                    <div class="form-group">
                        <label for="motaSP">Mô tả sản phẩm:</label>
                        <input class="form-control" type="text" name="motaSP" required>
                    </div>
                    <div class="form-group">
                        <label for="giaSP">Giá</label>
                        <input class="form-control" type="number" name="giaSP" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" name="add">Thêm</button>
                    </div>
                </form>
            </div>
    </div>
</body>
</html>