function calculateAmounts() {
   var weightInputs = document.querySelectorAll("input[name='weight[]']");
   var priceInputs = document.querySelectorAll("input[name='price[]']");
   var discountInputs = document.querySelectorAll("input[name='discount[]']");
   var amountInputs = document.querySelectorAll("input[name='amount[]']");
   var totalWeightInput = document.getElementById("xweight");
   var totalAmountInput = document.getElementById("xamount");
   var pajakInput = document.getElementById("pajak");
   var taxInput = document.getElementById("tax");
   var chargeInput = document.getElementById("charge");
   var dpInput = document.getElementById("dp");
   var balanceInput = document.getElementById("balance");

   var totalWeight = 0;
   var totalAmount = 0;
   var taxAmount = 0;

   for (var i = 0; i < weightInputs.length; i++) {
      var price = parseFloat(priceInputs[i].value);
      var discount = parseFloat(discountInputs[i].value);

      if (isNaN(discount)) {
         var amount = weightInputs[i].value * price;
         amountInputs[i].value = formatAmount(amount);
      } else {
         var amount = (weightInputs[i].value * price) - ((weightInputs[i].value * price) * (discount / 100));
         amountInputs[i].value = formatAmount(amount);
      }

      totalWeight += parseFloat(weightInputs[i].value);
      totalAmount += parseFloat(amountInputs[i].value.replace(/,/g, ''));
   }

   totalWeightInput.value = formatAmount(totalWeight);
   totalAmountInput.value = formatAmount(totalAmount);

   var pajak = parseInt(pajakInput.value);
   if (pajak === 1) {
      var taxRate = 0.11;
      taxAmount = totalAmount * taxRate;
      taxInput.value = formatAmount(taxAmount);
   } else {
      taxInput.value = "0";
   }

   var charge = parseFloat(chargeInput.value.replace(/,/g, ''));
   var dp = parseFloat(dpInput.value.replace(/,/g, ''));

   var balance = totalAmount + taxAmount + charge - dp;
   balanceInput.value = formatAmount(balance);

   // Aktifkan tombol Submit setelah mengklik Calculate
   document.getElementById("submit-btn").disabled = false;
}

// Fungsi bantu untuk memformat angka
function formatAmount(amount) {
   return amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}