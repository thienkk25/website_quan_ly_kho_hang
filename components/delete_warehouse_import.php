<?php
include "../connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = intval($_POST['id']);

        $sql = "DELETE FROM nhapkho WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $message = "Xoá phiếu nhập kho thành công!";
            } else {
                $message = "Lỗi khi xoá: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Lỗi chuẩn bị truy vấn: " . $conn->error;
        }
    } else {
        $message = "ID không hợp lệ!";
    }
} else {
    $message = "Yêu cầu không hợp lệ!";
}

$conn->close();
header("Location: " . $_SERVER['HTTP_REFERER']);
 exit(200);
?>

<!-- Hiển thị thông báo rồi quay lại trang trước -->
<!-- <script>
    alert("<?= $message ?>");
    window.history.back();
</script> -->
