CREATE DATABASE swm;
USE swm;
CREATE TABLE users (
  idusers INT PRIMARY KEY AUTO_INCREMENT,
  userid VARCHAR(100),
  passuser VARCHAR(100)
);
CREATE TABLE supplier (
  idsupplier INT PRIMARY KEY AUTO_INCREMENT,
  nmsupplier VARCHAR(100),
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
  nmbarang VARCHAR(30),
  iduser INT,
  FOREIGN KEY (iduser) REFERENCES users (idusers)
);
CREATE TABLE boning (
  idboning INT PRIMARY KEY AUTO_INCREMENT,
  batchboning VARCHAR(10),
  idsupplier INT,
  tglboning DATE,
  qtysapi INT,
  dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  iduser INT,
  FOREIGN KEY (iduser) REFERENCES users (idusers),
  FOREIGN KEY (idsupplier) REFERENCES supplier (idsupplier)
);
CREATE TABLE labelboning (
  idlabelboning INT PRIMARY KEY AUTO_INCREMENT,
  idboning INT,
  idbarang INT,
  qty DECIMAL(10,2),
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
('WK', 'BCA (BANK CENTRAL ASIA)', 'SANTI WIJAYA L', '7115407007'),
('SPECIAL', 'BNI (BANK NEGARA INDONESIA)', 'SANTI WIJAYA L', '0335163001');
INSERT INTO barang (kdbarang, nmbarang)
VALUES
('0001', 'TOPSIDE'),
('0002', 'OUTSIDE'),
('0003', 'GANDIK'),
('0004', 'KNUCKLE'),
('0005', 'TENDERLOIN'),
('0006', 'STRIPLOIN WHOLE'),
('0007', 'STRIPLOIN'),
('0008', 'CUBEROLL'),
('0009', 'RUMP'),
('0010', 'RUMP FAT ON'),
('0011', 'STRIPLOIN STEAK'),
('0012', 'CHUCK'),
('0013', 'BLADE'),
('0014', 'CHUCK TENDER'),
('0015', 'SHANK'),
('0016', 'BRISKET'),
('0017', 'BRISKET PEDO'),
('0018', 'BRISKET NE'),
('0019', 'SHORTPLATE'),
('0020', 'T-BONE'),
('0021', 'OPERIB'),
('0022', 'FQ 85 CL'),
('0023', 'PORTHER HOUSE'),
('0024', 'TENDERLOIN BUTT'),
('0025', 'SHORTLOIN'),
('0026', 'OPERIB PRINCE'),
('0027', 'TOMAHAWK'),
('0028', 'TOMAHAWK SPC'),
('0029', 'MBT'),
('0030', 'SHORTRIB'),
('0031', 'RIBS'),
('0032', 'SAPRERIB'),
('0033', 'SCAPULAR'),
('0034', 'BRISKETBONE'),
('0035', 'BACKBONE'),
('0036', 'TENDON'),
('0037', 'BONE'),
('0038', 'TAILTOP'),
('0039', 'TAILTIP'),
('0040', 'NECK BONE'),
('0041', 'CONRO'),
('0042', 'BONE SP'),
('0043', 'BACK RIB'),
('0044', 'MARROW BONE'),
('0045', 'CREAST MEAT'),
('0046', 'OXTAIL'),
('0047', 'FAT GINJAL'),
('0048', 'FAT BONING'),
('0049', 'OFFAL'),
('0050', 'KULIT'),
('0051', 'THIN SKIRT');
