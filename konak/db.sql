CREATE DATABASE swm;
USE swm;
CREATE TABLE pemasok (
idpemasok INT PRIMARY KEY AUTO_INCREMENT,
nmpemasok VARCHAR(30) NOT NULL UNIQUE,
alamatpemasok TEXT
);
CREATE TABLE boning (
idboning INT PRIMARY KEY AUTO_INCREMENT,
batchboning VARCHAR(10),
idpemasok INT,
tglkill DATE,
tglboning DATE,
qtysapi INT,
dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (idpemasok) REFERENCES pemasok (idpemasok)
);
