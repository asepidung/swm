<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";

// Ambil user id dari session (sesuaikan dengan auth.php kamu)
$idusers = $_SESSION['idusers'] ?? null;

function h($v)
{
   return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

// Tampilkan flash message sederhana via query string ?msg=...
if (isset($_GET['msg'])) {
   echo '<script>window.addEventListener("DOMContentLoaded",()=>{alert(' . json_encode($_GET['msg']) . ');});</script>';
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

   // Validasi parameter
   $kdbarcode = isset($_GET['kdbarcode']) ? trim($_GET['kdbarcode']) : '';
   $idtally   = isset($_GET['idtally']) ? (int)$_GET['idtally'] : 0;

   if ($kdbarcode === '' || $idtally <= 0) {
      header("Location: index.php?msg=Parameter%20tidak%20lengkap");
      exit;
   }

   // Prepared statement: pastikan ambil data SATU baris & sesuai tally
   $sql = "
        SELECT 
            td.*, b.nmbarang, g.nmgrade
        FROM tallydetail td
        LEFT JOIN barang b ON td.idbarang = b.idbarang
        LEFT JOIN grade  g ON td.idgrade  = g.idgrade
        WHERE td.barcode = ? AND td.idtally = ?
        LIMIT 1
    ";
   if (!$stmt = $conn->prepare($sql)) {
      // Hindari bocor info DB ke user produksi
      die("DB Error (prepare)");
   }
   $stmt->bind_param('si', $kdbarcode, $idtally);
   if (!$stmt->execute()) {
      die("DB Error (execute)");
   }
   $result = $stmt->get_result();

   if ($result->num_rows === 0) {
      // Redirect rapi dengan pesan
      header("Location: index.php?msg=Data%20Barang%20Tidak%20Ditemukan");
      exit;
   }

   $row = $result->fetch_assoc();
   // Siapkan nilai yang aman untuk ditampilkan
   $idtallydetail = (int)$row['idtallydetail'];
   $idbarang      = (int)$row['idbarang'];
   $idgrade       = (int)$row['idgrade'];
   $nmbarang      = $row['nmbarang'] ?? '';
   $nmgrade       = $row['nmgrade'] ?? '';
   $pod           = $row['pod'] ?? '';      // tanggal produksi/pack on date?
   $weight        = $row['weight'] ?? '';
   $pcs           = $row['pcs'] ?? '';
?>
   <div class="content-wrapper">
      <div class="content">
         <div class="container-fluid">
            <div class="row">
               <div class="col-lg-4 mt-2">
                  <div class="card">
                     <div class="card-body">
                        <form method="POST" action="cetakrelabel.php" id="formRelabel">
                           <input type="hidden" name="idtally" value="<?= $idtally ?>">
                           <input type="hidden" name="idtallydetail" value="<?= $idtallydetail ?>">

                           <div class="form-group">
                              <div class="input-group">
                                 <input type="hidden" name="idbarang" value="<?= $idbarang ?>">
                                 <input type="text" class="form-control" value="<?= h($nmbarang) ?>" readonly>
                              </div>
                           </div>

                           <div class="form-group">
                              <div class="input-group">
                                 <input type="hidden" name="idgrade" value="<?= $idgrade ?>">
                                 <input type="text" class="form-control" value="<?= h($nmgrade) ?>" readonly>
                              </div>
                           </div>

                           <div class="form-group">
                              <div class="input-group">
                                 <input type="hidden" name="xpackdate" id="xpackdate" value="<?= h($pod) ?>">
                                 <input type="date" class="form-control" name="packdate" id="packdate" required value="<?= h($pod) ?>">
                              </div>
                           </div>

                           <div class="form-group">
                              <div class="input-group">
                                 <input type="date" class="form-control" name="exp" id="exp">
                              </div>
                           </div>

                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="tenderstreach" id="tenderstreach" value="1">
                              <label class="form-check-label" for="tenderstreach">Aktifkan Tenderstreatch</label>
                           </div>

                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="pembulatan" id="pembulatan" value="1">
                              <label class="form-check-label" for="pembulatan">1 Digit Koma</label>
                           </div>

                           <input type="hidden" name="idusers" id="idusers" value="<?= h($idusers) ?>">
                           <input type="hidden" name="kdbarcode" id="kdbarcode" value="<?= h($kdbarcode) ?>">

                           <div class="form-group mt-2">
                              <div class="row">
                                 <div class="col-8">
                                    <input type="text" class="form-control" name="qty" value="<?= h($weight) ?>" readonly>
                                 </div>
                                 <div class="col">
                                    <input type="hidden" name="xpcs" value="<?= h($pcs) ?>">
                                    <input type="number" name="pcs" class="form-control" value="<?= h($pcs) ?>" min="0" step="1">
                                 </div>
                              </div>
                           </div>

                           <button type="submit" class="btn btn-block bg-gradient-primary" name="submit">Print</button>
                        </form>
                     </div>
                  </div>
                  <!-- /.card -->
               </div>
            </div>
         </div>
      </div>
   </div>

   <script>
      // OPTIONAL: hitung EXP berdasarkan packdate + umur simpan (kalau ada).
      // Jika umur simpan ada di server, lebih baik hitung di server.
      // Berikut contoh placeholder JS (komentari kalau belum dipakai):
      // const shelfLifeDays = null; // misal ambil dari server
      // function updateExp() {
      //   const pd = document.getElementById('packdate').value;
      //   if (!pd || !shelfLifeDays) { document.getElementById('exp').value = ""; return; }
      //   const d = new Date(pd);
      //   d.setDate(d.getDate() + shelfLifeDays);
      //   document.getElementById('exp').value = d.toISOString().slice(0,10);
      // }
      // document.getElementById('packdate').addEventListener('change', updateExp);
      // updateExp();
   </script>

<?php
} else {
   echo "Form tidak dikirimkan.";
}

require "../footer.php";
