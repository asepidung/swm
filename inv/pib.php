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
$query_invoice = "SELECT * FROM invoice WHERE idinvoice = '$idinvoice'";
$result_invoice = mysqli_query($conn, $query_invoice);
$row_invoice = mysqli_fetch_assoc($result_invoice);

// Tampilkan data dari tabel invoicedetail
$query_invoicedetail = "SELECT * FROM invoicedetail WHERE idinvoice = '$idinvoice'";
$result_invoicedetail = mysqli_query($conn, $query_invoicedetail);

$balance = $row_invoice['balance'];
$terbilang = terbilang($balance);
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $row_invoice['noinvoice']; ?></title>
   <style>
      body {
         font-family: Cambria, sans-serif;
         font-size: 14px;
      }

      /* Styling for table with border-collapse */
      .tablecollapse {
         border-collapse: collapse;
         width: 100%;
      }

      /* Styling for table headers (th) */
      .thcollapse {
         border: 1px solid black;
         padding: 4px;
      }

      /* Styling for data cells (td) with border */
      .tdcollapse {
         border: 1px solid black;
         padding: 2px;
      }

      /* Styling for data cells (td) without border */
      .noborder {
         border: none;
         padding: 4px;
      }

      /* Styling for h1 and h2 elements */
      .h1tea {
         margin: 5px 0 10px 0;
      }

      .h2tea {
         margin: 5px 0 0 0;
      }

      .pad {
         padding: 2px;
      }
   </style>
</head>

<body>
   <!-- Header -->
   <img src="../dist/img/hib.png" alt="headerinvoice" width="100%">

   <!-- Invoice Number -->
   <h4 class="h1tea" align="right"><?= $row_invoice['noinvoice']; ?></h4>

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
         <td width="12%">Do Date</td>
         <td width="2%" align="right">:</td>
         <td width="30%"><?= date('d-M-Y', strtotime($row_invoice['deliverydate'])); ?></td>
         <td width="12%">Bill To</td>
         <td width="2%" align="right">:</td>
         <td width="30%"><?= $row_invoice['idcustomer']; ?></td>
      </tr>
      <tr>
         <td width="12%">Terms</td>
         <td width="2%" align="right">:</td>
         <td width="30%"><?= $row_invoice['top']; ?> Days</td>
         <td width="12%" valign="top" rowspan="5">Address</td>
         <td width="2%" align="right" valign="top" rowspan="5">:</td>
         <td width="30%" valign="top" align="justify" rowspan="5"> <?= $row_invoice['alamat']; ?></td>
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

   <!-- Table 2 (Invoice Details) -->
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
            <td class="tdcollapse" align="left"><?= $row_invoicedetail['idbarang']; ?></td>
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
         <?php if ($row_invoice['tax'] > 1) { ?>
            <tr class="noborder">
               <td colspan="6" align="right">Tax 11% :</td>
               <th class="pad" align="right"><?= number_format($row_invoice['tax'], 2); ?></th>
            </tr>
         <?php } ?>
         <?php if ($row_invoice['charge'] > 1) { ?>
            <tr class="noborder">
               <td colspan="6" align="right">Charge :</td>
               <th class="pad" align="right"><?= number_format($row_invoice['charge'], 2); ?></th>
            </tr>
         <?php } ?>
         <?php if ($row_invoice['downpayment'] > 1) { ?>
            <tr class="noborder">
               <td colspan="6" align="right">DownPayment :</td>
               <th class="pad" align="right"><?= number_format($row_invoice['downpayment'], 2); ?></th>
            </tr>
         <?php } ?>
         <tr class="noborder">
            <td colspan="6" align="right">Balance :</td>
            <th class="pad" align="right"><?= number_format($row_invoice['balance'], 2); ?></th>
         </tr>
      </tfoot>
   </table>

   <table class="h2tea" width="50%">
      <tr>
         <td>Says :</td>
      </tr>
      <tr>
         <td align="justify" class="tdcollapse">
            <b><i><?= terbilang($row_invoice['balance']) ?></i></b>
         </td>
      </tr>
   </table>
   <div class="h2tea">Payment Methods</div>
   <table width="100%">
      <tr>
         <td colspan="4">BCA (BANK CENTRAL ASIA)</td>
         <td valign="top" align="center" rowspan="2">
            FINANCE
         </td>
      </tr>
      <tr>
         <td width="20%">ACC Name</td>
         <td width="5%">:</td>
         <td width="25%">SANTI WIJAYA L</td>
         <td width="25%"></td>
      </tr>
      <tr>
         <td width="20%">ACC. NUMBER</td>
         <td width="5%">:</td>
         <td width="25%">7115407007</td>
         <td></td>
      </tr>
      <tr>
         <td colspan="4"></td>
         <td>
         <td>
      </tr>
      <tr>
         <td colspan="4"></td>
         <td valign="bottom" align="center" width="25%">....................................</td>
      </tr>
   </table>

</body>

</html>