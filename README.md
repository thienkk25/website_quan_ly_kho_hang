# Website quản lý kho hàng

Nhóm 5

## File PDF phân tích thiết kế hệ thống quản lý kho tổng quát

[Xem tài liệu PDF](./Phân%20tích%20thiết%20kế%20hệ%20thống%20quản%20lý%20kho%20hàng.pdf)

## Thành phần dự án
### Các chức năng

    - Đăng nhập admin.
    - Quản lý sản phẩm( Thêm, sửa, xoá, tìm kiếm).
    - Quản lý nhập kho (Tạo phiếu nhập, sửa thông tin nhập, xoá: - nhà cung cấp, số lượng, giá nhập, ngày nhập, v.v).
    - Quản lý xuất kho( Thêm phiếu xuất kho: Ghi nhận thông tin sản phẩm bán hoặc chuyển kho, Kiểm soát xuất kho: Đảm bảo số lượng xuất không vượt quá tồn kho.).
    - Quản lý tồn kho ( Hiển thị số lượng hiện có của từng sản phẩm / Xuất báo cáo về tình trạng tồn kho).
    - Nhà cung cấp, Tài khoản, Vai trò.

### Phân công

    - Thiện: Thiết kế hệ thống tổng thể, bao gồm kiến trúc cơ sơ dữ liệu, xây dựng giao diện và chức năng nhập kho, quản lý nhà cung cấp (thêm, sửa, xoá, tìm kiếm), quản lý tài khoản(tạo, sửa, xoá), hỗ trợ, làm powerpoint.
    - Dương: Xây dựng giao diện và chức năng quản lý sản phẩm (thêm, sửa, xoá, tìm kiếm), trình bày powerpoint.
    - Phúc: Xây dựng giao diện và chức năng xuất kho (Tạo phiếu, kiểm tra và khi nhận thông tin sản phẩm).
    - Quý: Xây dựng giao diện quản lý tồn kho và chức năng.
    - Nhất: Xây dựng giao diện đăng nhập và phân quyền.

## Cơ sở dữ liệu
1. Bảng quản lý tài khoản

    ```sql
    CREATE TABLE TaiKhoan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    idVaiTro INT NOT NULL,
    idKho INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idVaiTro) REFERENCES VaiTro(id) ON DELETE CASCADE,
    FOREIGN KEY (idKho) REFERENCES Kho(id) ON DELETE SET NULL
    );
    ```

2. Bảng sản phẩm

    ```sql
    CREATE TABLE SanPham (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenSP VARCHAR(255) NOT NULL,
    motaSP TEXT,
    giaSP DECIMAL(10,2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    ```

3. Bảng hàng tồn kho

    ```sql
    CREATE TABLE HangTonKho (
    idSP INT,
    idKho INT,
    soLuong INT NOT NULL DEFAULT 0,
    PRIMARY KEY (idSP, idKho),
    FOREIGN KEY (idSP) REFERENCES SanPham(id) ON DELETE CASCADE,
    FOREIGN KEY (idKho) REFERENCES Kho(id) ON DELETE CASCADE
    );
    ```

4. Bảng nhà cung cấp

    ```sql
    CREATE TABLE NhaCungCap (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenNCC VARCHAR(255) NOT NULL,
    thongTinLienHe TEXT
    );
    ```

5. Bảng nhập kho

    ```sql
    CREATE TABLE NhapKho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idSP INT,
    idNCC INT,
    idKho INT NOT NULL,
    soLuong INT NOT NULL,
    giaNhap DECIMAL(10,2) NOT NULL,
    ngayNhap DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idSP) REFERENCES SanPham(id) ON DELETE CASCADE,
    FOREIGN KEY (idNCC) REFERENCES NhaCungCap(id) ON DELETE SET NULL,
    FOREIGN KEY (idKho) REFERENCES Kho(id) ON DELETE CASCADE
    );
    ```

6. Bảng xuất kho

    ```sql
    CREATE TABLE XuatKho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idSP INT,
    idKho INT NOT NULL,
    soLuong INT NOT NULL,
    ngayXuat DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idSP) REFERENCES SanPham(id) ON DELETE CASCADE,
    FOREIGN KEY (idKho) REFERENCES Kho(id) ON DELETE CASCADE
    );
    ```

7. Bảng vai trò

    ```sql
    CREATE TABLE VaiTro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenVaiTro VARCHAR(50) UNIQUE NOT NULL
    );
    ```

8. Bảng kho

    ```sql
    CREATE TABLE Kho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenKho VARCHAR(255) NOT NULL,
    diaChi TEXT NOT NULL
    );
    ```

**Chú ý:** *Sản phẩm dành cho học tập*