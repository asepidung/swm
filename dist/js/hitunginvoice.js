function calculateAmounts() {
   var weights = document.getElementsByName('weight[]');
   var prices = document.getElementsByName('price[]');
   var discounts = document.getElementsByName('discount[]');
   var discountrp = document.getElementsByName('discountrp[]');
   var amounts = document.getElementsByName('amount[]');
   var totalWeight = 0;
   var totalAmount = 0;
   var totalDiscount = 0;

   // Menghitung total berat, menghitung discountrp, dan menghitung amounts
   for (var i = 0; i < weights.length; i++) {
      var weight = parseFloat(weights[i].value);
      var price = parseFloat(prices[i].value);
      var discount = parseFloat(discounts[i].value);

      if (!isNaN(weight)) {
         totalWeight += weight;
      }

      if (!isNaN(weight) && !isNaN(price)) {
         var discountrpValue = (weight * price) * (discount / 100);
         discountrp[i].value = discountrpValue.toFixed(2);

         var amount = (weight * price) - discountrpValue;
         amounts[i].value = amount.toFixed(2);

         totalAmount += amount;
         totalDiscount += discountrpValue;
      }
   }

   // Log totalAmount sebelum dihitung ulang
   console.log("Total Amount (sebelum dihitung ulang): " + totalAmount);

   var pajak = "<?= $pajak ?>";
   var taxElement = document.getElementById('tax');
   var taxAmount = 0;

   // Menghitung totalAmount setelah dihitung ulang
   for (var i = 0; i < amounts.length; i++) {
      var amountValue = parseFloat(amounts[i].value.replace(',', '')); // Mengubah format angka dengan digit grouping
      if (!isNaN(amountValue)) {
         totalAmount += amountValue;
      }
   }

   // Log totalAmount setelah dihitung ulang
   console.log("Total Amount (setelah dihitung ulang): " + totalAmount);

   if (pajak === 'YES') {
      var taxPercentage = 0.11; // Persentase pajak 11%
      taxAmount = totalAmount * taxPercentage; // Menggunakan totalAmount bukan balance
   }

   taxElement.value = taxAmount.toFixed(2);

   // Menghitung balance
   var charge = parseFloat(document.getElementById('charge').value);
   var downPayment = parseFloat(document.getElementById('downpayment').value);
   var balanceElement = document.getElementById('balance');
   var balance = totalAmount + taxAmount + charge - downPayment;

   balanceElement.value = balance.toFixed(2);

   // Mengubah format discountrp[]
   for (var i = 0; i < discountrp.length; i++) {
      discountrp[i].value = parseFloat(discountrp[i].value).toLocaleString(undefined, {
         minimumFractionDigits: 2,
         maximumFractionDigits: 2
      });
   }

   // Mengubah format amounts[]
   for (var i = 0; i < amounts.length; i++) {
      amounts[i].value = parseFloat(amounts[i].value).toLocaleString(undefined, {
         minimumFractionDigits: 2,
         maximumFractionDigits: 2
      });
   }

   // Mengubah format xamount
   document.getElementById('xamount').value = parseFloat(totalAmount.toFixed(2)).toLocaleString(undefined, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
   });

   // Mengubah format xweight
   document.getElementById('xweight').value = totalWeight.toFixed(2);

   // Hapus toLocaleString() agar nilai taxAmount tetap angka, bukan string dengan tanda koma
   taxElement.value = taxAmount;

   // Mengubah format balance
   balanceElement.value = parseFloat(balance.toFixed(2)).toLocaleString(undefined, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
   });

   // Mengubah format xdiscount
   document.getElementById('xdiscount').value = parseFloat(totalDiscount.toFixed(2)).toLocaleString(undefined, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
   });
   document.getElementById('submit-btn').removeAttribute('disabled');
}
