<?php
 session_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tổng quan</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <?php include "sidebar.php" ?>
        </aside>
        <main class="main-content">
            <div>
                <?php  
                if (isset($_SESSION['username'])) {
                    echo "Xin chào, " . $_SESSION['username'];
                } else {
                    echo "Bạn chưa đăng nhập!";
                    header('location: login.php');
                } 
                ?>        
            </div>
            <?php include "components\overview.php" ?>
        </main>
    </div>
    <script src="script.js"></script>
</body>

</html>