<?php
    require_once 'config.php';

    // Check if form is submitted and required fields are filled
    if( isset($_POST['add']) && $_POST['username'] != '' && $_POST['password'] != '' ) {
        // Retrieve the username and password from the form
        $username = $_POST['username']; 
        $password = $_POST['password'];

        // Sanitize the inputs to prevent SQL injection
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);

        // Query to check if the user exists by username and password
        $sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $sql);

        // Check if credentials match
        if (mysqli_num_rows($result) > 0) {
            // If credentials match, redirect to the main page
            header('location:indexs.php');
        } else {
            // If credentials don't match, show error message
            echo "<script>alert('Thông tin tài khoản hoặc mật khẩu không chính xác')</script>";
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <title>Đăng nhập trang admin</title>
</head>
<body>
    <div class="container">
    <h1 style="text-align: center;">Xin chào Admin</h1><br>
            <div class="col-6" style="margin: auto; background-color: #e3f6fb; padding-bottom: 1px; border-radius: 10px;">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="username">Tên đăng nhập:</label>
                        <input class="form-control" type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input class="form-control" type="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" name="add">Đăng nhập</button>
                    </div>
                </form>
            </div>
    </div>
</body>
</html>