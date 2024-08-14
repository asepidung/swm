<?php
function getRandomQuote($conn)
{
   $query = "SELECT isiquote FROM quotes ORDER BY RAND() LIMIT 1";
   $result = $conn->query($query);

   if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['isiquote'];
   } else {
      return 'No quotes available.';
   }
}

// Mendapatkan quote acak
$quote = getRandomQuote($conn);

// Menutup koneksi database
$conn->close();
?>
<div class="card card-danger shadow-lg">
   <div class="card-header">
      <h3 class="card-title">Quotes Of The Day</h3>
      <div class="card-tools">
         <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
   </div>
   <div class="card-body">
      <!-- Isi quotes random -->
      <h4><?php echo htmlspecialchars($quote); ?></h4>
   </div>
</div>