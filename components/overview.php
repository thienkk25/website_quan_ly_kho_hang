<?php
    include "connection.php";
    $sqlSP = "SELECT * FROM sanpham";
    $resultSP = $conn->query($sqlSP);
    $sqlNCC = "SELECT * FROM nhacungcap";
    $resultNCC = $conn->query($sqlNCC);
    
?>
<header>
    <h1>Hoạt động hôm nay</h1>
</header>
<div class="stats">
    <div class="stat-card green">
        <h2>Tiền bán hàng</h2>
        <p>0</p>
    </div>
    <div class="stat-card blue">
        <h2>Số đơn hàng</h2>
        <p>0</p>
    </div>
    <div class="stat-card red">
        <h2>Số sản phẩm</h2>
        <p><?= $resultSP->num_rows; ?></p>
    </div>
</div>
<div class="info-cards">
    <div class="info-card">
        <h3>Thông tin kho</h3>
        <p>Tồn kho: 8</p>
    </div>
    <div class="info-card">
        <h3>Thông tin sản phẩm</h3>
        <p>Sản phẩm / Nhà cung cấp: <?= $resultSP->num_rows; ?> / <?= $resultNCC->num_rows; ?></p>
    </div>
</div>