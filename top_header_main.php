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
                    } 
                    ?>        
                </div>
                <div>
                
                    <?php 
                    include "role.php";
                       
                    if ($userRole['idVaiTro'] == 1) {?>
                    <select> 
                        <?php
                        $sqlKho = "SELECT * FROM kho";
                        $resultKho = mysqli_query($conn, $sqlKho); 
                        while ($row = mysqli_fetch_assoc($resultKho)): ?>
                            <option value="<?= $row['id'] ?>"><?= $row['tenKho']; ?></option> 
                        <?php endwhile; 
                        }else ?>
                    </select>
                    <?php
                    {   $sqlKho = "SELECT * FROM kho WHERE id='".$userRole['idKho']."'";
                        $resultKho = mysqli_query($conn, $sqlKho); 
                        echo mysqli_fetch_assoc($resultKho)['tenKho'];
                    } 
                    ?>
                

                </div>
            </div>