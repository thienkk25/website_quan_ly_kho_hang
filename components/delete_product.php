
<?php
include "../connection.php";
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn -> query("DELETE FROM sanpham WHERE id=$id");
    header("Location: product.php");
}
?>