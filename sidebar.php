<h2><a style="text-decoration: none;color: white;display: block;" href="http://127.0.0.1/website_quan_ly_kho_hang/">TỔNG QUAN</a></h2>
<ul>
    <li><a style="text-decoration: none;color: white;display: block;" href="http://127.0.0.1/website_quan_ly_kho_hang/components/product.php">Sản phẩm</a></li>
    <li><a style="text-decoration: none;color: white;display: block;" href="http://127.0.0.1/website_quan_ly_kho_hang/components/warehouse_import.php">Nhập kho</a></li>
    <li><a style="text-decoration: none;color: white;display: block;" href="http://127.0.0.1/website_quan_ly_kho_hang/components/warehouse_export.php">Xuất kho</a></li>
    <li><a style="text-decoration: none;color: white;display: block;" href="http://127.0.0.1/website_quan_ly_kho_hang/components/inventory.php">Tồn kho</a></li>
    <li><a style="text-decoration: none;color: white;display: block;" href="http://127.0.0.1/website_quan_ly_kho_hang/components/supplier.php">Nhà cung cấp</a></li>
    <?php if($userRole['idVaiTro'] == 1){ echo '<li><a style="text-decoration: none;color: white;display: block;" href="http://127.0.0.1/website_quan_ly_kho_hang/components/warehouse.php">Quản lý kho</a></li>'; } ?>
    <?php if($userRole['idVaiTro'] == 1){ echo '<li><a style="text-decoration: none;color: white;display: block;" href="http://127.0.0.1/website_quan_ly_kho_hang/components/account_management.php">Quản lý tài khoản</a></li>'; } ?>
    <li><a style="text-decoration: none;color: white;display: block;" href="http://127.0.0.1/website_quan_ly_kho_hang/logout.php">Đăng xuất</a></li>
</ul>