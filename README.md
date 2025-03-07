# website_quan_ly_kho_hang
Nhóm 5

## File PDF phân tích thiết kế hệ thống quản lý kho tổng quát

[Xem tài liệu PDF](./Phân%20tích%20thiết%20kế%20hệ%20thống%20quản%20lý%20kho.pdf)

## Cơ sở dữ liệu
1. Bảng quản lý tài khoản

    `CREATE TABLE TaiKhoan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    idVaiTro INT NOT NULL,
    idKho INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idVaiTro) REFERENCES VaiTro(id) ON DELETE CASCADE,
    FOREIGN KEY (idKho) REFERENCES Kho(id) ON DELETE SET NULL
    );`

2. Bảng sản phẩm

    `CREATE TABLE SanPham (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenSP VARCHAR(255) NOT NULL,
    motaSP TEXT,
    giaSP DECIMAL(10,2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );`

3. Bảng hàng tồn kho

    `CREATE TABLE HangTonKho (
    idSP INT,
    idKho INT,
    soLuong INT NOT NULL DEFAULT 0,
    PRIMARY KEY (idSP, idKho),
    FOREIGN KEY (idSP) REFERENCES SanPham(id) ON DELETE CASCADE,
    FOREIGN KEY (idKho) REFERENCES Kho(id) ON DELETE CASCADE
    );`

4. Bảng nhà cung cấp

    `CREATE TABLE NhaCungCap (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenNCC VARCHAR(255) NOT NULL,
    thongTinLienHe TEXT
    );`

5. Bảng nhập kho

    `CREATE TABLE NhapKho (
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
    );`

6. Bảng xuất kho

    `CREATE TABLE XuatKho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idSP INT,
    idKho INT NOT NULL,
    soLuong INT NOT NULL,
    ngayXuat DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idSP) REFERENCES SanPham(id) ON DELETE CASCADE,
    FOREIGN KEY (idKho) REFERENCES Kho(id) ON DELETE CASCADE
    );`

7. Bảng vai trò

    `CREATE TABLE VaiTro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenVaiTro VARCHAR(50) UNIQUE NOT NULL
    );`

8. Bảng kho

    `CREATE TABLE Kho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenKho VARCHAR(255) NOT NULL,
    diaChi TEXT NOT NULL
    );`


