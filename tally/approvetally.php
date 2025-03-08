<?php
require "../verifications/auth.php";
require "../konak/conn.php";

if (isset($_GET['id']) && isset($_GET['idso'])) {
   $idtally = intval($_GET['id']);
   $idso = intval($_GET['idso']);
   $iduser = $_SESSION['idusers']; // Mengambil iduser dari session

   // Cek status di tabel tally sebelum melanjutkan
   $stmtCheck = $conn->prepare("SELECT stat, notally FROM tally WHERE idtally = ?");
   if (!$stmtCheck) {
      die("Error: Prepare failed (" . $conn->errno . ") " . $conn->error);
   }
   $stmtCheck->bind_param("i", $idtally);
   $stmtCheck->execute();
   $resultCheck = $stmtCheck->get_result();
   $rowCheck = $resultCheck->fetch_assoc();

   // Jika status sudah "Approved" atau "DO", hentikan eksekusi dan redirect
   if ($rowCheck['stat'] === "Approved" || $rowCheck['stat'] === "DO") {
      header("location: index.php?message=status_invalid");
      exit();
   }

   // Simpan notally untuk digunakan dalam log
   $notally = $rowCheck['notally'];

   if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
      $sealnumb = !empty($_POST['sealnumb']) ? htmlspecialchars($_POST['sealnumb'], ENT_QUOTES, 'UTF-8') : NULL;

      // Mulai transaksi database
      $conn->autocommit(false);

      try {
         // Update status di tabel tally
         $stmt = $conn->prepare("UPDATE tally SET stat = 'Approved', sealnumb = ? WHERE idtally = ?");
         if (!$stmt) {
            throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
         }
         $stmt->bind_param("si", $sealnumb, $idtally);
         if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
         }

         // Update progress di tabel salesorder
         $stmt_so = $conn->prepare("UPDATE salesorder SET progress = 'DRAFT' WHERE idso = ?");
         if (!$stmt_so) {
            throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
         }
         $stmt_so->bind_param("i", $idso);
         if (!$stmt_so->execute()) {
            throw new Exception("Execute failed: " . $stmt_so->error);
         }

         // Simpan log aktivitas
         $event = "Approved Tally";
         $stmtLogActivity = $conn->prepare("INSERT INTO logactivity (iduser, event, docnumb) VALUES (?, ?, ?)");
         if (!$stmtLogActivity) {
            throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
         }
         $stmtLogActivity->bind_param('iss', $iduser, $event, $notally);
         if (!$stmtLogActivity->execute()) {
            throw new Exception("Execute failed: " . $stmtLogActivity->error);
         }

         // Commit transaksi jika semua query berhasil
         $conn->commit();

         // Redirect ke halaman index.php dengan pesan sukses
         header("location: index.php?message=success");
         exit();
      } catch (Exception $e) {
         // Rollback jika terjadi kesalahan
         $conn->rollback();
         echo "Terjadi kesalahan: " . $e->getMessage();
         exit();
      } finally {
         // Kembalikan autocommit ke true
         $conn->autocommit(true);
      }
   } else {
?>
      <!DOCTYPE html>
      <html>

      <head>
         <title>Approve Tally</title>
         <script>
            function showPopup() {
               document.getElementById('popupForm').style.display = 'block';
            }

            function hidePopup() {
               document.getElementById('popupForm').style.display = 'none';
            }
         </script>
         <style>
            #popupForm {
               display: none;
               position: fixed;
               top: 50%;
               left: 50%;
               transform: translate(-50%, -50%);
               border: 1px solid #ccc;
               padding: 20px;
               background: #fff;
               z-index: 1000;
            }

            #popupFormOverlay {
               position: fixed;
               top: 0;
               left: 0;
               width: 100%;
               height: 100%;
               background: rgba(0, 0, 0, 0.5);
               z-index: 999;
            }
         </style>
      </head>

      <body onload="showPopup()">
         <div id="popupFormOverlay"></div>
         <div id="popupForm">
            <form method="post">
               <label for="sealnumb">Isi Nomor Segel (Jika Ada)</label><br>
               <input type="text" id="sealnumb" name="sealnumb"><br><br>
               <button type="submit" name="submit" class="btn btn-sm btn-primary">Submit</button>
               <button type="button" onclick="hidePopup()">Cancel</button>
            </form>
         </div>
      </body>

      </html>
<?php
      exit();
   }
} else {
   header("location: index.php?message=invalid_id");
   exit();
}

$conn->close();
?>