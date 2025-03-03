<div style="display: flex;
    flex-direction: row;
    align-items: center;
    gap: 10px;
    padding: 10px;">
    <div>
        <?php  
        if (isset($_SESSION['username'])) {
            echo "Xin chào, " . $_SESSION['username'];
        } else {
            echo "Bạn chưa đăng nhập!";
            header('location: login.php');
            exit();
        } 
        ?>        
    </div>

    <div>
        <?php 
        include "role.php";
        
        // Nếu là admin (idVaiTro == 1), hiển thị dropdown chọn kho
        if ($userRole['idVaiTro'] == 1) { 
        ?>
            <form method="GET">
                <select name="idKho" onchange="this.form.submit()"> 
                    <option value="0" selected>Tất cả</option>
                    <?php
                    $sqlKho = "SELECT * FROM kho";
                    $resultKho = mysqli_query($conn, $sqlKho);
                    while ($row = mysqli_fetch_assoc($resultKho)): 
                        $selected = (isset($_GET['idKho']) && $_GET['idKho'] == $row['id']) ? 'selected' : '';
                    ?>
                        <option value="<?= $row['id'] ?>" <?= $selected ?>><?= $row['tenKho']; ?></option>
                    <?php endwhile; ?>
                </select>
            </form>
        <?php 
        } else { 
            // Nếu không phải admin, chỉ hiển thị tên kho của user
            $sqlKho = "SELECT tenKho FROM kho WHERE id='" . mysqli_real_escape_string($conn, $userRole['idKho']) . "'";
            $resultKho = mysqli_query($conn, $sqlKho);
            $rowKho = mysqli_fetch_assoc($resultKho);
            echo "<strong>Kho: </strong>" . ($rowKho['tenKho'] ?? "Không xác định");
        } 
        ?>
    </div>
</div>
