<?php
    include "connection.php";
    $sqlSP = "SELECT * FROM sanpham";
    $resultSP = $conn->query($sqlSP);
    $sqlNCC = "SELECT * FROM nhacungcap";
    $resultNCC = $conn->query($sqlNCC);
    // Lấy dữ liệu tổng hợp từ bảng hangtonkho
    $sql_summary = "
    SELECT 
        SUM(htk.soLuong) AS tongSLTon
    FROM hangtonkho htk
    LEFT JOIN sanpham sp ON htk.idSP = sp.id
    LEFT JOIN nhapkho nk ON htk.idSP = nk.idSP
    GROUP BY htk.idSP;
    ";

    $result_summary = $conn->query($sql_summary);
    $summary = $result_summary->fetch_assoc();
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
        <p>Tồn kho: <?= number_format($summary['tongSLTon']) ?></p>
    </div>
    <div class="info-card">
        <h3>Thông tin sản phẩm</h3>
        <p>Sản phẩm / Nhà cung cấp: <?= $resultSP->num_rows; ?> / <?= $resultNCC->num_rows; ?></p>
    </div>
</div>