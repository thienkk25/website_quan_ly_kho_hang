<?php
session_start();
session_unset(); // Xóa toàn bộ biến session
session_destroy(); // Hủy session
header("Location: login.php"); // Chuyển hướng về trang đăng nhập
exit();
?>
