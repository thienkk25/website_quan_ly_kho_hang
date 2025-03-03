<?php
    if (!isset($_SESSION['username'])) {
        header('location: login.php');
    }
    $sqlRole = "SELECT * FROM taikhoan WHERE username = '" . $_SESSION['username'] . "'";
    $resultRole = mysqli_query($conn, $sqlRole);
    $userRole = mysqli_fetch_assoc($resultRole);        
?>