<?php
    include "../connection.php";
    include "../role.php";
    if(isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $conn -> query("DELETE FROM kho WHERE id=$id");
        header("Location: warehouse.php");
    }
?>