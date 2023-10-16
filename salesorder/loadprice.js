document.addEventListener("DOMContentLoaded", function () {
   // Menangkap perubahan pilihan customer dan barang
   document.getElementById("idcustomer").addEventListener("change", updatePrice);
   document.getElementById("idbarang").addEventListener("change", updatePrice);
});

function updatePrice() {
   const idCustomer = document.getElementById("idcustomer").value;
   const idBarang = document.getElementById("idbarang").value;

   if (idCustomer && idBarang) {
      // Ganti URL dengan URL sesuai dengan endpoint yang Anda gunakan untuk mendapatkan harga
      const url = `/getPrice.php?idCustomer=${idCustomer}&idBarang=${idBarang}`;

      fetch(url)
         .then((response) => response.json())
         .then((data) => {
            // Mengisi otomatis harga ke dalam input price
            document.getElementById("price").value = data.price;
         })
         .catch((error) => {
            console.error("Error:", error);
         });
   }
}
