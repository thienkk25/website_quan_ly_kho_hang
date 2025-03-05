<?php
    include "../connection.php";
    include "../role.php";
    if(isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $conn -> query("DELETE FROM nhacungcap WHERE id=$id");
        header("Location: supplier.php");
    }
?>