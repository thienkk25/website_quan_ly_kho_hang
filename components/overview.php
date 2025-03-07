<?php
    $sqlSP = "SELECT * FROM sanpham";
    $resultSP = $conn->query($sqlSP);
    $sqlNCC = "SELECT * FROM nhacungcap";
    $resultNCC = $conn->query($sqlNCC);
    $idKhoFilter = isset($_GET['idKho']) && is_numeric($_GET['idKho']) ? intval($_GET['idKho']) : null;
    // Lấy dữ liệu tổng hợp từ bảng hangtonkho
    $sql_summary = "
    SELECT 
        SUM(htk.soLuong) AS tongSLTon, 
        SUM(htk.soLuong * (SELECT AVG(nk.giaNhap) FROM nhapkho nk WHERE nk.idSP = htk.idSP AND nk.idKho = htk.idKho)) AS tongVonTonKho, 
        SUM(htk.soLuong * sp.giaSP) AS tongGiaTriTonKho
    FROM hangtonkho htk
    LEFT JOIN sanpham sp ON htk.idSP = sp.id
    LEFT JOIN kho k ON htk.idKho = k.id
    WHERE " . ($idKhoFilter !== null ? "htk.idKho = $idKhoFilter" : ($userRole['idKho'] === null ? "1" : "htk.idKho='" . $userRole['idKho'] . "'"));

    $result_summary = $conn->query($sql_summary);
    $summary = $result_summary->fetch_assoc();
?>
<header>
    <h1>Hoạt động hôm nay</h1>
</header>
<div class="stats">
    <div class="stat-card green">
        <h2>Tổng vốn tồn kho</h2>
        <p><?= number_format($summary['tongVonTonKho']) ?> VND</p>
    </div>
    <div class="stat-card blue">
        <h2>Tổng giá trị tồn</h2>
        <p><?= number_format($summary['tongGiaTriTonKho']) ?> VND</p>
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