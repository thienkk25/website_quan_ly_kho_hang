<?php
session_start();
include "../connection.php";
include "../role.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $idVaiTro = $_POST['idVaiTro'];
    $idKho = isset($_POST['idKho']) && $_POST['idKho'] !== "" ? $_POST['idKho'] : null;

    // Kiểm tra username và password không được rỗng
    if (empty($username) || empty($password)) {
        echo "<script>alert('Tên đăng nhập và mật khẩu không được để trống!'); window.history.back();</script>";
        exit;
    }

    // Kiểm tra username đã tồn tại chưa
    $check_sql = "SELECT COUNT(*) FROM taikhoan WHERE username = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count > 0) {
        echo "<script>alert('Tên đăng nhập đã tồn tại!'); window.history.back();</script>";
        exit;
    }

    // Thêm tài khoản vào database
    $sql = "INSERT INTO taikhoan (username, password, idVaiTro, idKho) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ssii", $username, $password, $idVaiTro, $idKho);
        
        if ($stmt->execute()) {
            echo "<script>alert('Thêm tài khoản thành công!');</script>";
        } else {
            echo "<script>alert('Lỗi: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Lỗi chuẩn bị truy vấn: " . $conn->error . "');</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo tài khoản</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <?php include "../sidebar.php" ?>
        </aside>
        <main class="main-content">
            <h1>Tạo tài khoản</h1>

            <form action="" method="post">
            <div class="info-section">
                <div class="form-group">
                    <label for="username">Tài khoản</label>
                    <input type="text" id="username" name="username" placeholder="Tài khoản"></input>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="text" id="password" name="password" placeholder="Mật khẩu"></input>
                </div>
                <div class="form-group">
                    <label>Vai trò</label>
                    <?php
                    $sqlVaiTro = "SELECT * FROM vaitro";
                    $resultVaiTro = $conn->query($sqlVaiTro);

                    if ($resultVaiTro->num_rows > 0) {
                        echo '<select name="idVaiTro" id="idVaiTro" onchange="toggleKho()">';
                        while ($row = $resultVaiTro->fetch_assoc()) {
                            $selected = ($row['tenVaiTro'] == "admin") ? "selected" : ""; // Mặc định chọn admin
                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['tenVaiTro'].'</option>';
                        }
                        echo '</select>';
                    } else {
                        echo "Không có dữ liệu";
                    }
                    ?>
                </div>

                <div class="form-group" id="quanLyKho">
                    <label>Quản lý kho</label>
                    <?php 
                    $sqlKho = "SELECT * FROM kho";
                    $resultKho = $conn->query($sqlKho);
                    if ($resultKho->num_rows > 0) { ?>
                        <select name="idKho" id="idKho">
                            <option value="">-- Chọn kho --</option>
                            <?php 
                            while ($row = $resultKho->fetch_assoc()) {
                                echo "<option value=".$row['id'].">".$row['tenKho']."</option>";
                            }
                            ?>
                        </select>
                    <?php
                    } else {
                        echo "Không có dữ liệu";
                    }
                    ?>
                </div>
            </div>

            <div class="buttons">
                <button type="submit">Tạo</button>
            </div>
            </form>
        </main>
        
    </div>
    <script>
    function toggleKho() {
        let vaiTro = document.getElementById("idVaiTro");
        let quanLyKho = document.getElementById("quanLyKho");
        let idKho = document.getElementById("idKho");

        // Kiểm tra nếu vai trò là "admin" thì ẩn "Quản lý kho" và đặt value về null
        if (vaiTro.options[vaiTro.selectedIndex].text.toLowerCase() === "admin") {
            quanLyKho.style.display = "none";
            idKho.value = ""; // Đặt giá trị về rỗng khi admin
        } else {
            quanLyKho.style.display = "block";
        }
    }

    // Gọi hàm khi trang tải xong để ẩn nếu mặc định là admin
    window.onload = function() {
        toggleKho();
    };
    </script>
</body>

</html>

<?php
    
$conn->close();

?>
