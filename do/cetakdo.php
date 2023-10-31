<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";

$iddo = $_GET['iddo'];
// Query untuk mengambil data dari tabel do
$query = "SELECT do.*, customers.nama_customer, users.fullname
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
      font-family: Cambria, sans-serif;
      font-size: 14px;
    }

    .border-collapse {
      border-collapse: collapse;
    }

    .half-width {
      width: 50%;
    }

    .small-text {
      font-size: 12px;
    }
  </style>
</head>

<body>
  <p>Delivery Order<br />
    <strong>PT. SANTI WIJAYA MEAT</strong><br />
    Jl. Perum Asabri Blok B Desa Sukasirna Kec. Jonggol Kab. Bogor Telp. 021-89935103
  </p>
  <hr />
  <table width="100%">
    <tr>
      <td width="12%">Do Number</td>
      <td width="2%" align="right">:</td>
      <td width="30%"><?= $row_do['donumber']; ?></td>
      <td width="12%">Delivery Date</td>
      <td width="2%" align="right">:</td>
      <td width="30%"><?= date('d-M-Y', strtotime($row_do['deliverydate'])); ?></td>
    </tr>
    <tr>
      <td width="12%">So Number</td>
      <td width="2%" align="right">:</td>
      <td width="30%"><?= $row_do['sonumber']; ?></td>
      <td width="12%">PO Number</td>
      <td width="2%" align="right">:</td>
      <td width="30%"><?= $row_do['po']; ?></td>
    </tr>
    <tr>
      <td width="12%">Sales Ref</td>
      <td width="2%" align="right">:</td>
      <td width="30%"> Muryani</td>
      <td width="12%">Customer</td>
      <td width="2%" align="right">:</td>
      <td width="30%"><?= $row_do['nama_customer']; ?></td>
    </tr>
    <tr>
      <td width="12%">Driver</td>
      <td width="2%" align="right">:</td>
      <td width="30%"> <?= $row_do['driver']; ?></td>
      <td class="border-collapse" width="12%" valign="top">Address</td>
      <td class="border-collapse" width="2%" valign="top" align="right">:</td>
      <td width="30%" align="justify" valign="top"><?= $row_do['alamat']; ?></td>
    </tr>
    <tr>
      <td width="12%">No POL</td>
      <td width="2%" align="right">:</td>
      <td width="30%"> <?= $row_do['plat']; ?></td>
      <td></td>
      <td></td>
    </tr>
    <tr height="30px">
      <td width="12%"></td>
      <td width="2%"></td>
      <td width="30%"></td>
      <td width="12%"></td>
      <td width="2%"></td>
    </tr>
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
      <td width="20%">Warehouse <br><br><br><br><br> ....................................</td>
      <td width="20%">QC/QA <br><br><br><br><br> ....................................</td>
      <td width="20%">Driver <br><br><br><br><br> ....................................</td>
      <td width="20%">Security <br><br><br><br><br> ....................................</td>
      <td width="20%">Customer <br><br><br><br><br> ....................................</td>
    </tr>
  </table>
  <br>
  <p class="small-text" align="right">
    Made By <?= $row_do['fullname'] . " " . "at" . " " . date("d/M/y H:m:s", strtotime($row_do['created'])) ?>
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