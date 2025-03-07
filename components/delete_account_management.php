<?php
session_start();
include "../connection.php";
include "../role.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = intval($_POST['id']);

        if ($userRole['idVaiTro'] != 1) {
            echo "<script>alert('Bạn không có quyền xoá!'); window.history.back();</script>";
            exit();
        }

        $sql_delete = "DELETE FROM taikhoan WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id);

        if ($stmt_delete->execute()) {
            echo "<script>alert('Xóa tài khoản thành công!'); window.location.href='account_management.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi xóa tài khoản!'); window.history.back();</script>";
        }
        $stmt_delete->close();

        
    } else {
        echo "<script>alert('Dữ liệu không hợp lệ!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Yêu cầu không hợp lệ!'); window.history.back();</script>";
}

$conn->close();
?>
