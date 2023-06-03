<?php
require "../konak/conn.php";
if (isset($_GET['submit'])) {
  // Query untuk mendapatkan nama barang
  $product = $_GET['product'];
  $query = "SELECT nmbarang FROM barang WHERE idbarang = $product";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  $nmbarang = $row['nmbarang'];
  $exp = isset($_GET['exp']) && !empty($_GET['exp']) ? date('d-M-Y', strtotime($_GET['exp'])) : null;
  $packdate = date('d-M-Y', strtotime($_GET['packdate']));
  $kdbarcode = $_GET['kdbarcode'];
  // Memeriksa dan memecah nilai qty dan pcs
  $qty = null;
  $pcs = null;
  $qtyPcsInput = $_GET['qty'];

  // Mengecek apakah input qty dan pcs terisi (misalnya "1250.25/4")
  if (strpos($qtyPcsInput, "/") !== false) {
    list($qty, $pcs) = explode("/", $qtyPcsInput . "-Pcs");
  } else {
    $qty = $qtyPcsInput;
  }
  // Memformat qty menjadi 2 digit desimal di belakang koma
  $qty = number_format($qty, 2);
}
?>
<table width="380" border="0" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="114" style="width: 112px">
        <span style="font-size: 18px; color: #000000; font-family: Tahoma, Geneva, sans-serif">
          <strong>*YP*</strong>
        </span>
      </td>
      <td width="149" style="width: 149px">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" style="width: 264px">
        <span style="color: #000000; font-family: Tahoma, Geneva, sans-serif">
          <strong>Prod By: PT. SANTI WIJAYA MEAT</strong>
        </span>
      </td>
    </tr>
    <tr>
      <td colspan="4" style="white-space: nowrap; width: 373px">
        <p style="color: #000000; font-family: Tahoma, Geneva, sans-serif; font-size: 12px;">
          Jl. Perum Asabri Blok B No 20 Rt. 01/05 Ds. Sukasirna<br>Kec. Jonggol Kab. Bogor
        </p>
      </td>
    </tr>
    <tr>
      <td height="38" colspan="2" style="width: 264px">
        <span style="font-size: 22px; color: #000000; font-family: Tahoma, Geneva, sans-serif">
          <strong><?= $nmbarang; ?></strong>
        </span>
      </td>
      <td colspan="2" rowspan="5" align="left"><img src="../assets/dist/img/hi.svg" alt="HALAL" width="70"></td>
    </tr>
    <tr>
      <td colspan="1" rowspan="2" style="width: 112px">
        <span style="color: #000000; font-family: Tahoma, Geneva, sans-serif">
          <span style="font-size: 24px"><strong><?= $qty; ?></strong></span>
        </span>
      </td>
      <td style="width: 149px">
        <p>
          <strong><i><?= $pcs; ?></i></strong>
        </p>
      </td>
    </tr>
    <tr>
      <td height="27" style="width: 149px; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif; font-style: normal; font-size: 14px;"><strong>&#9733; <i>Tenderstreatch</i> &#x2605;</strong></td>
    </tr>
    <tr>
      <td style="font-size: 12px">
        <span style="color: #000000; font-family: Tahoma, Geneva, sans-serif">Packed Date&nbsp; :</span>
      </td>
      <td style="font-size: 12px">
        <span style="color: #000000; font-family: Tahoma, Geneva, sans-serif"><?= $packdate; ?></span>
      </td>
    </tr>
    <?php if ($exp !== null) : ?>
      <tr>
        <td height="17" style="font-size: 12px">
          <span style="color: #000000; font-family: Tahoma, Geneva, sans-serif">Expired Date :</span>
        </td>
        <td style="font-size: 12px">
          <span style="color: #000000; font-family: Tahoma, Geneva, sans-serif"><?= $exp; ?></span>
        </td>
      </tr>
    <?php endif; ?>
    <tr>
      <td height="29" colspan="2" rowspan="1" style="width: 264px">
        <span style="color: #000000; font-family: Tahoma, Geneva, sans-serif">
          <strong>KEEP CHILL/FROZEN</strong>
        </span>
      </td>
      <td colspan="2" rowspan="1" style="text-align: left; width: 114px">
        <span style="font-size: 11px; color: #000000; font-family: Tahoma, Geneva, sans-serif">
          No. 01011263450821<br />
          NKV CS-3201170-027
        </span>
      </td>
    </tr>
    <tr>
      <td colspan="4" style="text-align: center; width: 373px"><span style="color: #000000; font-size: 36px; font-family: '3 of 9 Barcode'"><?= "*" . $kdbarcode . "*"; ?></span></td>
    </tr>
    <tr>
      <td colspan="4" style="text-align: center; width: 373px">
        <span style="color: #000000; font-size: 18px; font-family: Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif">
          <?= $kdbarcode; ?></span>
      </td>
    </tr>
  </tbody>
</table>