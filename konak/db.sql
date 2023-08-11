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
CREATE TABLE customers (
  idcustomer INT PRIMARY KEY AUTO_INCREMENT,
  nama_customer VARCHAR(100) UNIQUE,
  alamat1 VARCHAR(200),
  alamat2 VARCHAR(200),
  alamat3 VARCHAR(200),
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
CREATE TABLE do (
  iddo INT PRIMARY KEY AUTO_INCREMENT,
  donumber VARCHAR(30) UNIQUE,
  deliverydate DATE,
  idcustomer INT,
  alamat VARCHAR(255);
  po VARCHAR (50),
  driver VARCHAR(20),
  plat VARCHAR (12),
  note VARCHAR (255),
  status VARCHAR(20),
  xbox INT,
  xweight DECIMAL(12.2),
  rweight DECIMAL(12.2),
  xweightreceipt DECIMAL(12, 2),
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
  weightreceipt DECIMAL(12, 2),
  notes VARCHAR(255),
  FOREIGN KEY (iddo) REFERENCES do (iddo),
  FOREIGN KEY (idgrade) REFERENCES grade (idgrade),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
CREATE TABLE doreceipt (
  iddoreceipt INT PRIMARY KEY AUTO_INCREMENT,
  iddo INT,
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
  FOREIGN KEY (iddo) REFERENCES do (iddo),
  FOREIGN KEY (idcustomer) REFERENCES customers (idcustomer),
  FOREIGN KEY (idusers) REFERENCES users (idusers)
);
CREATE TABLE doreceiptdetail (
  iddoreceiptdetail INT PRIMARY KEY AUTO_INCREMENT,
  iddoreceipt INT,
  idgrade INT,
  idbarang INT,
  box INT,
  weight DECIMAL(12, 2),
  notes VARCHAR(255),
  FOREIGN KEY (iddoreceipt) REFERENCES doreceipt (iddoreceipt),
  FOREIGN KEY (idgrade) REFERENCES grade (idgrade),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
CREATE TABLE invoice (
  idinvoice INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  noinvoice VARCHAR(30) NOT NULL UNIQUE,
  iddoreceipt INT NOT NULL,
  top INT,
  duedate DATE,
  status VARCHAR (30),
  tgltf DATE,
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
  idusers INT,
  creatime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (iddoreceipt) REFERENCES doreceipt (iddoreceipt),
  FOREIGN KEY (idsegment) REFERENCES segment (idsegment),
  FOREIGN KEY (idcustomer) REFERENCES customers (idcustomer)
  FOREIGN KEY (idusers) REFERENCES users (idusers)
);
CREATE TABLE invoicedetail (
  idinvoicedetail INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  idinvoice INT NOT NULL,
  idgrade INT NOT NULL,
  idbarang INT NOT NULL,
  weight DECIMAL (12,2) NOT NULL,
  price DECIMAL(12,2),
  discount INT,
  discountrp DECIMAL(12,2),
  amount DECIMAL(12,2),
  FOREIGN KEY (idinvoice) REFERENCES invoice (idinvoice),
  FOREIGN KEY (idgrade) REFERENCES grade (idgrade),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
CREATE TABLE trading (
  idtrading INT PRIMARY KEY AUTO_INCREMENT,
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
CREATE TABLE gr (
  idgr INT PRIMARY KEY AUTO_INCREMENT,
  grnumber VARCHAR(30) NOT NULL UNIQUE,
  receivedate DATE,
  idsupplier INT,
  idnumber VARCHAR(30),
  xbox INT,
  xweight DECIMAL(6,2),
  note VARCHAR(255),
  iduser INT,
  creatime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idsupplier) REFERENCES supplier (idsupplier)
);
CREATE TABLE grdetail (
  idgrdetail INT PRIMARY KEY AUTO_INCREMENT,
  idgr INT,
  idgrade INT,
  idbarang INT,
  box INT,
  weight  DECIMAL(6,2),
  notes VARCHAR(100),
  FOREIGN KEY (idgr) REFERENCES gr (idgr),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang),
  FOREIGN KEY (idgrade) REFERENCES GRADE (idgrade)
);
CREATE TABLE adjustment (
  idadjustment INT PRIMARY KEY AUTO_INCREMENT,
  noadjustment VARCHAR (30) UNIQUE,
  tgladjustment DATE,
  eventadjustment VARCHAR(30),
  note VARCHAR (255),
  xweight DECIMAL(6,2)
);
CREATE TABLE adjustmentdetail (
  idadjustmentdetail INT PRIMARY KEY AUTO_INCREMENT,
  idadjustment INT,
  idgrade INT,
  idbarang INT,
  weight DECIMAL(6,2),
  notes VARCHAR(255),
  FOREIGN KEY (idadjustment) REFERENCES adjustment (idadjustment),
  FOREIGN KEY (idgrade) REFERENCES grade (idgrade),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);