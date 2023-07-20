CREATE DATABASE swm;
USE swm;
CREATE TABLE users (
  idusers INT PRIMARY KEY AUTO_INCREMENT,
  userid VARCHAR(100) UNIQUE,
  passuser VARCHAR(100)
);
CREATE TABLE supplier (
  idsupplier INT PRIMARY KEY AUTO_INCREMENT,
  nmsupplier VARCHAR(100) UNIQUE,
  jenis_usaha VARCHAR(100),
  alamat VARCHAR(200),
  telepon VARCHAR(20),
  npwp VARCHAR(20),
  iduser INT,
  FOREIGN KEY (iduser) REFERENCES users (idusers)
);
INSERT INTO supplier (nmsupplier, jenis_usaha, alamat, telepon)
VALUES
('H. DONI', 'SAPI', 'RPH TAPOS JL. RAYA TAPOS DEPOK CITY 16457', '081225834627');
CREATE TABLE barang (
  idbarang INT PRIMARY KEY AUTO_INCREMENT,
  kdbarang VARCHAR(10),
  nmbarang VARCHAR(30) UNIQUE,
  stockawal DECIMAL(12,2),
  iduser INT,
  FOREIGN KEY (iduser) REFERENCES users (idusers)
);
CREATE TABLE boning (
  idboning INT PRIMARY KEY AUTO_INCREMENT,
  batchboning VARCHAR(10),
  idsupplier INT,
  tglboning DATE,
  qtysapi INT,
  keterangan VARCHAR(255),
  dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  iduser INT,
  FOREIGN KEY (iduser) REFERENCES users (idusers),
  FOREIGN KEY (idsupplier) REFERENCES supplier (idsupplier)
);
CREATE TABLE labelboning (
  idlabelboning INT PRIMARY KEY AUTO_INCREMENT,
  idboning INT,
  idbarang INT,
  qty DECIMAL(12,2),
  pcs CHAR(5),
  packdate DATE,
  exp DATE,
  kdbarcode VARCHAR(20) UNIQUE,
  dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  iduser INT,
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang),
  FOREIGN KEY (iduser) REFERENCES users (idusers),
  FOREIGN KEY (idboning) REFERENCES boning (idboning)
);
CREATE TABLE relabel (
  idrelabel INT PRIMARY KEY AUTO_INCREMENT,
  idbarang INT,
  qty DECIMAL(12,2),
  pcs CHAR(5),
  packdate DATE,
  exp DATE,
  kdbarcode VARCHAR(20) UNIQUE,
  dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  iduser INT,
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang),
  FOREIGN KEY (iduser) REFERENCES users (idusers)
);
CREATE TABLE segment (
  idsegment INT PRIMARY KEY AUTO_INCREMENT,
  nmsegment VARCHAR(50) UNIQUE,
  banksegment VARCHAR(50),
  accname VARCHAR (50),
  accnumber VARCHAR (50)
);
INSERT INTO segment (nmsegment, banksegment, accname, accnumber)
VALUES
('HOREKA', 'BNI (BANK NEGARA INDONESIA)', 'PT. SANTI WIJAYA MEAT', '8585889991'),
('WARGA/KARYAWAN', 'BCA (BANK CENTRAL ASIA)', 'SANTI WIJAYA L', '7115407007'),
('SPECIAL', 'BNI (BANK NEGARA INDONESIA)', 'SANTI WIJAYA L', '0335163001');
CREATE TABLE customers (
  idcustomer INT PRIMARY KEY AUTO_INCREMENT,
  nama_customer VARCHAR(100) UNIQUE,
  alamat VARCHAR(200),
  idsegment INT,
  top INT,
  sales_referensi VARCHAR(50),
  pajak BOOLEAN,
  telepon VARCHAR(20) DEFAULT '-',
  email VARCHAR(100) DEFAULT '-',
  catatan VARCHAR(255) DEFAULT '-',
  tanggal_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idsegment) REFERENCES segment (idsegment)
);
CREATE TABLE grade (
  idgrade INT PRIMARY KEY AUTO_INCREMENT,
  nmgrade CHAR(3) UNIQUE
);
INSERT INTO grade (nmgrade)
VALUES
("J01"),  ("J02"), ("P01"), ("P02");
CREATE TABLE do (
  iddo INT PRIMARY KEY AUTO_INCREMENT,
  donumber VARCHAR(30) UNIQUE,
  deliverydate DATE,
  idcustomer INT,
  po VARCHAR (50),
  driver VARCHAR(20),
  plat VARCHAR (12),
  note VARCHAR (255),
  status VARCHAR(20),
  xbox INT,
  xweight DECIMAL(12.2),
  idusers INT,
  created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idcustomer) REFERENCES customers (idcustomer),
  FOREIGN KEY (idusers) REFERENCES users (idusers)
);
CREATE TABLE dodetail (
  iddodetail INT PRIMARY KEY AUTO_INCREMENT,
  iddo INT,
  idgrade INT,
  idbarang INT,
  box INT,
  weight DECIMAL(12, 2),
  notes VARCHAR(255),
  FOREIGN KEY (iddo) REFERENCES do (iddo),
  FOREIGN KEY (idgrade) REFERENCES grade (idgrade),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
CREATE TABLE invoice (
  idinvoice INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  noinvoice VARCHAR(30) NOT NULL UNIQUE,
  iddo INT NOT NULL,
  top INT,
  duedate DATE,
  idsegment INT NOT NULL,
  invoice_date DATE NOT NULL,
  idcustomer INT NOT NULL,
  pocustomer VARCHAR(50) NOT NULL,
  donumber VARCHAR (30) NOT NULL,
  note VARCHAR(255),
  xweight DECIMAL (12,2) NOT NULL,
  xamount DECIMAL (12,2) NOT NULL,
  xdiscount DECIMAL (12,2),
  tax DECIMAL (12,2),
  charge DECIMAL (12,2),
  downpayment DECIMAL (12,2),
  balance DECIMAL (12,2) NOT NULL,
  FOREIGN KEY (iddo) REFERENCES do (iddo),
  FOREIGN KEY (idsegment) REFERENCES segment (idsegment),
  FOREIGN KEY (idcustomer) REFERENCES customers (idcustomer)
);
CREATE TABLE invoicedetail (
  idinvoicedetail INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  idinvoice INT NOT NULL,
  idgrade INT NOT NULL,
  idbarang INT NOT NULL,
  price DECIMAL(12,2),
  discount INT,
  discountrp DECIMAL(12,2),
  amount DECIMAL(12,2),
  FOREIGN KEY (idinvoice) REFERENCES invoice (idinvoice),
  FOREIGN KEY (idgrade) REFERENCES grade (idgrade),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
  ALTER TABLE customers ADD COLUMN tukarfaktur BOOLEAN;
  ALTER TABLE invoice MODIFY tax DECIMAL(12,2) NOT NULL DEFAULT 0;
  ALTER TABLE invoice MODIFY downpayment DECIMAL(12,2) NOT NULL DEFAULT 0;
  ALTER TABLE invoice ADD COLUMN tukarfaktur BOOLEAN;
  ALTER TABLE invoice ADD COLUMN donumber VARCHAR(50);
  ALTER TABLE invoice ADD COLUMN note VARCHAR(255);