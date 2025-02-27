<?php
    session_start();
    if (isset($_SESSION['username'])) {
        header('location: index.php');
    }
    include 'connection.php';

    // Check if form is submitted and required fields are filled
    if( isset($_POST['login']) && $_POST['username'] != '' && $_POST['password'] != '' ) {
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
            $user = mysqli_fetch_assoc($result);
            // If credentials match, redirect to the main page
            $_SESSION['username'] = $user['username'];
            header('location: index.php');
        } else {
            // If credentials don't match, show error message
            echo "<script>alert('Thông tin tài khoản hoặc mật khẩu không chính xác')</script>";
        }
    }
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .login-btn {
            background: #667eea;
            color: white;
            padding: 10px;
            border: none;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        .login-btn:hover {
            background: #5563c1;
        }
        .forgot-password {
            display: block;
            margin-top: 10px;
            font-size: 14px;
            color: #667eea;
            text-decoration: none;
        }
        .forgot-password:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng Nhập</h2>
        <form action="" method="POST">
            <div class="input-group">
                <label for="username">Tên đăng nhập</label>
                <input class="form-control" type="text" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Mật khẩu</label>
                <input class="form-control" type="password" name="password" required>
            </div>
            <button type="submit" class="login-btn" name="login">Đăng nhập</button>
        </form>
    </div>
</body>
</html>