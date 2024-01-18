CREATE DATABASE swm;
USE swm;
CREATE TABLE users (
  idusers INT PRIMARY KEY AUTO_INCREMENT,
  userid VARCHAR(30) UNIQUE,
  fullname VARCHAR (30),
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
CREATE TABLE grade (
  idgrade INT PRIMARY KEY AUTO_INCREMENT,
  nmgrade CHAR(3) UNIQUE
);
CREATE TABLE barang (
  idbarang INT PRIMARY KEY AUTO_INCREMENT,
  kdbarang VARCHAR(10),
  nmbarang VARCHAR(30) UNIQUE,
  iduser INT,
  FOREIGN KEY (iduser) REFERENCES users (idusers)
);
-- boning adalah transaksi masuk barang
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
  idgroup INT,
  tanggal_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idgroup) REFERENCES groupcs (idgroup),
  FOREIGN KEY (idsegment) REFERENCES segment (idsegment)
);
-- do adalah transaksi keluar barang
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
  sonumber VARCHAR(30),
  idso INT,
  xbox INT,
  xweight DECIMAL(12.2),
  rweight DECIMAL(12.2),
  xweightreceipt DECIMAL(12, 2),
  idusers INT,
  created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idcustomer) REFERENCES customers (idcustomer),
  FOREIGN KEY (idusers) REFERENCES users (idusers)
  FOREIGN KEY (idso) REFERENCES salesorder (idso)
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
  idrawmate INT NOT NULL
  weight DECIMAL (12,2) NOT NULL,
  price DECIMAL(12,2),
  discount INT,
  discountrp DECIMAL(12,2),
  amount DECIMAL(12,2),
  FOREIGN KEY (idinvoice) REFERENCES invoice (idinvoice),
  FOREIGN KEY (idgrade) REFERENCES grade (idgrade),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang),
  FOREIGN KEY (idrawmate) REFERENCES rawmate (idrawmate)
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
-- gr adalah transaksi masuk barang
CREATE TABLE gr (
  idgr INT PRIMARY KEY AUTO_INCREMENT,
  grnumber VARCHAR(30) NOT NULL UNIQUE,
  receivedate DATE,
  idsupplier INT,
  idnumber VARCHAR(30),
  note VARCHAR(255),
  iduser INT,
  creatime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idsupplier) REFERENCES supplier (idsupplier)
);
CREATE TABLE grdetail (
  idgrdetail INT PRIMARY KEY AUTO_INCREMENT,
  idgr INT,
  kdbarcode VARCHAR(50),
  idgrade INT,
  idbarang INT,
  qty DECIMAL (6,2),
  pcs INT,
  pod DATE,
  creatime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idgr) REFERENCES gr (idgr),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang),
  FOREIGN KEY (idgrade) REFERENCES grade (idgrade)
);
-- adjustment adalah transaksi masuk barang
CREATE TABLE adjustment (
  idadjustment INT PRIMARY KEY AUTO_INCREMENT,
  noadjustment VARCHAR (30) UNIQUE,
  tgladjustment DATE,
  eventadjustment VARCHAR(30),
  xweight DECIMAL(6,2),
  idusers INT,
  FOREIGN KEY (idusers) REFERENCES users (idusers),
  creatime TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
-- inbound adalah transaksi masuk barang
CREATE TABLE inbound (
  idinbound INT PRIMARY KEY AUTO_INCREMENT,
  noinbound VARCHAR(30) UNIQUE,
  tglinbound DATE,
  xweight DECIMAL(12,2),
  xbox INT,
  note VARCHAR(255),
  proses VARCHAR(30),
  idusers INT,
  creatime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idusers) REFERENCES users (idusers)
);
CREATE TABLE inbounddetail (
  idinbounddetail INT PRIMARY KEY AUTO_INCREMENT,
  idinbound INT,
  idgrade INT,
  idbarang INT,
  box INT,
  weight DECIMAL(6,2),
  notes VARCHAR(100),
  FOREIGN KEY (idinbound) REFERENCES inbound (idinbound),
  FOREIGN KEY (idgrade) REFERENCES grade (idgrade),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
-- outbond adalah transaksi keluar barang
CREATE TABLE outbound (
  idoutbound INT PRIMARY KEY AUTO_INCREMENT,
  nooutbound VARCHAR(30) UNIQUE,
  tgloutbound DATE,
  xweight DECIMAL(12,2),
  xbox INT,
  note VARCHAR(255),
  proses VARCHAR(30),
  idusers INT,
  creatime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idusers) REFERENCES users (idusers)
);
CREATE TABLE outbounddetail (
  idoutbounddetail INT PRIMARY KEY AUTO_INCREMENT,
  idoutbound INT,
  idgrade INT,
  idbarang INT,
  box INT,
  weight DECIMAL(6,2),
  notes VARCHAR(100),
  FOREIGN KEY (idoutbound) REFERENCES outbound (idoutbound),
  FOREIGN KEY (idgrade) REFERENCES grade (idgrade),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
-- returjual adalah transaksi masuk barang
CREATE TABLE returjual (
  idreturjual INT PRIMARY KEY AUTO_INCREMENT,
  returnnumber VARCHAR(30),
  returdate DATE,
  idcustomer INT,
  note VARCHAR(255),
  idusers INT,
  creatime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idcustomer) REFERENCES customers (idcustomer),
  FOREIGN KEY (idusers) REFERENCES users (idusers)
);
CREATE TABLE returjualdetail (
  idreturjualdetail INT PRIMARY KEY AUTO_INCREMENT,
  idreturjual INT,
  idgrade INT,
  idbarang INT,
  kdbarcode VARCHAR(30),
  qty DECIMAL(6,2),
  pcs INT,
  pod DATE,
  creatime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idreturjual) REFERENCES returjual (idreturjual),
  FOREIGN KEY (idgrade) REFERENCES grade (idgrade),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);

CREATE TABLE rawmate (
  idrawmate INT PRIMARY KEY AUTO_INCREMENT,
  kdrawmate VARCHAR(10),
  nmrawmate VARCHAR(30) UNIQUE,
  iduser INT,
  FOREIGN KEY (iduser) REFERENCES users (idusers)
);
CREATE TABLE poproduct (
  idpoproduct INT PRIMARY KEY AUTO_INCREMENT,
  nopoproduct VARCHAR(30),
  idsupplier INT,
  tglpoproduct DATE,
  deliveryat DATE,
  xweight DECIMAL (12,2),
  xamount DECIMAL (12,2),
  Terms VARCHAR (10),
  note VARCHAR (255),
  stat VARCHAR (10),
  idusers INT,
  creatime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idsupplier) REFERENCES supplier (idsupplier),
  FOREIGN KEY (idusers) REFERENCES users (idusers)
);
CREATE TABLE poproductdetail (
  idpoproductdetail INT PRIMARY KEY AUTO_INCREMENT,
  idpoproduct INT,
  idbarang INT,
  qty DECIMAL (12,2),
  price DECIMAL (12,2),
  amount DECIMAL (12,2),
  notes VARCHAR (100),
  FOREIGN KEY (idpoproduct) REFERENCES poproduct (idpoproduct),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
CREATE TABLE poraw (
  idporaw INT PRIMARY KEY AUTO_INCREMENT,
  noporaw VARCHAR(30),
  idsupplier INT,
  tglporaw DATE,
  Terms VARCHAR (10),
  note VARCHAR (255),
  idusers INT,
  creatime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idsupplier) REFERENCES supplier (idsupplier),
  FOREIGN KEY (idusers) REFERENCES users (idusers)
);
CREATE TABLE porawdetail (
  idporawdetail INT PRIMARY KEY AUTO_INCREMENT,
  idporaw INT,
  idrawmate INT,
  qty DECIMAL (12,2),
  price DECIMAL (12,2),
  amount DECIMAL (12,2),
  notes VARCHAR (100),
  FOREIGN KEY (idporaw) REFERENCES poraw (idporaw),
  FOREIGN KEY (idrawmate) REFERENCES rawmate (idrawmate)
);
CREATE TABLE bank (
  idbank INT PRIMARY KEY AUTO_INCREMENT,
  jenisbank VARCHAR (10),
  nmbank VARCHAR (50),
  norek VARCHAR (20)
);
CREATE TABLE plandev (
  idplandev INT PRIMARY KEY AUTO_INCREMENT,
  plandelivery DATE,
  idcustomer INT,
  weight INT,
  driver_name VARCHAR(50),
  armada VARCHAR(10),
  loadtime TIME,
  note VARCHAR(255),
  FOREIGN KEY (idcustomer) REFERENCES customers (idcustomer)
);
CREATE TABLE groupcs (
  idgroup INT PRIMARY KEY AUTO_INCREMENT,
  nmgroup VARCHAR (20),
  terms char(3)
);
CREATE TABLE pricelist (
  idpricelist INT PRIMARY KEY AUTO_INCREMENT,
  idgroup INT,
  latestupdate DATE,
  up VARCHAR(30),
  note VARCHAR(255),
  idusers INT,
  creatime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idgroup) REFERENCES groupcs (idgroup),
  FOREIGN KEY (idusers) REFERENCES users (idusers)
);
CREATE TABLE pricelistdetail (
  idpricelistdetail INT PRIMARY KEY AUTO_INCREMENT,
  idpricelist INT,
  idbarang INT,
  price INT,
  notes VARCHAR(255),
  FOREIGN KEY (idpricelist) REFERENCES pricelist (idpricelist),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
ALTER TABLE customers
ADD idgroup INT;
ALTER TABLE customers
ADD FOREIGN KEY (idgroup) REFERENCES groupcs (idgroup);

CREATE TABLE salesorder (
  idso INT PRIMARY KEY AUTO_INCREMENT,
  sonumber VARCHAR(30),
  idcustomer INT,
  deliverydate DATE,
  po VARCHAR(30),
  alamat VARCHAR(100),
  note VARCHAR(255),
  progress VARCHAR(15),
  idusers INT,
  creatime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idcustomer) REFERENCES customers (idcustomer),
  FOREIGN KEY (idusers) REFERENCES users (idusers)
);
CREATE TABLE salesorderdetail (
  idsodetail INT PRIMARY KEY AUTO_INCREMENT,
  idso INT,
  idbarang INT,
  weight INT,
  price INT,
  notes VARCHAR(255),
  FOREIGN KEY (idso) REFERENCES salesorder (idso),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
CREATE TABLE tally (
  idtally INT PRIMARY KEY AUTO_INCREMENT,
  idso INT,
  sonumber VARCHAR(30),
  notally VARCHAR(30),
  deliverydate DATE,
  idcustomer INT,
  po VARCHAR (30),
  creatime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idso) REFERENCES salesorder (idso),
  FOREIGN KEY (idcustomer) REFERENCES customers (idcustomer)
);
CREATE TABLE tallydetail (
  idtallydetail INT PRIMARY KEY AUTO_INCREMENT,
  idtally INT,
  barcode VARCHAR(30),
  idbarang INT,
  weight DECIMAL (6,2),
  pcs INT,
  pod DATE,
  origin INT,
  FOREIGN KEY (idtally) REFERENCES tally (idtally),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
CREATE TABLE cuts (
  idcut INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  nmcut VARCHAR(20)
);
ALTER TABLE barang
ADD COLUMN idcut INT;
ALTER TABLE barang
ADD FOREIGN KEY fk_idcut (idcut) REFERENCES cuts (idcut);

ALTER TABLE do ADD COLUMN sonumber VARCHAR(30);

CREATE TABLE repack (
  idrepack INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  norepack VARCHAR(30),
  tglrepack DATE,
  note VARCHAR(255),
  idusers INT,
  FOREIGN KEY (idusers) REFERENCES users (idusers)
);
CREATE TABLE detailbahan (
  iddetailbahan INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  idrepack INT,
  barcode VARCHAR(30),
  idbarang INT,
  idgrade INT,
  qty DECIMAL(6,2),
  pcs INT,
  pod DATE,
  origin INT,
  creatime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idgrade) REFERENCES grade (idgrade),
  FOREIGN KEY (idrepack) REFERENCES repack (idrepack),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
CREATE TABLE detailhasil (
  iddetailhasil INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  idrepack INT,
  kdbarcode VARCHAR(30),
  idbarang INT,
  idgrade INT,
  qty DECIMAL(6,2),
  pcs INT,
  packdate DATE,
  exp DATE,
  FOREIGN KEY (idrepack) REFERENCES repack (idrepack),
  FOREIGN KEY (idgrade) REFERENCES grade (idgrade),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);
CREATE TABLE piutang (
  idpiutang INT PRIMARY KEY AUTO_INCREMENT,
  idgroup INT,
  idinvoice INT,
  idcustomer INT,
  balance DECIMAL(12,2),
  progress VARCHAR(15),
  duedate DATE,
  FOREIGN KEY (idgroup) REFERENCES groupcs (idgroup),
  FOREIGN KEY (idinvoice) REFERENCES invoice (idinvoice),
  FOREIGN KEY (idcustomer) REFERENCES customers (idcustomer) 
);
ALTER TABLE relabel ADD COLUMN idgrade INT;
ALTER TABLE relabel ADD FOREIGN KEY fk_idgrade (idgrade) REFERENCES grade (idgrade);
CREATE TABLE bank (
  idbank INT PRIMARY KEY AUTO_INCREMENT,
  nmbank VARCHAR (30),
  account VARCHAR(20),
  note VARHCAR (30)
);
CREATE TABLE stocktake (
  idst INT PRIMARY KEY AUTO_INCREMENT,
  nost VARCHAR (20),
  tglst DATE,
  note VARCHAR (255),
  creatime TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE stocktakedetail (
  idstdetail INT PRIMARY KEY AUTO_INCREMENT,
  idst INT,
  kdbarcode VARCHAR(50),
  idgrade INT,
  idbarang INT,
  qty DECIMAL (6,2),
  pcs INT,
  pod DATE,
  origin INT,
  FOREIGN KEY (idst) REFERENCES stocktake (idst),
  FOREIGN KEY (idgrade) REFERENCES grade (idgrade),
  FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
);

 alter table trading add column idgrade INT;
  ALTER TABLE trading ADD FOREIGN KEY (idgrade) REFERENCES grade (idgrade);

  CREATE TABLE stock (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kdbarcode VARCHAR(50),
    idgrade INT,
    idbarang INT,
    qty DECIMAL (6,2),
    pcs INT,
    pod DATE,
    origin INT,
    creatime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idgrade) REFERENCES grade (idgrade),
    FOREIGN KEY (idbarang) REFERENCES barang (idbarang)
  );
  CREATE TABLE mutasi (
    idmutasi INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nomutasi VARCHAR (30),
    tglmutasi DATE,
    note VARCHAR (255),
    creatime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    idusers INT,
    FOREIGN KEY (idusers) REFERENCES users (idusers)
  );
  CREATE TABLE mutasidetail (
    idmutasidetail INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    idmutasi INT,
    kdbarcode VARCHAR(30),
    idbarang INT,
    idgrade INT,
    qty DECIMAL(6,2),
    pcs INT,
    pod DATE,
    creatime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idgrade) REFERENCES grade (idgrade),
    FOREIGN KEY (idbarang) REFERENCES barang (idbarang),
    FOREIGN KEY (idmutasi) REFERENCES mutasi (idmutasi)
  );