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
                    <select>
                        <option>Kho 1</option>
                        <option>Kho 2</option>
                        <option>Kho 3</option>
                    </select>
                </div>
            </div>