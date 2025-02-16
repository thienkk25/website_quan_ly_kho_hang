<header>
    <h1>Tạo sản phẩm sản phẩm</h1>
    <div class="form-group">
        <button type="submit">Lưu</button>
        <button type="button">Trở về</button>
    </div>
</header>
<h2>Thông tin cơ bản</h2>
<form>
    <div class="form-group">
        <label for="productName">Tên sản phẩm:</label>
        <input type="text" id="productName" placeholder="Nhập tên sản phẩm">
    </div>
    <div class="form-group">
        <label for="quantity">Số lượng:</label>
        <input type="number" id="quantity" value="0">
    </div>
    <div class="form-group">
        <label for="costPrice">Giá vốn:</label>
        <input type="text" id="costPrice" placeholder="Nhập giá vốn">
    </div>
    <div class="form-group">
        <label for="salePrice">Giá bán:</label>
        <input type="text" id="salePrice" value="0">
    </div>
    <div class="form-group">
        <label for="category">Danh mục:</label>
        <select id="category">
            <option value="ao">Áo</option>
        </select>
    </div>
    <div class="form-group">
        <label for="productCode">Mã sản phẩm:</label>
        <input type="text" id="productCode" placeholder="Nếu không nhập, hệ thống sẽ tự sinh.">
    </div>
    <div class="form-group">
        <label for="producer">Nhà sản xuất:</label>
        <select id="producer">
            <option value="apple">apple</option>
        </select>
    </div>

</form>