<?php
session_start();
include "connection.php";
include "role.php";
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
        <aside class="sidebar" style="height: 100vh;">
            <?php include "sidebar.php" ?>
        </aside>
        <main class="main-content">
            <?php include "top_header_main.php" ?>
            <?php include "components\overview.php" ?>
        </main>
    </div>
    <script src="script.js"></script>
</body>

</html>