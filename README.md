# website_quan_ly_kho_hang
Nhóm 5
<<<<<<< HEAD
j
=======

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
>>>>>>> thien
