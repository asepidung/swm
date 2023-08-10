<?php
// Query untuk mengambil data dari tabel grade
$sql = "SELECT * FROM grade";
$result = $conn->query($sql);

$options = '';
if ($result->num_rows > 0) {
   while ($row = $result->fetch_assoc()) {
      $options .= "<option value=\"" . $row["idgrade"] . "\">" . $row["nmgrade"] . "</option>";
   }
} else {
   $options = '<option value="">No data available</option>';
}

echo $options;
