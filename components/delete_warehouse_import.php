<?php
session_start();
include "../connection.php";
include "../role.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && is_numeric($_POST['id']) && isset($_POST['idKho']) && is_numeric($_POST['idKho'])) {
        $id = intval($_POST['id']);
        $idKho = intval($_POST['idKho']);

        if ($userRole['idVaiTro'] != 1 && $userRole['idKho'] != $idKho) {
            echo "<script>alert('Bạn không có quyền xóa phiếu nhập này!'); window.history.back();</script>";
            exit();
        }

        // Lấy thông tin phiếu nhập trước khi xóa
        $sql_old = "SELECT idSP, soLuong FROM nhapkho WHERE id = ?";
        $stmt_old = $conn->prepare($sql_old);
        $stmt_old->bind_param("i", $id);
        $stmt_old->execute();
        $result_old = $stmt_old->get_result();

        if ($result_old->num_rows == 0) {
            echo "<script>alert('Phiếu nhập không tồn tại!'); window.history.back();</script>";
            exit();
        }

        $row_old = $result_old->fetch_assoc();
        $idSP = $row_old['idSP'];
        $soLuongXoa = $row_old['soLuong'];
        $stmt_old->close();

        // Cập nhật lại hàng tồn kho (Trừ đi số lượng đã nhập từ phiếu này)
        $sql_update_ton_kho = "UPDATE hangtonkho SET soLuong = soLuong - ? WHERE idSP = ? AND idKho = ?";
        $stmt_update_ton_kho = $conn->prepare($sql_update_ton_kho);
        $stmt_update_ton_kho->bind_param("iii", $soLuongXoa, $idSP, $idKho);
        $stmt_update_ton_kho->execute();
        $stmt_update_ton_kho->close();

        // Xóa phiếu nhập kho
        $sql_delete = "DELETE FROM nhapkho WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id);

        if ($stmt_delete->execute()) {
            echo "<script>alert('Xóa phiếu nhập kho thành công!'); window.location.href='warehouse_import.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi xóa phiếu nhập!'); window.history.back();</script>";
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
