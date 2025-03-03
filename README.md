# website_quan_ly_kho_hang
Nhóm 5

## Cơ sở dữ liệu
1. Bảng quản lý tài khoản (admin)

    `CREATE TABLE Admin (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT GETDATE()
    );`

2. Bảng sản phẩm

    `CREATE TABLE SanPham (
    id INT IDENTITY(1,1) PRIMARY KEY,
    tenSP VARCHAR(255) NOT NULL,
    motaSP TEXT,
    giaSP DECIMAL(10,2) NOT NULL,
    created_at DATETIME DEFAULT GETDATE()
    );`

3. Bảng tồn kho

    `CREATE TABLE HangTonKho (
    idSP INT PRIMARY KEY,
    soLuong INT NOT NULL DEFAULT 0,
    FOREIGN KEY (idSP) REFERENCES SanPham(id) ON DELETE CASCADE
    );`

4. Bảng nhà cung cấp

    `CREATE TABLE NhaCungCap (
    id INT IDENTITY(1,1) PRIMARY KEY,
    tenNCC VARCHAR(255) NOT NULL,
    thongTinLienHe TEXT
    );`

5. Bảng nhập kho

    `CREATE TABLE NhapKho (
    id INT IDENTITY(1,1) PRIMARY KEY,
    idSP INT,
    idNCC INT,
    soLuong INT NOT NULL,
    giaNhap DECIMAL(10,2) NOT NULL,
    ngayNhap DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (idSP) REFERENCES SanPham(id) ON DELETE CASCADE,
    FOREIGN KEY (idNCC) REFERENCES NhaCungCap(id) ON DELETE SET NULL
    );`

6. Bảng xuất kho

    `CREATE TABLE XuatKho (
    id INT IDENTITY(1,1) PRIMARY KEY,
    idSP INT,
    soLuong INT NOT NULL,
    ngayXuat DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (idSP) REFERENCES SanPham(id) ON DELETE CASCADE
    );`

-- Bảng Kho (Quản lý nhiều kho hàng)
CREATE TABLE Kho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenKho VARCHAR(255) NOT NULL,
    diaChi TEXT NOT NULL
);
-- Bảng VaiTro (Phân quyền: Admin, Quản lý kho, Nhân viên kho)
CREATE TABLE VaiTro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenVaiTro VARCHAR(50) UNIQUE NOT NULL
);

-- Bảng TaiKhoan (Quản lý tài khoản người dùng)
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

CREATE TABLE SanPham (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenSP VARCHAR(255) NOT NULL,
    motaSP TEXT,
    giaSP DECIMAL(10,2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE HangTonKho (
    idSP INT,
    idKho INT,
    soLuong INT NOT NULL DEFAULT 0,
    PRIMARY KEY (idSP, idKho),
    FOREIGN KEY (idSP) REFERENCES SanPham(id) ON DELETE CASCADE,
    FOREIGN KEY (idKho) REFERENCES Kho(id) ON DELETE CASCADE
);

CREATE TABLE NhaCungCap (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenNCC VARCHAR(255) NOT NULL,
    thongTinLienHe TEXT
);

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

CREATE TABLE XuatKho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idSP INT,
    idKho INT NOT NULL,
    soLuong INT NOT NULL,
    ngayXuat DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idSP) REFERENCES SanPham(id) ON DELETE CASCADE,
    FOREIGN KEY (idKho) REFERENCES Kho(id) ON DELETE CASCADE
);
