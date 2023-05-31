CREATE DATABASE swm;
USE swm;
CREATE TABLE supplier (
  idsupplier INT PRIMARY KEY AUTO_INCREMENT,
  nmsupplier VARCHAR(100),
  jenis_usaha VARCHAR(100),
  alamat VARCHAR(200),
  telepon VARCHAR(20),
  npwp VARCHAR(20)
);
CREATE TABLE barang (
  idbarang INT PRIMARY KEY AUTO_INCREMENT,
  kdbarang VARCHAR(10),
  nmbarang VARCHAR(30)
);
CREATE TABLE repackimport (
  idrepackimport INT PRIMARY KEY AUTO_INCREMENT,
  batchrepackimport VARCHAR(10),
  tglrepackimport DATE,
  dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE repackimportdetail (
  idrepackimportdetail INT PRIMARY KEY AUTO_INCREMENT,
  idrepackimport INT,
  idbarang INT,
  qty DECIMAL(10, 2),
  FOREIGN KEY (idrepackimport) REFERENCES repackimport (idrepackimport),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
CREATE TABLE repackstock (
  idrepackstock INT PRIMARY KEY AUTO_INCREMENT,
  batchrepackstock VARCHAR(10),
  tglrepackstock DATE,
  dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE repackstockdetail (
  idrepackstockdetail INT PRIMARY KEY AUTO_INCREMENT,
  idrepackstock INT,
  idbarang INT,
  qty DECIMAL(10, 2),
  FOREIGN KEY (idrepackstock) REFERENCES repackstock (idrepackstock),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
CREATE TABLE boning (
  idboning INT PRIMARY KEY AUTO_INCREMENT,
  batchboning VARCHAR(10),
  idsupplier INT,
  tglboning DATE,
  qtysapi INT,
  dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idsupplier) REFERENCES supplier (idsupplier)
);
CREATE TABLE boningdetail (
  idboningdetail INT PRIMARY KEY AUTO_INCREMENT,
  idboning INT,
  idbarang INT,
  qty DECIMAL(10,2),
  FOREIGN KEY (idboning) REFERENCES boning (idboning),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
CREATE TABLE label (
  idlabel INT PRIMARY KEY AUTO_INCREMENT,
  idparent INT,
  idbarang INT,
  qty DECIMAL(10,2),
  pcs CHAR(5),
  kdbarcode VARCHAR(20),
  packdate DATE,
  exp DATE,
  dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang),
  FOREIGN KEY (idparent) REFERENCES boning (idboning),
  FOREIGN KEY (idparent) REFERENCES repackimport (idrepackimport),
  FOREIGN KEY (idparent) REFERENCES repackstock (idrepackstock)
);
