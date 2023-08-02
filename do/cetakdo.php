<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";

$iddo = $_GET['iddo'];
// Query untuk mengambil data dari tabel do
$query = "SELECT do.*, customers.nama_customer, customers.alamat, users.userid
          FROM do 
          INNER JOIN customers ON do.idcustomer = customers.idcustomer 
          INNER JOIN users ON do.idusers = users.idusers
          WHERE do.iddo = '$iddo'";
$result = mysqli_query($conn, $query);
$row_do = mysqli_fetch_assoc($result);


// Query untuk mengambil data dari tabel dodetail berdasarkan iddo
$query_detail = "SELECT dodetail.*, barang.kdbarang, barang.nmbarang, grade.nmgrade
                FROM dodetail
                INNER JOIN grade ON dodetail.idgrade = grade.idgrade
                INNER JOIN barang ON dodetail.idbarang = barang.idbarang
                WHERE dodetail.iddo = '$iddo'";
$result_detail = mysqli_query($conn, $query_detail);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    .data {
      padding-right: 5px;
      padding-left: 5px;
      /* Ubah angka sesuai kebutuhan Anda */
    }

    body {
      background-color: white;
      color: black;
      font-family: 'poppins', 'cambria';
      font-kerning: normal;
      font-size: 14px;
    }

    .border-collapse {
      border-collapse: collapse;
    }

    .half-width {
      width: 50%;
    }

    .small-text {
      font-size: 10px;
    }
  </style>
</head>

<body>
  <p>Delivery Order<br />
    <strong>PT. SANTI WIJAYA MEAT</strong><br />
    Jl. Perum Asabri Blok B Desa Sukasirna Kec. Jonggol Kab. Bogor Telp. 021-89935103
  </p>
  <hr />
  <table cellpadding="0" cellspacing="0">
    <tbody>
      <tr>
        <td style="width:137px">DO Numb</td>
        <td style="width:383px">: <?= $row_do['donumber']; ?></td>
        <td style="width:155px">Delivery Date</td>
        <td style="width:513px">: <?= date('D, d-M-Y', strtotime($row_do['deliverydate'])); ?></td>
      </tr>
      <tr>
        <td style="width:137px">SO NUmb</td>
        <td style="height:0px; width:383px">: -</td>
        <td style="height:0px; width:155px">PO Number</td>
        <td style="height:0px; width:513px">: <?= $row_do['po']; ?></td>
      </tr>
      <tr>
        <td style="width:137px">Sales Ref</td>
        <td style="width:383px">: MURYANI</td>
        <td style="width:155px">Customer</td>
        <td style="width:513px">: <?= $row_do['nama_customer']; ?></td>
      </tr>
      <tr>
        <td style="width:137px">Driver</td>
        <td style="width:383px">: TOPIK</td>
        <td style="width:155px">Address</td>
        <td colspan="1" rowspan="3" style="width:513px" valign="top">: <?= $row_do['alamat']; ?></td>
      </tr>
      <tr>
        <td style="width:137px">No POL</td>
        <td style="width:383px">: F 2111 FAB</td>
        <td style="width:155px">&nbsp;</td>
      </tr>
    </tbody>
  </table>
  <br>
  <table width="100%" border="1" cellpadding="2" class="border-collapse">
    <tr>
      <th width="5%">No</th>
      <th width="10%">Code</th>
      <th width="30%">Item Descriptions</th>
      <th width="10%">Box</th>
      <th width="15%">Weight</th>
      <th width="30%">Notes</th>
    </tr>
    <?php
    $no = 1; // Inisialisasi nomor urut
    while ($row_detail = mysqli_fetch_assoc($result_detail)) {
    ?>
      <tr align="center">
        <td><?= $no ?></td>
        <td><?= $row_detail['nmgrade'] . $row_detail['kdbarang']; ?></td>
        <td align="left"><span class="data"><?= $row_detail['nmbarang']; ?></span></td>
        <td><?= $row_detail['box']; ?></td>
        <td align="right"><span class="data"><?= number_format($row_detail['weight'], 2); ?></span></td>
        <td align="left"><span class="data"><?= $row_detail['notes']; ?></span></td>
      </tr>
    <?php
      $no++; // Increment nomor urut
    }
    ?>
    <tr align="right">
      <th colspan="3">Total</th>
      <th align="center"><?= number_format($row_do['xbox']); ?></th>
      <th><span class="data"><?= number_format($row_do['xweight'], 2); ?></span></th>
      <th></th>
    </tr>
  </table>
  <i>
    <p align="justify" class="half-width">
      <strong>Note !</strong><br>
      <?php
      if ($row_do['note'] !== "") {
        echo $row_do['note'];
      } else {
        echo "-";
      }
      ?>
    </p>
  </i>
  <table width="100%">
    <tr align="center">
      <td width="25%">Warehouse <br><br><br><br> ....................................</td>
      <td width="25%">Driver <br><br><br><br> ....................................</td>
      <td width="25%">Security <br><br><br><br> ....................................</td>
      <td width="25%">Customer <br><br><br><br> ....................................</td>
    </tr>
  </table>
  <br>
  <p class="small-text">
    Made By <?= $row_do['userid'] . " " . "at" . " " . date("d/M/y H:m:s", strtotime($row_do['created'])) ?>
  </p>
  <script>
    document.title = "<?php echo $row_do['donumber']; ?>";
    window.addEventListener("load", function() {
      window.print();

      // Redirect ke halaman do.php setelah 3 detik
      setTimeout(function() {
        window.location.href = "do.php";
      }, 3000); // Ubah angka ini sesuai dengan durasi yang diinginkan (dalam milidetik)
    });
  </script>
</body>

</html>