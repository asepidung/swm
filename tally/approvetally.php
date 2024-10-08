<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
}

require "../konak/conn.php";

if (isset($_GET['id'])) {
   $idtally = intval($_GET['id']);
   $idso = intval($_GET['idso']);
   $iduser = $_SESSION['idusers']; // Mengambil iduser dari session

   // Jika form sudah disubmit, lanjutkan eksekusi PHP
   if (isset($_POST['submit'])) {
      $sealnumb = !empty($_POST['sealnumb']) ? $_POST['sealnumb'] : NULL;

      // Mulai transaksi
      $conn->autocommit(false);

      try {
         // Prepare statement untuk update tabel tally
         $stmt = $conn->prepare("UPDATE tally SET stat = ?, sealnumb = ? WHERE idtally = ?");
         if (!$stmt) {
            throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
         }

         $status = "Approved";
         $stmt->bind_param("ssi", $status, $sealnumb, $idtally);

         if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
         }

         // Dapatkan nomor tally (notally)
         $queryNotally = "SELECT notally FROM tally WHERE idtally = ?";
         $stmtNotally = $conn->prepare($queryNotally);
         if (!$stmtNotally) {
            throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
         }

         $stmtNotally->bind_param("i", $idtally);
         $stmtNotally->execute();
         $resultNotally = $stmtNotally->get_result();
         $rowNotally = $resultNotally->fetch_assoc();
         $notally = $rowNotally['notally'];

         // Prepare statement untuk update tabel salesorder
         $stmt_so = $conn->prepare("UPDATE salesorder SET progress = 'DRAFT' WHERE idso = ?");
         if (!$stmt_so) {
            throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
         }

         $stmt_so->bind_param("i", $idso);

         if (!$stmt_so->execute()) {
            throw new Exception("Execute failed: " . $stmt_so->error);
         }

         // Insert log activity ke tabel logactivity
         $event = "Approved Tally";
         $queryLogActivity = "INSERT INTO logactivity (iduser, event, docnumb) VALUES (?, ?, ?)";
         $stmtLogActivity = $conn->prepare($queryLogActivity);
         if (!$stmtLogActivity) {
            throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
         }

         $stmtLogActivity->bind_param('iss', $iduser, $event, $notally);

         if (!$stmtLogActivity->execute()) {
            throw new Exception("Execute failed: " . $stmtLogActivity->error);
         }

         // Commit transaksi jika semua query berhasil dieksekusi
         $conn->commit();

         // Redirect ke halaman index.php setelah update berhasil
         header("location: index.php");
         exit();
      } catch (Exception $e) {
         // Rollback transaksi jika terjadi kesalahan
         $conn->rollback();
         echo "Error: " . $e->getMessage();
         exit();
      } finally {
         // Set autocommit kembali ke true setelah selesai
         $conn->autocommit(true);
      }
   } else {
      // Tampilkan form input
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
            /* Gaya untuk form popup */
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
            <form method="post" action="">
               <label for="sealnumb">Isi Nomor Segel, Apabila Kiriman Tidak Menggunakan Segel Biarkan Kosong:</label><br>
               <input type="text" id="sealnumb" name="sealnumb"><br><br>
               <button type="submit" name="submit" class="btn btn-sm btn-primary">Submit</button>
               <button type="button" onclick="hidePopup()">Cancel</button>
            </form>
         </div>
      </body>

      </html>
<?php
      exit(); // Pastikan untuk menghentikan eksekusi setelah menampilkan form
   }
} else {
   echo "ID tally tidak ditemukan.";
   exit();
}

$conn->close();
?>