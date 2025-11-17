<?php
require "../verifications/auth.php";
require "../konak/conn.php";

if (!function_exists('e')) {
    function e($s)
    {
        return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$idcarcase = (int)($_POST['idcarcase'] ?? 0);
$killdate  = $_POST['killdate'] ?? date('Y-m-d');
$note      = trim($_POST['note'] ?? '');
$idusers   = (int)($_SESSION['idusers'] ?? 0);

$iddetail  = $_POST['iddetail']  ?? [];
$breed     = $_POST['breed']     ?? [];
$carcase1  = $_POST['carcase1']  ?? [];
$carcase2  = $_POST['carcase2']  ?? [];
$hides     = $_POST['hides']     ?? [];
$tails     = $_POST['tails']     ?? [];

if ($idcarcase <= 0) {
    die("Error: ID carcas tidak valid.");
}
if ($idusers <= 0) {
    die("Error: Session user tidak valid.");
}

// -----------------------------
// Fungsi normalisasi angka
// -----------------------------
function normalizeNumber($number)
{
    $number = trim((string)$number);
    if ($number === '') {
        return null;
    }

    $number = str_replace(' ', '', $number);

    $lastComma = strrpos($number, ',');
    $lastDot   = strrpos($number, '.');

    if ($lastComma !== false && $lastDot !== false) {
        if ($lastComma > $lastDot) {
            // format: 1.234,56
            $number = str_replace('.', '', $number);
            $number = str_replace(',', '.', $number);
        } else {
            // format: 1,234.56
            $number = str_replace(',', '', $number);
        }
    } elseif ($lastComma !== false) {
        // hanya koma → desimal
        $number = str_replace('.', '', $number);
        $number = str_replace(',', '.', $number);
    } else {
        if (substr_count($number, '.') > 1) {
            $parts = explode('.', $number);
            $last  = array_pop($parts);
            $number = implode('', $parts) . '.' . $last;
        }
    }

    if (!is_numeric($number)) {
        return null;
    }
    return (float)$number;
}

// -----------------------------
// Siapkan baris detail hasil edit
// -----------------------------
$rows   = [];
$errors = [];

foreach ($iddetail as $i => $idDet) {
    $idDet = (int)$idDet;

    $classRaw = isset($breed[$i]) ? strtoupper(trim($breed[$i])) : '';

    // Carcase A
    $rawC1 = $carcase1[$i] ?? '';
    $valC1 = normalizeNumber($rawC1);
    if ($rawC1 !== '' && $valC1 === null) {
        $errors[] = "Carcase A baris " . ($i + 1) . " tidak valid.";
    }
    $c1 = ($valC1 === null || $valC1 < 0) ? 0.0 : $valC1;

    // Carcase B
    $rawC2 = $carcase2[$i] ?? '';
    $valC2 = normalizeNumber($rawC2);
    if ($rawC2 !== '' && $valC2 === null) {
        $errors[] = "Carcase B baris " . ($i + 1) . " tidak valid.";
    }
    $c2 = ($valC2 === null || $valC2 < 0) ? 0.0 : $valC2;

    // Hides
    $rawH = $hides[$i] ?? '';
    $valH = normalizeNumber($rawH);
    if ($rawH !== '' && $valH === null) {
        $errors[] = "Hides baris " . ($i + 1) . " tidak valid.";
    }
    $h = ($valH === null || $valH < 0) ? 0.0 : $valH;

    // Tail
    $rawT = $tails[$i] ?? '';
    $valT = normalizeNumber($rawT);
    if ($rawT !== '' && $valT === null) {
        $errors[] = "Tail baris " . ($i + 1) . " tidak valid.";
    }
    $t = ($valT === null || $valT < 0) ? 0.0 : $valT;

    if ($idDet <= 0) {
        $errors[] = "ID detail tidak valid pada baris " . ($i + 1) . ".";
        continue;
    }

    // Catatan: di edit, kita tidak paksa breed wajib diisi,
    // supaya data lama yang kosong tetap bisa disimpan.
    $rows[] = [
        'iddetail' => $idDet,
        'breed'    => $classRaw,
        'carcase1' => $c1,
        'carcase2' => $c2,
        'hides'    => $h,
        'tail'     => $t,
    ];
}

if (!empty($errors)) {
    echo "<h3>Error:</h3><ul>";
    foreach ($errors as $err) {
        echo "<li>" . e($err) . "</li>";
    }
    echo "</ul>";
    echo '<p><a href="javascript:history.back()">Kembali</a></p>';
    exit;
}

// -----------------------------
// UPDATE ke database (TRANSAKSI)
// -----------------------------
$conn->begin_transaction();

try {
    // Pastikan header masih ada & aktif
    $stmt = $conn->prepare("
        SELECT idcarcase 
        FROM carcase 
        WHERE idcarcase = ? AND is_deleted = 0 
        LIMIT 1
    ");
    if ($stmt === false) throw new Exception("Prepare cek header gagal: " . $conn->error);
    $stmt->bind_param('i', $idcarcase);
    $stmt->execute();
    $stmt->bind_result($existing);
    if (!$stmt->fetch()) {
        $stmt->close();
        throw new Exception("Data carcas tidak ditemukan atau sudah dihapus.");
    }
    $stmt->close();

    // Update HEADER (killdate & note saja, aman)
    $stmt = $conn->prepare("
        UPDATE carcase
        SET killdate = ?, 
            note     = ?
        WHERE idcarcase = ? AND is_deleted = 0
    ");
    if ($stmt === false) throw new Exception("Prepare update header gagal: " . $conn->error);
    $stmt->bind_param(
        'ssi',
        $killdate,
        $note,
        $idcarcase
    );
    if (!$stmt->execute()) {
        throw new Exception("Gagal mengupdate header carcas: " . $stmt->error);
    }
    $stmt->close();

    // Update DETAIL per baris (jika ada)
    if (!empty($rows)) {
        $stmt = $conn->prepare("
            UPDATE carcasedetail
            SET breed    = ?,
                carcase1 = ?,
                carcase2 = ?,
                hides    = ?,
                tail     = ?
            WHERE iddetail = ? AND idcarcase = ?
        ");
        if ($stmt === false) throw new Exception("Prepare update detail gagal: " . $conn->error);

        foreach ($rows as $r) {
            $idDet = $r['iddetail'];
            $b     = $r['breed'];
            $c1    = $r['carcase1'];
            $c2    = $r['carcase2'];
            $h     = $r['hides'];
            $t     = $r['tail'];

            // s d d d d i i
            $ok = $stmt->bind_param(
                'sddddii',
                $b,
                $c1,
                $c2,
                $h,
                $t,
                $idDet,
                $idcarcase
            );
            if ($ok === false) throw new Exception("Bind param update detail gagal: " . $stmt->error);

            if (!$stmt->execute()) {
                throw new Exception("Gagal mengupdate detail carcas: " . $stmt->error);
            }
        }
        $stmt->close();
    }

    $conn->commit();

    header("Location: view.php?id=" . $idcarcase);
    exit;
} catch (Exception $ex) {
    $conn->rollback();
    die("Terjadi kesalahan: " . e($ex->getMessage()));
}
