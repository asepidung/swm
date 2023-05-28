<!DOCTYPE html>
<html>

<head>
  <title>Cetak Label</title>
  <style>
    @page {
      size: 4in 3in;
      margin: 0;
    }

    body {
      width: 100%;
      height: 100%;
      margin: 0;
      padding: 0;
    }

    .label {
      width: 100%;
      height: 100%;
      border: 1px solid #000;
      padding: 10px;
      box-sizing: border-box;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      font-family: Arial, sans-serif;
      font-size: 12px;
    }
  </style>
</head>

<body>
  <div class="label">
    <p>Contoh Label</p>
  </div>
</body>

</html>