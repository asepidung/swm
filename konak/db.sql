-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 10, 2024 at 02:54 AM
-- Server version: 10.11.8-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u525862761_swm`
--

-- --------------------------------------------------------

--
-- Table structure for table `adjustment`
--

CREATE TABLE `adjustment` (
  `idadjustment` int(11) NOT NULL,
  `noadjustment` varchar(30) DEFAULT NULL,
  `tgladjustment` date DEFAULT NULL,
  `eventadjustment` varchar(30) DEFAULT NULL,
  `xweight` decimal(6,2) DEFAULT NULL,
  `idusers` int(11) DEFAULT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `adjustmentdetail`
--

CREATE TABLE `adjustmentdetail` (
  `idadjustmentdetail` int(11) NOT NULL,
  `idadjustment` int(11) DEFAULT NULL,
  `idgrade` int(11) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `weight` decimal(6,2) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `idbarang` int(11) NOT NULL,
  `kdbarang` varchar(10) DEFAULT NULL,
  `nmbarang` varchar(30) DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `idcut` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `boning`
--

CREATE TABLE `boning` (
  `idboning` int(11) NOT NULL,
  `batchboning` varchar(10) DEFAULT NULL,
  `idsupplier` int(11) DEFAULT NULL,
  `tglboning` date DEFAULT NULL,
  `qtysapi` int(11) DEFAULT NULL,
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `iduser` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `idcustomer` int(11) NOT NULL,
  `nama_customer` varchar(100) DEFAULT NULL,
  `alamat1` varchar(255) DEFAULT NULL,
  `idsegment` int(11) DEFAULT NULL,
  `top` int(11) DEFAULT NULL,
  `sales_referensi` varchar(50) DEFAULT NULL,
  `pajak` char(3) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT '-',
  `email` varchar(100) DEFAULT '-',
  `catatan` varchar(255) DEFAULT '-',
  `tanggal_update` timestamp NOT NULL DEFAULT current_timestamp(),
  `tukarfaktur` char(3) DEFAULT NULL,
  `alamat2` varchar(255) DEFAULT NULL,
  `alamat3` varchar(255) DEFAULT NULL,
  `idgroup` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cuts`
--

CREATE TABLE `cuts` (
  `idcut` int(11) NOT NULL,
  `nmcut` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detailbahan`
--

CREATE TABLE `detailbahan` (
  `iddetailbahan` int(11) NOT NULL,
  `idrepack` int(11) DEFAULT NULL,
  `barcode` varchar(30) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `idgrade` int(11) DEFAULT NULL,
  `qty` decimal(6,2) DEFAULT NULL,
  `pcs` int(11) DEFAULT NULL,
  `pod` date DEFAULT NULL,
  `origin` int(11) DEFAULT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detailhasil`
--

CREATE TABLE `detailhasil` (
  `iddetailhasil` int(11) NOT NULL,
  `idrepack` int(11) DEFAULT NULL,
  `kdbarcode` varchar(30) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `idgrade` int(11) DEFAULT NULL,
  `qty` decimal(6,2) DEFAULT NULL,
  `pcs` int(11) DEFAULT NULL,
  `packdate` date DEFAULT NULL,
  `exp` date DEFAULT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp(),
  `note` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `do`
--

CREATE TABLE `do` (
  `iddo` int(11) NOT NULL,
  `donumber` varchar(30) DEFAULT NULL,
  `idcustomer` int(11) DEFAULT NULL,
  `idso` int(11) DEFAULT NULL,
  `idtally` int(11) DEFAULT NULL,
  `po` varchar(50) DEFAULT NULL,
  `deliverydate` date DEFAULT NULL,
  `driver` varchar(20) DEFAULT NULL,
  `plat` varchar(12) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `sealnumb` varchar(7) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `xbox` int(11) DEFAULT NULL,
  `xweight` decimal(12,2) DEFAULT NULL,
  `idusers` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `rweight` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dodetail`
--

CREATE TABLE `dodetail` (
  `iddodetail` int(11) NOT NULL,
  `iddo` int(11) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `box` int(11) DEFAULT NULL,
  `weight` decimal(12,2) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doreceipt`
--

CREATE TABLE `doreceipt` (
  `iddoreceipt` int(11) NOT NULL,
  `iddo` int(11) DEFAULT NULL,
  `donumber` varchar(30) DEFAULT NULL,
  `deliverydate` date DEFAULT NULL,
  `idcustomer` int(11) DEFAULT NULL,
  `po` varchar(50) DEFAULT NULL,
  `driver` varchar(20) DEFAULT NULL,
  `plat` varchar(12) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `xbox` int(11) DEFAULT NULL,
  `xweight` decimal(12,2) DEFAULT NULL,
  `idusers` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `alamat` varchar(255) DEFAULT NULL,
  `idso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doreceiptdetail`
--

CREATE TABLE `doreceiptdetail` (
  `iddoreceiptdetail` int(11) NOT NULL,
  `iddoreceipt` int(11) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `box` int(11) DEFAULT NULL,
  `weight` decimal(12,2) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr`
--

CREATE TABLE `gr` (
  `idgr` int(11) NOT NULL,
  `grnumber` varchar(30) NOT NULL,
  `receivedate` date DEFAULT NULL,
  `idsupplier` int(11) DEFAULT NULL,
  `idnumber` varchar(30) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `idgrade` int(11) NOT NULL,
  `nmgrade` char(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grdetail`
--

CREATE TABLE `grdetail` (
  `idgrdetail` int(11) NOT NULL,
  `idgr` int(11) DEFAULT NULL,
  `idgrade` int(11) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `kdbarcode` varchar(50) NOT NULL,
  `pcs` int(11) DEFAULT NULL,
  `qty` decimal(6,2) DEFAULT NULL,
  `pod` date DEFAULT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groupcs`
--

CREATE TABLE `groupcs` (
  `idgroup` int(11) NOT NULL,
  `nmgroup` varchar(20) DEFAULT NULL,
  `terms` char(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inbound`
--

CREATE TABLE `inbound` (
  `idinbound` int(11) NOT NULL,
  `noinbound` varchar(30) DEFAULT NULL,
  `tglinbound` date DEFAULT NULL,
  `xweight` decimal(12,2) DEFAULT NULL,
  `xbox` int(11) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `proses` varchar(30) DEFAULT NULL,
  `idusers` int(11) DEFAULT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inbounddetail`
--

CREATE TABLE `inbounddetail` (
  `idinbounddetail` int(11) NOT NULL,
  `idinbound` int(11) DEFAULT NULL,
  `idgrade` int(11) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `box` int(11) DEFAULT NULL,
  `weight` decimal(6,2) DEFAULT NULL,
  `notes` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `idinvoice` int(11) NOT NULL,
  `noinvoice` varchar(30) NOT NULL,
  `iddoreceipt` int(11) NOT NULL,
  `top` int(11) DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `tgltf` date DEFAULT NULL,
  `idsegment` int(11) NOT NULL,
  `invoice_date` date NOT NULL,
  `idcustomer` int(11) NOT NULL,
  `pocustomer` varchar(50) NOT NULL,
  `donumber` varchar(30) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `xweight` decimal(12,2) NOT NULL,
  `xamount` decimal(12,2) NOT NULL,
  `xdiscount` decimal(12,2) DEFAULT NULL,
  `tax` decimal(12,2) DEFAULT NULL,
  `charge` decimal(12,2) DEFAULT NULL,
  `downpayment` decimal(12,2) DEFAULT NULL,
  `balance` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoicedetail`
--

CREATE TABLE `invoicedetail` (
  `idinvoicedetail` int(11) NOT NULL,
  `idinvoice` int(11) NOT NULL,
  `idbarang` int(11) NOT NULL,
  `weight` decimal(12,2) NOT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `discountrp` decimal(12,2) DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `labelboning`
--

CREATE TABLE `labelboning` (
  `idlabelboning` int(11) NOT NULL,
  `idboning` int(11) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `qty` decimal(10,2) DEFAULT NULL,
  `pcs` char(6) DEFAULT NULL,
  `packdate` date DEFAULT NULL,
  `exp` date DEFAULT NULL,
  `kdbarcode` varchar(20) DEFAULT NULL,
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `iduser` int(11) DEFAULT NULL,
  `idgrade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mutasi`
--

CREATE TABLE `mutasi` (
  `idmutasi` int(11) NOT NULL,
  `nomutasi` varchar(30) DEFAULT NULL,
  `tglmutasi` date DEFAULT NULL,
  `driver` varchar(10) DEFAULT NULL,
  `nopol` varchar(10) DEFAULT NULL,
  `gudang` varchar(10) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp(),
  `idusers` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mutasidetail`
--

CREATE TABLE `mutasidetail` (
  `idmutasidetail` int(11) NOT NULL,
  `idmutasi` int(11) DEFAULT NULL,
  `kdbarcode` varchar(30) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `idgrade` int(11) DEFAULT NULL,
  `qty` decimal(6,2) DEFAULT NULL,
  `pcs` int(11) DEFAULT NULL,
  `pod` date DEFAULT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `outbound`
--

CREATE TABLE `outbound` (
  `idoutbound` int(11) NOT NULL,
  `nooutbound` varchar(30) DEFAULT NULL,
  `tgloutbound` date DEFAULT NULL,
  `xweight` decimal(12,2) DEFAULT NULL,
  `xbox` int(11) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `proses` varchar(30) DEFAULT NULL,
  `idusers` int(11) DEFAULT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `outbounddetail`
--

CREATE TABLE `outbounddetail` (
  `idoutbounddetail` int(11) NOT NULL,
  `idoutbound` int(11) DEFAULT NULL,
  `idgrade` int(11) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `box` int(11) DEFAULT NULL,
  `weight` decimal(6,2) DEFAULT NULL,
  `notes` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `piutang`
--

CREATE TABLE `piutang` (
  `idpiutang` int(11) NOT NULL,
  `idgroup` int(11) DEFAULT NULL,
  `idinvoice` int(11) DEFAULT NULL,
  `idcustomer` int(11) DEFAULT NULL,
  `balance` decimal(12,2) DEFAULT NULL,
  `progress` varchar(15) DEFAULT NULL,
  `duedate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plandev`
--

CREATE TABLE `plandev` (
  `idplandev` int(11) NOT NULL,
  `plandelivery` date DEFAULT NULL,
  `idcustomer` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `driver_name` varchar(50) DEFAULT NULL,
  `armada` varchar(10) DEFAULT NULL,
  `loadtime` time DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `idso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pomaterial`
--

CREATE TABLE `pomaterial` (
  `idpomaterial` int(11) NOT NULL,
  `nopomaterial` varchar(30) DEFAULT NULL,
  `idsupplier` int(11) DEFAULT NULL,
  `tglpomaterial` date DEFAULT NULL,
  `deliveryat` date DEFAULT NULL,
  `Terms` varchar(10) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `stat` varchar(10) DEFAULT NULL,
  `idusers` int(11) DEFAULT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pomaterialdetail`
--

CREATE TABLE `pomaterialdetail` (
  `idpomaterialdetail` int(11) NOT NULL,
  `idpomaterial` int(11) DEFAULT NULL,
  `idrawmate` int(11) DEFAULT NULL,
  `qty` decimal(12,2) DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `notes` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `poproduct`
--

CREATE TABLE `poproduct` (
  `idpoproduct` int(11) NOT NULL,
  `nopoproduct` varchar(30) DEFAULT NULL,
  `idsupplier` int(11) DEFAULT NULL,
  `tglpoproduct` date DEFAULT NULL,
  `deliveryat` date DEFAULT NULL,
  `xweight` decimal(12,2) DEFAULT NULL,
  `xamount` decimal(12,2) DEFAULT NULL,
  `Terms` varchar(10) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `stat` varchar(10) DEFAULT NULL,
  `idusers` int(11) DEFAULT NULL,
  `creatime` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `poproductdetail`
--

CREATE TABLE `poproductdetail` (
  `idpoproductdetail` int(11) NOT NULL,
  `idpoproduct` int(11) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `qty` decimal(12,2) DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `notes` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pricelist`
--

CREATE TABLE `pricelist` (
  `idpricelist` int(11) NOT NULL,
  `idgroup` int(11) DEFAULT NULL,
  `latestupdate` date DEFAULT NULL,
  `up` varchar(30) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `idusers` int(11) DEFAULT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pricelistdetail`
--

CREATE TABLE `pricelistdetail` (
  `idpricelistdetail` int(11) NOT NULL,
  `idpricelist` int(11) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rawmate`
--

CREATE TABLE `rawmate` (
  `idrawmate` int(11) NOT NULL,
  `kdrawmate` varchar(10) DEFAULT NULL,
  `nmrawmate` varchar(30) DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `relabel`
--

CREATE TABLE `relabel` (
  `idrelabel` int(11) NOT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `qty` decimal(10,2) DEFAULT NULL,
  `pcs` char(5) DEFAULT NULL,
  `packdate` date DEFAULT NULL,
  `exp` date DEFAULT NULL,
  `kdbarcode` varchar(20) DEFAULT NULL,
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `iduser` int(11) DEFAULT NULL,
  `idgrade` int(11) DEFAULT NULL,
  `xpcs` int(11) DEFAULT NULL,
  `xpackdate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `repack`
--

CREATE TABLE `repack` (
  `idrepack` int(11) NOT NULL,
  `norepack` varchar(30) DEFAULT NULL,
  `tglrepack` date DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `idusers` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `returjual`
--

CREATE TABLE `returjual` (
  `idreturjual` int(11) NOT NULL,
  `returnnumber` varchar(30) DEFAULT NULL,
  `returdate` date DEFAULT NULL,
  `idcustomer` int(11) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `idusers` int(11) DEFAULT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `returjualdetail`
--

CREATE TABLE `returjualdetail` (
  `idreturjualdetail` int(11) NOT NULL,
  `idreturjual` int(11) DEFAULT NULL,
  `idgrade` int(11) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `kdbarcode` varchar(30) DEFAULT NULL,
  `qty` decimal(6,2) DEFAULT NULL,
  `pcs` int(11) DEFAULT NULL,
  `pod` date DEFAULT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salesorder`
--

CREATE TABLE `salesorder` (
  `idso` int(11) NOT NULL,
  `sonumber` varchar(30) DEFAULT NULL,
  `idcustomer` int(11) DEFAULT NULL,
  `deliverydate` date DEFAULT NULL,
  `po` varchar(30) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `progress` varchar(15) DEFAULT NULL,
  `idusers` int(11) DEFAULT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salesorderdetail`
--

CREATE TABLE `salesorderdetail` (
  `idsodetail` int(11) NOT NULL,
  `idso` int(11) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `segment`
--

CREATE TABLE `segment` (
  `idsegment` int(11) NOT NULL,
  `nmsegment` varchar(50) DEFAULT NULL,
  `banksegment` varchar(50) DEFAULT NULL,
  `accname` varchar(50) DEFAULT NULL,
  `accnumber` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `kdbarcode` varchar(50) DEFAULT NULL,
  `idgrade` int(11) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `qty` decimal(6,2) DEFAULT NULL,
  `pcs` int(11) DEFAULT NULL,
  `pod` date DEFAULT NULL,
  `origin` int(11) DEFAULT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp(),
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stocktake`
--

CREATE TABLE `stocktake` (
  `idst` int(11) NOT NULL,
  `nost` varchar(20) DEFAULT NULL,
  `tglst` date DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stocktakedetail`
--

CREATE TABLE `stocktakedetail` (
  `idstdetail` int(11) NOT NULL,
  `idst` int(11) DEFAULT NULL,
  `kdbarcode` varchar(50) DEFAULT NULL,
  `idgrade` int(11) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `qty` decimal(6,2) DEFAULT NULL,
  `pcs` int(11) DEFAULT NULL,
  `pod` date DEFAULT NULL,
  `origin` int(11) DEFAULT NULL,
  `timescan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `idsupplier` int(11) NOT NULL,
  `nmsupplier` varchar(100) DEFAULT NULL,
  `jenis_usaha` varchar(100) DEFAULT NULL,
  `alamat` varchar(200) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `npwp` varchar(20) DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tally`
--

CREATE TABLE `tally` (
  `idtally` int(11) NOT NULL,
  `idso` int(11) DEFAULT NULL,
  `sonumber` varchar(30) DEFAULT NULL,
  `notally` varchar(30) DEFAULT NULL,
  `deliverydate` date DEFAULT NULL,
  `idcustomer` int(11) DEFAULT NULL,
  `po` varchar(30) DEFAULT NULL,
  `stat` varchar(10) NOT NULL,
  `sealnumb` varchar(7) NOT NULL,
  `creatime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tallydetail`
--

CREATE TABLE `tallydetail` (
  `idtallydetail` int(11) NOT NULL,
  `idtally` int(11) DEFAULT NULL,
  `barcode` varchar(30) DEFAULT NULL,
  `idgrade` int(11) DEFAULT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `weight` decimal(6,2) DEFAULT NULL,
  `pcs` int(11) DEFAULT NULL,
  `pod` date DEFAULT NULL,
  `origin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trading`
--

CREATE TABLE `trading` (
  `idtrading` int(11) NOT NULL,
  `idbarang` int(11) DEFAULT NULL,
  `qty` decimal(12,2) DEFAULT NULL,
  `pcs` char(5) DEFAULT NULL,
  `packdate` date DEFAULT NULL,
  `exp` date DEFAULT NULL,
  `kdbarcode` varchar(20) DEFAULT NULL,
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `iduser` int(11) DEFAULT NULL,
  `idgrade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `idusers` int(11) NOT NULL,
  `userid` varchar(100) DEFAULT NULL,
  `passuser` varchar(100) DEFAULT NULL,
  `fullname` varchar(30) DEFAULT NULL,
  `status` enum('AKTIF','INAKTIF','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adjustment`
--
ALTER TABLE `adjustment`
  ADD PRIMARY KEY (`idadjustment`),
  ADD UNIQUE KEY `noadjustment` (`noadjustment`),
  ADD KEY `idusers` (`idusers`);

--
-- Indexes for table `adjustmentdetail`
--
ALTER TABLE `adjustmentdetail`
  ADD PRIMARY KEY (`idadjustmentdetail`),
  ADD KEY `idadjustment` (`idadjustment`),
  ADD KEY `idgrade` (`idgrade`),
  ADD KEY `idbarang` (`idbarang`);

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`idbarang`),
  ADD KEY `iduser` (`iduser`),
  ADD KEY `fk_idcut` (`idcut`);

--
-- Indexes for table `boning`
--
ALTER TABLE `boning`
  ADD PRIMARY KEY (`idboning`),
  ADD KEY `iduser` (`iduser`),
  ADD KEY `idsupplier` (`idsupplier`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`idcustomer`),
  ADD KEY `idsegment` (`idsegment`),
  ADD KEY `idgroup` (`idgroup`);

--
-- Indexes for table `cuts`
--
ALTER TABLE `cuts`
  ADD PRIMARY KEY (`idcut`);

--
-- Indexes for table `detailbahan`
--
ALTER TABLE `detailbahan`
  ADD PRIMARY KEY (`iddetailbahan`),
  ADD KEY `idgrade` (`idgrade`),
  ADD KEY `idrepack` (`idrepack`),
  ADD KEY `idbarang` (`idbarang`);

--
-- Indexes for table `detailhasil`
--
ALTER TABLE `detailhasil`
  ADD PRIMARY KEY (`iddetailhasil`),
  ADD KEY `idrepack` (`idrepack`),
  ADD KEY `idgrade` (`idgrade`),
  ADD KEY `idbarang` (`idbarang`);

--
-- Indexes for table `do`
--
ALTER TABLE `do`
  ADD PRIMARY KEY (`iddo`),
  ADD UNIQUE KEY `donumber` (`donumber`),
  ADD KEY `idcustomer` (`idcustomer`),
  ADD KEY `idusers` (`idusers`),
  ADD KEY `do_ibfk_3` (`idso`),
  ADD KEY `do_ibfk_4` (`idtally`);

--
-- Indexes for table `dodetail`
--
ALTER TABLE `dodetail`
  ADD PRIMARY KEY (`iddodetail`),
  ADD KEY `iddo` (`iddo`),
  ADD KEY `idbarang` (`idbarang`);

--
-- Indexes for table `doreceipt`
--
ALTER TABLE `doreceipt`
  ADD PRIMARY KEY (`iddoreceipt`),
  ADD UNIQUE KEY `donumber` (`donumber`),
  ADD KEY `iddo` (`iddo`),
  ADD KEY `idcustomer` (`idcustomer`),
  ADD KEY `idusers` (`idusers`),
  ADD KEY `idso` (`idso`);

--
-- Indexes for table `doreceiptdetail`
--
ALTER TABLE `doreceiptdetail`
  ADD PRIMARY KEY (`iddoreceiptdetail`),
  ADD KEY `iddoreceipt` (`iddoreceipt`),
  ADD KEY `idbarang` (`idbarang`);

--
-- Indexes for table `gr`
--
ALTER TABLE `gr`
  ADD PRIMARY KEY (`idgr`),
  ADD UNIQUE KEY `grnumber` (`grnumber`),
  ADD KEY `idsupplier` (`idsupplier`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`idgrade`),
  ADD UNIQUE KEY `nmgrade` (`nmgrade`);

--
-- Indexes for table `grdetail`
--
ALTER TABLE `grdetail`
  ADD PRIMARY KEY (`idgrdetail`),
  ADD KEY `idgr` (`idgr`),
  ADD KEY `idbarang` (`idbarang`),
  ADD KEY `idgrade` (`idgrade`);

--
-- Indexes for table `groupcs`
--
ALTER TABLE `groupcs`
  ADD PRIMARY KEY (`idgroup`);

--
-- Indexes for table `inbound`
--
ALTER TABLE `inbound`
  ADD PRIMARY KEY (`idinbound`),
  ADD UNIQUE KEY `noinbound` (`noinbound`),
  ADD KEY `idusers` (`idusers`);

--
-- Indexes for table `inbounddetail`
--
ALTER TABLE `inbounddetail`
  ADD PRIMARY KEY (`idinbounddetail`),
  ADD KEY `idinbound` (`idinbound`),
  ADD KEY `idgrade` (`idgrade`),
  ADD KEY `idbarang` (`idbarang`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`idinvoice`),
  ADD UNIQUE KEY `noinvoice` (`noinvoice`),
  ADD KEY `iddoreceipt` (`iddoreceipt`),
  ADD KEY `idsegment` (`idsegment`),
  ADD KEY `idcustomer` (`idcustomer`);

--
-- Indexes for table `invoicedetail`
--
ALTER TABLE `invoicedetail`
  ADD PRIMARY KEY (`idinvoicedetail`),
  ADD KEY `idinvoice` (`idinvoice`),
  ADD KEY `idbarang` (`idbarang`);

--
-- Indexes for table `labelboning`
--
ALTER TABLE `labelboning`
  ADD PRIMARY KEY (`idlabelboning`),
  ADD UNIQUE KEY `kdbarcode` (`kdbarcode`),
  ADD KEY `idbarang` (`idbarang`),
  ADD KEY `iduser` (`iduser`),
  ADD KEY `idboning` (`idboning`),
  ADD KEY `fk_idgrade_grade` (`idgrade`);

--
-- Indexes for table `mutasi`
--
ALTER TABLE `mutasi`
  ADD PRIMARY KEY (`idmutasi`),
  ADD KEY `idusers` (`idusers`);

--
-- Indexes for table `mutasidetail`
--
ALTER TABLE `mutasidetail`
  ADD PRIMARY KEY (`idmutasidetail`),
  ADD KEY `idgrade` (`idgrade`),
  ADD KEY `idbarang` (`idbarang`),
  ADD KEY `idmutasi` (`idmutasi`);

--
-- Indexes for table `outbound`
--
ALTER TABLE `outbound`
  ADD PRIMARY KEY (`idoutbound`),
  ADD UNIQUE KEY `nooutbound` (`nooutbound`),
  ADD KEY `idusers` (`idusers`);

--
-- Indexes for table `outbounddetail`
--
ALTER TABLE `outbounddetail`
  ADD PRIMARY KEY (`idoutbounddetail`),
  ADD KEY `idoutbound` (`idoutbound`),
  ADD KEY `idgrade` (`idgrade`),
  ADD KEY `idbarang` (`idbarang`);

--
-- Indexes for table `piutang`
--
ALTER TABLE `piutang`
  ADD PRIMARY KEY (`idpiutang`),
  ADD KEY `idgroup` (`idgroup`),
  ADD KEY `idinvoice` (`idinvoice`),
  ADD KEY `idcustomer` (`idcustomer`);

--
-- Indexes for table `plandev`
--
ALTER TABLE `plandev`
  ADD PRIMARY KEY (`idplandev`),
  ADD KEY `idcustomer` (`idcustomer`),
  ADD KEY `idso` (`idso`);

--
-- Indexes for table `pomaterial`
--
ALTER TABLE `pomaterial`
  ADD PRIMARY KEY (`idpomaterial`),
  ADD KEY `idsupplier` (`idsupplier`),
  ADD KEY `idusers` (`idusers`);

--
-- Indexes for table `pomaterialdetail`
--
ALTER TABLE `pomaterialdetail`
  ADD PRIMARY KEY (`idpomaterialdetail`),
  ADD KEY `idpomaterial` (`idpomaterial`),
  ADD KEY `idrawmate` (`idrawmate`);

--
-- Indexes for table `poproduct`
--
ALTER TABLE `poproduct`
  ADD PRIMARY KEY (`idpoproduct`),
  ADD KEY `idsupplier` (`idsupplier`),
  ADD KEY `idusers` (`idusers`);

--
-- Indexes for table `poproductdetail`
--
ALTER TABLE `poproductdetail`
  ADD PRIMARY KEY (`idpoproductdetail`),
  ADD KEY `idpoproduct` (`idpoproduct`),
  ADD KEY `idbarang` (`idbarang`);

--
-- Indexes for table `pricelist`
--
ALTER TABLE `pricelist`
  ADD PRIMARY KEY (`idpricelist`),
  ADD KEY `idgroup` (`idgroup`),
  ADD KEY `idusers` (`idusers`);

--
-- Indexes for table `pricelistdetail`
--
ALTER TABLE `pricelistdetail`
  ADD PRIMARY KEY (`idpricelistdetail`),
  ADD KEY `idpricelist` (`idpricelist`),
  ADD KEY `idbarang` (`idbarang`);

--
-- Indexes for table `rawmate`
--
ALTER TABLE `rawmate`
  ADD PRIMARY KEY (`idrawmate`),
  ADD UNIQUE KEY `nmrawmate` (`nmrawmate`),
  ADD KEY `iduser` (`iduser`);

--
-- Indexes for table `relabel`
--
ALTER TABLE `relabel`
  ADD PRIMARY KEY (`idrelabel`),
  ADD KEY `idbarang` (`idbarang`),
  ADD KEY `iduser` (`iduser`),
  ADD KEY `fk_idgrade` (`idgrade`);

--
-- Indexes for table `repack`
--
ALTER TABLE `repack`
  ADD PRIMARY KEY (`idrepack`),
  ADD KEY `idusers` (`idusers`);

--
-- Indexes for table `returjual`
--
ALTER TABLE `returjual`
  ADD PRIMARY KEY (`idreturjual`),
  ADD KEY `idcustomer` (`idcustomer`),
  ADD KEY `idusers` (`idusers`);

--
-- Indexes for table `returjualdetail`
--
ALTER TABLE `returjualdetail`
  ADD PRIMARY KEY (`idreturjualdetail`),
  ADD KEY `idreturjual` (`idreturjual`),
  ADD KEY `idgrade` (`idgrade`),
  ADD KEY `idbarang` (`idbarang`);

--
-- Indexes for table `salesorder`
--
ALTER TABLE `salesorder`
  ADD PRIMARY KEY (`idso`),
  ADD KEY `idcustomer` (`idcustomer`),
  ADD KEY `idusers` (`idusers`);

--
-- Indexes for table `salesorderdetail`
--
ALTER TABLE `salesorderdetail`
  ADD PRIMARY KEY (`idsodetail`),
  ADD KEY `idso` (`idso`),
  ADD KEY `idbarang` (`idbarang`);

--
-- Indexes for table `segment`
--
ALTER TABLE `segment`
  ADD PRIMARY KEY (`idsegment`),
  ADD UNIQUE KEY `nmsegment` (`nmsegment`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idgrade` (`idgrade`),
  ADD KEY `idbarang` (`idbarang`);

--
-- Indexes for table `stocktake`
--
ALTER TABLE `stocktake`
  ADD PRIMARY KEY (`idst`);

--
-- Indexes for table `stocktakedetail`
--
ALTER TABLE `stocktakedetail`
  ADD PRIMARY KEY (`idstdetail`),
  ADD KEY `idst` (`idst`),
  ADD KEY `idgrade` (`idgrade`),
  ADD KEY `idbarang` (`idbarang`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`idsupplier`),
  ADD KEY `iduser` (`iduser`);

--
-- Indexes for table `tally`
--
ALTER TABLE `tally`
  ADD PRIMARY KEY (`idtally`),
  ADD KEY `idso` (`idso`),
  ADD KEY `idcustomer` (`idcustomer`);

--
-- Indexes for table `tallydetail`
--
ALTER TABLE `tallydetail`
  ADD PRIMARY KEY (`idtallydetail`),
  ADD KEY `idtally` (`idtally`),
  ADD KEY `idbarang` (`idbarang`),
  ADD KEY `idgrade` (`idgrade`);

--
-- Indexes for table `trading`
--
ALTER TABLE `trading`
  ADD PRIMARY KEY (`idtrading`),
  ADD UNIQUE KEY `kdbarcode` (`kdbarcode`),
  ADD KEY `idbarang` (`idbarang`),
  ADD KEY `iduser` (`iduser`),
  ADD KEY `idgrade` (`idgrade`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idusers`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adjustment`
--
ALTER TABLE `adjustment`
  MODIFY `idadjustment` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `adjustmentdetail`
--
ALTER TABLE `adjustmentdetail`
  MODIFY `idadjustmentdetail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `idbarang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boning`
--
ALTER TABLE `boning`
  MODIFY `idboning` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `idcustomer` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cuts`
--
ALTER TABLE `cuts`
  MODIFY `idcut` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detailbahan`
--
ALTER TABLE `detailbahan`
  MODIFY `iddetailbahan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detailhasil`
--
ALTER TABLE `detailhasil`
  MODIFY `iddetailhasil` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `do`
--
ALTER TABLE `do`
  MODIFY `iddo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dodetail`
--
ALTER TABLE `dodetail`
  MODIFY `iddodetail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doreceipt`
--
ALTER TABLE `doreceipt`
  MODIFY `iddoreceipt` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doreceiptdetail`
--
ALTER TABLE `doreceiptdetail`
  MODIFY `iddoreceiptdetail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr`
--
ALTER TABLE `gr`
  MODIFY `idgr` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `idgrade` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grdetail`
--
ALTER TABLE `grdetail`
  MODIFY `idgrdetail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groupcs`
--
ALTER TABLE `groupcs`
  MODIFY `idgroup` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inbound`
--
ALTER TABLE `inbound`
  MODIFY `idinbound` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inbounddetail`
--
ALTER TABLE `inbounddetail`
  MODIFY `idinbounddetail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `idinvoice` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoicedetail`
--
ALTER TABLE `invoicedetail`
  MODIFY `idinvoicedetail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `labelboning`
--
ALTER TABLE `labelboning`
  MODIFY `idlabelboning` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mutasi`
--
ALTER TABLE `mutasi`
  MODIFY `idmutasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mutasidetail`
--
ALTER TABLE `mutasidetail`
  MODIFY `idmutasidetail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outbound`
--
ALTER TABLE `outbound`
  MODIFY `idoutbound` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outbounddetail`
--
ALTER TABLE `outbounddetail`
  MODIFY `idoutbounddetail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `piutang`
--
ALTER TABLE `piutang`
  MODIFY `idpiutang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plandev`
--
ALTER TABLE `plandev`
  MODIFY `idplandev` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pomaterial`
--
ALTER TABLE `pomaterial`
  MODIFY `idpomaterial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pomaterialdetail`
--
ALTER TABLE `pomaterialdetail`
  MODIFY `idpomaterialdetail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `poproduct`
--
ALTER TABLE `poproduct`
  MODIFY `idpoproduct` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `poproductdetail`
--
ALTER TABLE `poproductdetail`
  MODIFY `idpoproductdetail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pricelist`
--
ALTER TABLE `pricelist`
  MODIFY `idpricelist` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pricelistdetail`
--
ALTER TABLE `pricelistdetail`
  MODIFY `idpricelistdetail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rawmate`
--
ALTER TABLE `rawmate`
  MODIFY `idrawmate` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `relabel`
--
ALTER TABLE `relabel`
  MODIFY `idrelabel` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `repack`
--
ALTER TABLE `repack`
  MODIFY `idrepack` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `returjual`
--
ALTER TABLE `returjual`
  MODIFY `idreturjual` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `returjualdetail`
--
ALTER TABLE `returjualdetail`
  MODIFY `idreturjualdetail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salesorder`
--
ALTER TABLE `salesorder`
  MODIFY `idso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salesorderdetail`
--
ALTER TABLE `salesorderdetail`
  MODIFY `idsodetail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `segment`
--
ALTER TABLE `segment`
  MODIFY `idsegment` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stocktake`
--
ALTER TABLE `stocktake`
  MODIFY `idst` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stocktakedetail`
--
ALTER TABLE `stocktakedetail`
  MODIFY `idstdetail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `idsupplier` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tally`
--
ALTER TABLE `tally`
  MODIFY `idtally` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tallydetail`
--
ALTER TABLE `tallydetail`
  MODIFY `idtallydetail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trading`
--
ALTER TABLE `trading`
  MODIFY `idtrading` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `idusers` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adjustment`
--
ALTER TABLE `adjustment`
  ADD CONSTRAINT `adjustment_ibfk_1` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`);

--
-- Constraints for table `adjustmentdetail`
--
ALTER TABLE `adjustmentdetail`
  ADD CONSTRAINT `adjustmentdetail_ibfk_1` FOREIGN KEY (`idadjustment`) REFERENCES `adjustment` (`idadjustment`),
  ADD CONSTRAINT `adjustmentdetail_ibfk_2` FOREIGN KEY (`idgrade`) REFERENCES `grade` (`idgrade`),
  ADD CONSTRAINT `adjustmentdetail_ibfk_3` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`);

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `users` (`idusers`),
  ADD CONSTRAINT `fk_idcut` FOREIGN KEY (`idcut`) REFERENCES `cuts` (`idcut`);

--
-- Constraints for table `boning`
--
ALTER TABLE `boning`
  ADD CONSTRAINT `boning_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `users` (`idusers`),
  ADD CONSTRAINT `boning_ibfk_2` FOREIGN KEY (`idsupplier`) REFERENCES `supplier` (`idsupplier`);

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`idsegment`) REFERENCES `segment` (`idsegment`),
  ADD CONSTRAINT `customers_ibfk_2` FOREIGN KEY (`idgroup`) REFERENCES `groupcs` (`idgroup`);

--
-- Constraints for table `detailbahan`
--
ALTER TABLE `detailbahan`
  ADD CONSTRAINT `detailbahan_ibfk_1` FOREIGN KEY (`idgrade`) REFERENCES `grade` (`idgrade`),
  ADD CONSTRAINT `detailbahan_ibfk_2` FOREIGN KEY (`idrepack`) REFERENCES `repack` (`idrepack`),
  ADD CONSTRAINT `detailbahan_ibfk_3` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`);

--
-- Constraints for table `detailhasil`
--
ALTER TABLE `detailhasil`
  ADD CONSTRAINT `detailhasil_ibfk_1` FOREIGN KEY (`idrepack`) REFERENCES `repack` (`idrepack`),
  ADD CONSTRAINT `detailhasil_ibfk_2` FOREIGN KEY (`idgrade`) REFERENCES `grade` (`idgrade`),
  ADD CONSTRAINT `detailhasil_ibfk_3` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`);

--
-- Constraints for table `do`
--
ALTER TABLE `do`
  ADD CONSTRAINT `do_ibfk_1` FOREIGN KEY (`idcustomer`) REFERENCES `customers` (`idcustomer`),
  ADD CONSTRAINT `do_ibfk_2` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`),
  ADD CONSTRAINT `do_ibfk_3` FOREIGN KEY (`idso`) REFERENCES `salesorder` (`idso`),
  ADD CONSTRAINT `do_ibfk_4` FOREIGN KEY (`idtally`) REFERENCES `tally` (`idtally`);

--
-- Constraints for table `dodetail`
--
ALTER TABLE `dodetail`
  ADD CONSTRAINT `dodetail_ibfk_1` FOREIGN KEY (`iddo`) REFERENCES `do` (`iddo`),
  ADD CONSTRAINT `dodetail_ibfk_3` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`);

--
-- Constraints for table `doreceipt`
--
ALTER TABLE `doreceipt`
  ADD CONSTRAINT `doreceipt_ibfk_1` FOREIGN KEY (`iddo`) REFERENCES `do` (`iddo`),
  ADD CONSTRAINT `doreceipt_ibfk_2` FOREIGN KEY (`idcustomer`) REFERENCES `customers` (`idcustomer`),
  ADD CONSTRAINT `doreceipt_ibfk_3` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`),
  ADD CONSTRAINT `doreceipt_ibfk_4` FOREIGN KEY (`idso`) REFERENCES `salesorder` (`idso`);

--
-- Constraints for table `doreceiptdetail`
--
ALTER TABLE `doreceiptdetail`
  ADD CONSTRAINT `doreceiptdetail_ibfk_1` FOREIGN KEY (`iddoreceipt`) REFERENCES `doreceipt` (`iddoreceipt`),
  ADD CONSTRAINT `doreceiptdetail_ibfk_3` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`);

--
-- Constraints for table `gr`
--
ALTER TABLE `gr`
  ADD CONSTRAINT `gr_ibfk_1` FOREIGN KEY (`idsupplier`) REFERENCES `supplier` (`idsupplier`);

--
-- Constraints for table `grdetail`
--
ALTER TABLE `grdetail`
  ADD CONSTRAINT `grdetail_ibfk_1` FOREIGN KEY (`idgr`) REFERENCES `gr` (`idgr`),
  ADD CONSTRAINT `grdetail_ibfk_2` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`),
  ADD CONSTRAINT `grdetail_ibfk_3` FOREIGN KEY (`idgrade`) REFERENCES `grade` (`idgrade`);

--
-- Constraints for table `inbound`
--
ALTER TABLE `inbound`
  ADD CONSTRAINT `inbound_ibfk_1` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`);

--
-- Constraints for table `inbounddetail`
--
ALTER TABLE `inbounddetail`
  ADD CONSTRAINT `inbounddetail_ibfk_1` FOREIGN KEY (`idinbound`) REFERENCES `inbound` (`idinbound`),
  ADD CONSTRAINT `inbounddetail_ibfk_2` FOREIGN KEY (`idgrade`) REFERENCES `grade` (`idgrade`),
  ADD CONSTRAINT `inbounddetail_ibfk_3` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`);

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`iddoreceipt`) REFERENCES `doreceipt` (`iddoreceipt`),
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`idsegment`) REFERENCES `segment` (`idsegment`),
  ADD CONSTRAINT `invoice_ibfk_3` FOREIGN KEY (`idcustomer`) REFERENCES `customers` (`idcustomer`);

--
-- Constraints for table `invoicedetail`
--
ALTER TABLE `invoicedetail`
  ADD CONSTRAINT `invoicedetail_ibfk_1` FOREIGN KEY (`idinvoice`) REFERENCES `invoice` (`idinvoice`),
  ADD CONSTRAINT `invoicedetail_ibfk_3` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`);

--
-- Constraints for table `labelboning`
--
ALTER TABLE `labelboning`
  ADD CONSTRAINT `fk_idgrade_grade` FOREIGN KEY (`idgrade`) REFERENCES `grade` (`idgrade`),
  ADD CONSTRAINT `labelboning_ibfk_1` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`),
  ADD CONSTRAINT `labelboning_ibfk_2` FOREIGN KEY (`iduser`) REFERENCES `users` (`idusers`),
  ADD CONSTRAINT `labelboning_ibfk_3` FOREIGN KEY (`idboning`) REFERENCES `boning` (`idboning`);

--
-- Constraints for table `mutasi`
--
ALTER TABLE `mutasi`
  ADD CONSTRAINT `mutasi_ibfk_1` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`);

--
-- Constraints for table `mutasidetail`
--
ALTER TABLE `mutasidetail`
  ADD CONSTRAINT `mutasidetail_ibfk_1` FOREIGN KEY (`idgrade`) REFERENCES `grade` (`idgrade`),
  ADD CONSTRAINT `mutasidetail_ibfk_2` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`),
  ADD CONSTRAINT `mutasidetail_ibfk_3` FOREIGN KEY (`idmutasi`) REFERENCES `mutasi` (`idmutasi`);

--
-- Constraints for table `outbound`
--
ALTER TABLE `outbound`
  ADD CONSTRAINT `outbound_ibfk_1` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`);

--
-- Constraints for table `outbounddetail`
--
ALTER TABLE `outbounddetail`
  ADD CONSTRAINT `outbounddetail_ibfk_1` FOREIGN KEY (`idoutbound`) REFERENCES `outbound` (`idoutbound`),
  ADD CONSTRAINT `outbounddetail_ibfk_2` FOREIGN KEY (`idgrade`) REFERENCES `grade` (`idgrade`),
  ADD CONSTRAINT `outbounddetail_ibfk_3` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`);

--
-- Constraints for table `piutang`
--
ALTER TABLE `piutang`
  ADD CONSTRAINT `piutang_ibfk_1` FOREIGN KEY (`idgroup`) REFERENCES `groupcs` (`idgroup`),
  ADD CONSTRAINT `piutang_ibfk_2` FOREIGN KEY (`idinvoice`) REFERENCES `invoice` (`idinvoice`),
  ADD CONSTRAINT `piutang_ibfk_3` FOREIGN KEY (`idcustomer`) REFERENCES `customers` (`idcustomer`);

--
-- Constraints for table `plandev`
--
ALTER TABLE `plandev`
  ADD CONSTRAINT `plandev_ibfk_1` FOREIGN KEY (`idcustomer`) REFERENCES `customers` (`idcustomer`),
  ADD CONSTRAINT `plandev_ibfk_2` FOREIGN KEY (`idso`) REFERENCES `salesorder` (`idso`);

--
-- Constraints for table `pomaterial`
--
ALTER TABLE `pomaterial`
  ADD CONSTRAINT `pomaterial_ibfk_1` FOREIGN KEY (`idsupplier`) REFERENCES `supplier` (`idsupplier`),
  ADD CONSTRAINT `pomaterial_ibfk_2` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`);

--
-- Constraints for table `pomaterialdetail`
--
ALTER TABLE `pomaterialdetail`
  ADD CONSTRAINT `pomaterialdetail_ibfk_1` FOREIGN KEY (`idpomaterial`) REFERENCES `pomaterial` (`idpomaterial`),
  ADD CONSTRAINT `pomaterialdetail_ibfk_2` FOREIGN KEY (`idrawmate`) REFERENCES `rawmate` (`idrawmate`);

--
-- Constraints for table `poproduct`
--
ALTER TABLE `poproduct`
  ADD CONSTRAINT `poproduct_ibfk_1` FOREIGN KEY (`idsupplier`) REFERENCES `supplier` (`idsupplier`),
  ADD CONSTRAINT `poproduct_ibfk_2` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`);

--
-- Constraints for table `poproductdetail`
--
ALTER TABLE `poproductdetail`
  ADD CONSTRAINT `poproductdetail_ibfk_1` FOREIGN KEY (`idpoproduct`) REFERENCES `poproduct` (`idpoproduct`),
  ADD CONSTRAINT `poproductdetail_ibfk_2` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`);

--
-- Constraints for table `pricelist`
--
ALTER TABLE `pricelist`
  ADD CONSTRAINT `pricelist_ibfk_1` FOREIGN KEY (`idgroup`) REFERENCES `groupcs` (`idgroup`),
  ADD CONSTRAINT `pricelist_ibfk_2` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`);

--
-- Constraints for table `pricelistdetail`
--
ALTER TABLE `pricelistdetail`
  ADD CONSTRAINT `pricelistdetail_ibfk_1` FOREIGN KEY (`idpricelist`) REFERENCES `pricelist` (`idpricelist`),
  ADD CONSTRAINT `pricelistdetail_ibfk_2` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`);

--
-- Constraints for table `rawmate`
--
ALTER TABLE `rawmate`
  ADD CONSTRAINT `rawmate_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `users` (`idusers`);

--
-- Constraints for table `relabel`
--
ALTER TABLE `relabel`
  ADD CONSTRAINT `fk_idgrade` FOREIGN KEY (`idgrade`) REFERENCES `grade` (`idgrade`),
  ADD CONSTRAINT `relabel_ibfk_1` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`),
  ADD CONSTRAINT `relabel_ibfk_2` FOREIGN KEY (`iduser`) REFERENCES `users` (`idusers`);

--
-- Constraints for table `repack`
--
ALTER TABLE `repack`
  ADD CONSTRAINT `repack_ibfk_1` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`);

--
-- Constraints for table `returjual`
--
ALTER TABLE `returjual`
  ADD CONSTRAINT `returjual_ibfk_1` FOREIGN KEY (`idcustomer`) REFERENCES `customers` (`idcustomer`),
  ADD CONSTRAINT `returjual_ibfk_2` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`);

--
-- Constraints for table `returjualdetail`
--
ALTER TABLE `returjualdetail`
  ADD CONSTRAINT `returjualdetail_ibfk_1` FOREIGN KEY (`idreturjual`) REFERENCES `returjual` (`idreturjual`),
  ADD CONSTRAINT `returjualdetail_ibfk_2` FOREIGN KEY (`idgrade`) REFERENCES `grade` (`idgrade`),
  ADD CONSTRAINT `returjualdetail_ibfk_3` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`);

--
-- Constraints for table `salesorder`
--
ALTER TABLE `salesorder`
  ADD CONSTRAINT `salesorder_ibfk_1` FOREIGN KEY (`idcustomer`) REFERENCES `customers` (`idcustomer`),
  ADD CONSTRAINT `salesorder_ibfk_2` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`);

--
-- Constraints for table `salesorderdetail`
--
ALTER TABLE `salesorderdetail`
  ADD CONSTRAINT `salesorderdetail_ibfk_1` FOREIGN KEY (`idso`) REFERENCES `salesorder` (`idso`),
  ADD CONSTRAINT `salesorderdetail_ibfk_2` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`);

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`idgrade`) REFERENCES `grade` (`idgrade`),
  ADD CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`);

--
-- Constraints for table `stocktakedetail`
--
ALTER TABLE `stocktakedetail`
  ADD CONSTRAINT `stocktakedetail_ibfk_1` FOREIGN KEY (`idst`) REFERENCES `stocktake` (`idst`),
  ADD CONSTRAINT `stocktakedetail_ibfk_2` FOREIGN KEY (`idgrade`) REFERENCES `grade` (`idgrade`),
  ADD CONSTRAINT `stocktakedetail_ibfk_3` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`);

--
-- Constraints for table `supplier`
--
ALTER TABLE `supplier`
  ADD CONSTRAINT `supplier_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `users` (`idusers`);

--
-- Constraints for table `tally`
--
ALTER TABLE `tally`
  ADD CONSTRAINT `tally_ibfk_1` FOREIGN KEY (`idso`) REFERENCES `salesorder` (`idso`),
  ADD CONSTRAINT `tally_ibfk_2` FOREIGN KEY (`idcustomer`) REFERENCES `customers` (`idcustomer`);

--
-- Constraints for table `tallydetail`
--
ALTER TABLE `tallydetail`
  ADD CONSTRAINT `tallydetail_ibfk_1` FOREIGN KEY (`idtally`) REFERENCES `tally` (`idtally`),
  ADD CONSTRAINT `tallydetail_ibfk_2` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`),
  ADD CONSTRAINT `tallydetail_ibfk_3` FOREIGN KEY (`idgrade`) REFERENCES `grade` (`idgrade`);

--
-- Constraints for table `trading`
--
ALTER TABLE `trading`
  ADD CONSTRAINT `trading_ibfk_1` FOREIGN KEY (`idbarang`) REFERENCES `barang` (`idbarang`),
  ADD CONSTRAINT `trading_ibfk_2` FOREIGN KEY (`iduser`) REFERENCES `users` (`idusers`),
  ADD CONSTRAINT `trading_ibfk_3` FOREIGN KEY (`idgrade`) REFERENCES `grade` (`idgrade`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE TABLE carcase (
  idcarcase INT AUTO_INCREMENT PRIMARY KEY,
  killdate DATE,
  idsupplier INT,
  note varchar (100),
  FOREIGN KEY (idsupplier) REFERENCES supplier (idsupplier)
);

CREATE TABLE carcasedetail (
  iddetail INT AUTO_INCREMENT PRIMARY KEY,
  idcarcase INT,
  breed VARCHAR(15),
  berat DECIMAL (6,2),
  eartag VARCHAR (5),
  carcase1 DECIMAL (5,2),
  carcase2 DECIMAL (5,2),
  hides DECIMAL (5,2),
  tail DECIMAL (5,2),
  FOREIGN KEY (idcarcase) REFERENCES carcase (idcarcase)
);

CREATE TABLE detailpcs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  idlabelboning INT,
  berat DECIMAL (6,2),
  FOREIGN KEY (idlabelboning) REFERENCES labelboning (idlabelboning)
);

CREATE TABLE request (
  idrequest INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  norequest VARCHAR(15) NOT NULL,
  duedate DATE NOT NULL,
  iduser INT NOT NULL,
  idsupplier INT,
  note VARCHAR (255),
  stat VARCHAR (10),
  is_deleted TIMESTAMP,
  xamount DECIMAL(15,2),
  taxrp DECIMAL(15,2),
  creatime timestamp NOT NULL DEFAULT current_timestamp(),
  FOREIGN KEY (iduser) REFERENCES users (idusers),
  FOREIGN KEY (idsupplier) REFERENCES supplier (idsupplier)
);

CREATE TABLE requestdetail (
  iddetail INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  idrequest INT NOT NULL,
  idrawmate INT NOT NULL,
  qty INT NOT NULL,
  price INT NOT NULL,
  notes VARCHAR(100),
  FOREIGN key (idrequest) REFERENCES request (idrequest),
  FOREIGN key (idrawmate) REFERENCES rawmate (idrawmate)
);