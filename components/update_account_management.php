<?php
session_start();
include "../connection.php";
include "../role.php";

// Xử lý cập nhật tài khoản
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $idVaiTro = $_POST['idVaiTro'];
    
    // Nếu vai trò là Admin (idVaiTro = 1), đặt idKho thành NULL
    $idKho = ($idVaiTro == 1) ? null : (isset($_POST['idKho']) && $_POST['idKho'] !== "" ? $_POST['idKho'] : null);

    // Kiểm tra username không được trống
    if (empty($username)) {
        echo "<script>alert('Tên đăng nhập không được để trống!'); window.history.back();</script>";
        exit;
    }

    // Kiểm tra username có trùng không (trừ user hiện tại)
    $check_sql = "SELECT COUNT(*) FROM taikhoan WHERE username = ? AND id != ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("si", $username, $id);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count > 0) {
        echo "<script>alert('Tên đăng nhập đã tồn tại!'); window.history.back();</script>";
        exit;
    }

    // Nếu nhập mật khẩu mới
    if (!empty($password)) {
        $sql_update = "UPDATE taikhoan SET username = ?, password = ?, idVaiTro = ?, idKho = ? WHERE id = ?";
    } else {
        $sql_update = "UPDATE taikhoan SET username = ?, idVaiTro = ?, idKho = ? WHERE id = ?";
    }

    $stmt = $conn->prepare($sql_update);
    if ($stmt) {
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("ssiii", $username, $hashedPassword, $idVaiTro, $idKho, $id);
        } else {
            $stmt->bind_param("siii", $username, $idVaiTro, $idKho, $id);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Cập nhật tài khoản thành công!'); window.location.href='account_management.php';</script>";
        } else {
            echo "<script>alert('Lỗi: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Lỗi chuẩn bị truy vấn: " . $conn->error . "');</script>";
    }
}

// Lấy dữ liệu tài khoản cần sửa
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    if ($userRole['idVaiTro'] != 1) {
        echo "Lỗi: Bạn không có quyền truy cập!";
        exit;
    }

    $sql = "SELECT * FROM taikhoan WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật tài khoản</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <?php include "../sidebar.php"; ?>
        </aside>
        <main class="main-content">
            <h1>Cập nhật tài khoản</h1>

            <form action="" method="post">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">

                <div class="info-section">
                    <div class="form-group">
                        <label for="username">Tài khoản</label>
                        <input type="text" id="username" name="username" value="<?= $row['username'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu (để trống nếu không đổi)</label>
                        <input style="width: 100%;
                                padding: 10px;
                                border: 1px solid #ccc;
                                border-radius: 5px;" type="password" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label>Vai trò</label>
                        <select name="idVaiTro" id="idVaiTro">
                            <?php
                            $sqlVaiTro = "SELECT * FROM vaitro";
                            $resultVaiTro = $conn->query($sqlVaiTro);

                            if ($resultVaiTro->num_rows > 0) {
                                while ($rowVaiTro = $resultVaiTro->fetch_assoc()) {
                                    $selected = ($rowVaiTro['id'] == $row['idVaiTro']) ? "selected" : "";
                                    echo "<option value='{$rowVaiTro['id']}' {$selected}>{$rowVaiTro['tenVaiTro']}</option>";
                                }
                            } else {
                                echo "<option value=''>Không có dữ liệu</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Quản lý kho</label>
                        <select name="idKho" id="idKho">
                            <option value="">Không quản lý kho</option>
                            <?php
                            $sqlKho = "SELECT * FROM kho";
                            $resultKho = $conn->query($sqlKho);

                            if ($resultKho->num_rows > 0) {
                                while ($rowKho = $resultKho->fetch_assoc()) {
                                    $selected = ($rowKho['id'] == $row['idKho']) ? "selected" : "";
                                    echo "<option value='{$rowKho['id']}' {$selected}>{$rowKho['tenKho']}</option>";
                                }
                            } else {
                                echo "<option value=''>Không có dữ liệu</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="buttons">
                    <button type="submit">Cập nhật</button>
                </div>
            </form>
        </main>
    </div>

    <script>
        document.getElementById("idVaiTro").addEventListener("change", function () {
            let idKhoSelect = document.getElementById("idKho");
            if (this.value == "1") {
                idKhoSelect.value = "";
                idKhoSelect.disabled = true;
            } else {
                idKhoSelect.disabled = false;
            }
        });
    </script>
</body>
</html>

<?php
        } else {
            echo "<script>alert('Không tìm thấy tài khoản!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Lỗi truy vấn!'); window.history.back();</script>";
    }
}

$conn->close();
?>
