<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "dhdn";

    $conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn) {
        echo ("<script>alert('Kết nối không thành công!')</script>");
    }else {
        echo ('');
    }
mysqli_set_charset($conn, 'UTF8');

?>