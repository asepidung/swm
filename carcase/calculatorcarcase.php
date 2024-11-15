<?php
// Query untuk mengambil data carcase
$query = "SELECT carcase.idcarcase, carcase.killdate, supplier.nmsupplier, users.fullname,
                     (SELECT SUM(cd.berat) FROM carcasedetail cd WHERE cd.idcarcase = carcase.idcarcase) AS total_berat,
                     (SELECT COUNT(cd.eartag) FROM carcasedetail cd WHERE cd.idcarcase = carcase.idcarcase) AS total_eartag,
                     (SELECT SUM(cd.carcase1) + SUM(cd.carcase2) FROM carcasedetail cd WHERE cd.idcarcase = carcase.idcarcase) AS total_carcase,
                     (SELECT SUM(cd.carcase1) + SUM(cd.carcase2) + SUM(cd.tail) FROM carcasedetail cd WHERE cd.idcarcase = carcase.idcarcase) AS total_carcase_tail,
                     (SELECT SUM(cd.hides) FROM carcasedetail cd WHERE cd.idcarcase = carcase.idcarcase) AS total_hides,
                     (SELECT SUM(cd.tail) FROM carcasedetail cd WHERE cd.idcarcase = carcase.idcarcase) AS total_tails
              FROM carcase 
              JOIN supplier ON carcase.idsupplier = supplier.idsupplier
              LEFT JOIN users ON carcase.idusers = users.idusers
              ORDER BY carcase.idcarcase DESC";
$result = mysqli_query($conn, $query);
