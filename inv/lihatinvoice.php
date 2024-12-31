<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: verifications/login.php");
}
require "../konak/conn.php";
require "terbilang.php";
$idinvoice = $_GET['idinvoice'];
$idusers = $_SESSION['idusers'];

// Tampilkan data dari tabel invoice
$query_invoice = "SELECT invoice.*, doreceipt.deliverydate, customers.nama_customer, customers.alamat1, segment.banksegment, segment.accname, segment.accnumber 
                  FROM invoice 
                  INNER JOIN doreceipt ON invoice.iddoreceipt = doreceipt.iddoreceipt 
                  INNER JOIN customers ON invoice.idcustomer = customers.idcustomer 
                  INNER JOIN segment ON customers.idsegment = segment.idsegment
                  WHERE invoice.idinvoice = '$idinvoice'";

$result_invoice = mysqli_query($conn, $query_invoice);
$row_invoice = mysqli_fetch_assoc($result_invoice);

// Tampilkan data dari tabel invoicedetail
$query_invoicedetail = "SELECT invoicedetail.*, barang.nmbarang 
                        FROM invoicedetail 
                        INNER JOIN barang ON invoicedetail.idbarang = barang.idbarang 
                        WHERE idinvoice = '$idinvoice'";
$result_invoicedetail = mysqli_query($conn, $query_invoicedetail);

$balance = $row_invoice['balance'];
$terbilang = terbilang($balance);

// Mendapatkan nilai dari tabel segment berdasarkan nmcustomer
$banksegment = $row_invoice['banksegment'];
$accname = $row_invoice['accname'];
$accnumber = $row_invoice['accnumber'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $row_invoice['nama_customer'] . " " . $row_invoice['noinvoice']; ?></title>
   <link rel="icon" href="../dist/img/favicon.png" type="image/x-icon">
   <style>
      body {
         font-family: Cambria, sans-serif;
         font-size: 14px;
      }

      tfoot {
         padding: 20 0 0 0;
      }

      .floatingButtonContainer {
         position: fixed;
         bottom: 20px;
         left: 50%;
         transform: translateX(-50%);
         z-index: 9999;
      }

      .floatingButton {
         background-color: #f0ad4e;
         color: #fff;
         padding: 12px 20px;
         border: none;
         border-radius: 5px;
         box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
         font-size: 16px;
         cursor: pointer;
      }

      .floatingButton:hover {
         background-color: #e69537;
      }

      @media print {
         .floatingButton {
            display: none;
         }
      }

      .noinvoice {
         font-size: 14px;
         font-weight: bold;
         margin-top: -2px;
         margin-bottom: -5px;
      }

      .tableContainer {
         padding: 20 0 0 0;
      }

      .tablecollapse {
         border-collapse: collapse;
         width: 100%;
      }

      .thcollapse {
         border: 1px solid black;
         padding: 4px;
      }

      .tdcollapse {
         border: 1px solid black;
         padding: 4px;
      }

      .noborder {
         border: none;
         padding: 4px;
      }

      .h1tea {
         margin: 5px 0 10px 0;
      }

      .mt {
         margin: 40px 0 0 0;
      }

      .h2tea {
         margin: 5px 0 0 0;
      }

      .pad {
         padding: 2px;
      }

      .pad1 {
         padding: 15px 0 15px 0;
      }

      .bggelap {
         background-color: #C1C1C1;
      }
   </style>
</head>

<body>
   <img src="../dist/img/hic.png" alt="headerinvoice" width="100%">
   <!-- Invoice Number -->
   <p class="noinvoice" align="right"><?= $row_invoice['noinvoice']; ?></p>

   <!-- Table 1 (Information) -->
   <table width="100%">
      <tr>
         <td width="12%">Do Number</td>
         <td width="2%" align="right">:</td>
         <td width="30%"><?= $row_invoice['donumber']; ?></td>
         <td width="12%">Invoice Date</td>
         <td width="2%" align="right">:</td>
         <td width="30%"><?= date('d-M-Y', strtotime($row_invoice['invoice_date'])); ?></td>
      </tr>
      <tr>
         <td width="12%">Delivery Date</td>
         <td width="2%" align="right">:</td>
         <td width="30%"><?= date('d-M-Y', strtotime($row_invoice['deliverydate'])); ?></td>
         <td width="12%">Bill To</td>
         <td width="2%" align="right">:</td>
         <td width="30%"><?= $row_invoice['nama_customer']; ?></td>
      </tr>
      <tr>
         <td width="12%">Terms</td>
         <td width="2%" align="right">:</td>
         <td width="30%"><?= $row_invoice['top']; ?> Days</td>
         <td width="12%" valign="top" rowspan="5">Address</td>
         <td width="2%" align="right" valign="top" rowspan="5">:</td>
         <td width="30%" valign="top" align="justify" rowspan="5"> <?= $row_invoice['alamat1']; ?></td>
      </tr>
      <tr>
         <td width="12%">Duedate</td>
         <td width="2%" align="right">:</td>
         <td width="30%"><?= date('d-M-Y', strtotime($row_invoice['duedate'])); ?></td>
      </tr>
      <tr>
         <td width="12%">Sales Ref</td>
         <td width="2%" align="right">:</td>
         <td width="30%">Muryani</td>
      </tr>
      <tr>
         <td width="12%">Cust PO</td>
         <td width="2%" align="right">:</td>
         <td width="30%"><?= $row_invoice['pocustomer']; ?></td>
      </tr>
   </table>
   <div class="tableContainer">
      <table class="tablecollapse" width="100%">
         <tr>
            <th class="tdcollapse">#</th>
            <th class="tdcollapse">Prod Descriptions</th>
            <th class="tdcollapse">Weight</th>
            <th class="tdcollapse">Price</th>
            <th class="tdcollapse">Disc %</th>
            <th class="tdcollapse">Disc Rp</th>
            <th class="tdcollapse">Total</th>
         </tr>
         <?php
         $no = 1;
         while ($row_invoicedetail = mysqli_fetch_assoc($result_invoicedetail)) { ?>
            <tr align="right">
               <td class="tdcollapse" align="center"><?= $no; ?></td>
               <td class="tdcollapse" align="left"><?= $row_invoicedetail['nmbarang']; ?></td>
               <td class="tdcollapse"><?= number_format($row_invoicedetail['weight'], 2); ?></td>
               <td class="tdcollapse"><?= number_format($row_invoicedetail['price'], 2); ?></td>
               <td class="tdcollapse" align="center"><?= $row_invoicedetail['discount']; ?></td>
               <td class="tdcollapse"><?= number_format($row_invoicedetail['discountrp'], 2); ?></td>
               <td class="tdcollapse"><?= number_format($row_invoicedetail['amount'], 2); ?></td>
            </tr>
         <?php $no++;
         } ?>
         <tfoot>
            <tr>
               <th colspan="3" align="right"><?= number_format($row_invoice['xweight'], 2); ?></th>
               <td colspan="3" align="right">Grand Total :</td>
               <th class="noborder pad" align="right"><?= number_format($row_invoice['xamount'], 2); ?></th>
            </tr>
            <tr class="noborder">
               <td colspan="6" align="right">Tax 11% :</td>
               <th class="pad" align="right"><?= number_format($row_invoice['tax'], 2); ?></th>
            </tr>
            <tr class="noborder">
               <td colspan="6" align="right">Charge :</td>
               <th class="pad" align="right"><?= number_format($row_invoice['charge'], 2); ?></th>
            </tr>
            <tr class="noborder">
               <td colspan="6" align="right">DownPayment :</td>
               <th class="pad" align="right"><?= number_format($row_invoice['downpayment'], 2); ?></th>
            </tr>
            <tr class="noborder">
               <td colspan="6" align="right">Balance :</td>
               <th class="pad" align="right"><?= number_format($row_invoice['balance'], 2); ?></th>
            </tr>
         </tfoot>
      </table>
   </div>
   <br>
   <table class="h2tea tablecollapse">
      <!-- <tr class="tablecollapse">
         <td align="right">Says :</td>
      </tr> -->
      <tr>
         <td width="50%"></td>
         <td scope="col" align="right">
            <b class="bggelap">
               Says :
               <i>
                  <?= terbilang($row_invoice['balance']) . " " . "Rupiah" ?>
               </i>
            </b>
         </td>
         <td></td>
      </tr>
      <?php if ($row_invoice['note'] !== "") { ?>
         <tr class="pad1">
            <td align="justify">
               <i>Note :<br> <b><?= $row_invoice['note'] ?></b></i>
            </td>
         </tr>
      <?php } ?>
   </table>
   <br>
   <div class=" h2tea">Payment Methods</div>
   <table width="100%">
      <tr>
         <td colspan="4"><?= $banksegment; ?></td>
         <td valign="top" align="center" rowspan="2">
            F I N A N C E
         </td>
      </tr>
      <tr>
         <td width="20%">ACC Name</td>
         <td width="5%">:</td>
         <td width="25%"><strong><?= $accname; ?></strong></td>
         <td width="25%"></td>
      </tr>
      <tr>
         <td width="20%">ACC. NUMBER</td>
         <td width="5%">:</td>
         <td width="25%"><strong><?= $accnumber; ?></strong></td>
         <td></td>
      </tr>
      <tr>
         <td colspan="4"></td>
         <td valign="bottom" align="center" width="25%"><br><br>....................................</td>
      </tr>
   </table>
  
   <div class="floatingButtonContainer">
      <button class="floatingButton" onclick="window.history.back();">Kembali</button>
      <button class="floatingButton" onclick="window.print();">Print</button>
   </div>

</body>

</html>