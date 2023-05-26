CREATE DATABASE swm;
USE swm;
CREATE TABLE supplier (
  idsupplier INT PRIMARY KEY AUTO_INCREMENT,
  nmsupplier VARCHAR(100),
  alamat VARCHAR(200),
  telepon VARCHAR(20),
  email VARCHAR(100),
  kontak VARCHAR(10),
  npwp VARCHAR(20),
  jenis_usaha VARCHAR(100),
  catatan TEXT
);
CREATE TABLE gudang (
  idgudang INT PRIMARY KEY AUTO_INCREMENT,
  nmgudang VARCHAR(10)
);
CREATE TABLE barang (
  idbarang INT PRIMARY KEY AUTO_INCREMENT,
  kdbarang VARCHAR(10),
  nmbarang VARCHAR(30),
  barcode VARCHAR(20),
  idgudang INT,
  FOREIGN KEY (idgudang) REFERENCES gudang (idgudang)
);
CREATE TABLE boning (
  idboning INT PRIMARY KEY AUTO_INCREMENT,
  batchboning VARCHAR(10),
  idsupplier INT,
  tglkill DATE,
  tglboning DATE,
  qtysapi INT,
  catatan TEXT,
  dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idsupplier) REFERENCES supplier (idsupplier)
);
CREATE TABLE boningdetail (
  idboningdetail INT PRIMARY KEY AUTO_INCREMENT,
  idboning INT,
  idbarang INT,
  ttlqtybarang DECIMAL(10.2),
  FOREIGN KEY (idboning) REFERENCES boning (idboning),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
CREATE TABLE barcodeboning (
  idbarcodeboning INT PRIMARY KEY AUTO_INCREMENT,
  idboningdetail INT,
  idbarang INT,
  pcs INT,
  kdbarcode VARCHAR(20),
  packdate DATE,
  exp DATE,
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang),
  FOREIGN key (idboningdetail) REFERENCES boningdetail (idboningdetail)
);
