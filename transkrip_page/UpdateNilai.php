<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Nilai</title>
</head>

<body>
    <?php
    include '../Database/connection.php';
    include '../Database/encrypt-decrypt.php';
    include '../Database/config.php';
    include '../Database/checkRole.php';

    // $id = '7eac0a6a-7157-4474-92cf-0f28bbf0a444';
    $id = $_GET['userID'];

    function hitungGrade($nilai)
    {
        $nilai = (int) $nilai;
        if ($nilai >= 80) {
            return 'A';
        } else if ($nilai >= 70) {
            return 'B';
        } else if ($nilai >= 60) {
            return 'C';
        } else if ($nilai >= 50) {
            return 'D';
        } else {
            return 'E';
        }
    }


    // $nilaiData = null;
    // if (isset($_GET['Kd_Matkul'])) {
    //     $kode_mk = $_GET['Kd_Matkul'];
    
    //     $sql = "SELECT n.Kd_Matkul, mk.Nama_Matkul, n.Nilai, n.Grade, m.NIM, u.nama FROM nilai n JOIN matkul mk ON n.Kd_Matkul = mk.Kd_Matkul JOIN mahasiswa m ON n.NIM = m.NIM JOIN users u ON m.userID = u.userID WHERE m.userID = ? AND n.Kd_Matkul = ?";
    //     $stmt = mysqli_prepare($conn, $sql);
    //     mysqli_stmt_bind_param($stmt, "ss", $id, $kode_mk);
    //     mysqli_stmt_execute($stmt);
    //     $result = mysqli_stmt_get_result($stmt);
    
    //     $nilaiData = mysqli_fetch_array($result);
    //     if ($nilaiData) {
    //         $nilai = decryptFunc($nilaiData['Nilai'], $keyDecrypt);
    //         $grade = decryptFunc($nilaiData['Grade'], $keyDecrypt);
    //         $nama = decryptFunc($nilaiData['nama'], $keyDecrypt);
    //     }
    // }
    
    // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //     $kode_mk = $_POST['Kd_Matkul'];
    //     $nilaiInput = $_POST['Nilai'];
    
    //     if ($kode_mk && $id && $nilaiInput != '') {
    //         $grade = hitungGrade($nilaiInput);
    
    //         $nilaiEncrypted = encryptFunc($nilaiInput, $keyEncrypt);
    //         $gradeEncrypted = encryptFunc($grade, $keyEncrypt);
    
    //         $sqlNim = "SELECT NIM FROM mahasiswa WHERE userID= ?";
    //         $stmtNim = mysqli_prepare($conn, $sqlNim);
    //         mysqli_stmt_bind_param($stmtNim, "s", $id);
    //         mysqli_stmt_execute($stmtNim);
    //         $resultNim = mysqli_stmt_get_result($stmtNim);
    
    //         $rowNim = mysqli_fetch_array($resultNim);
    //         $nim = $rowNim['NIM'];
    
    //         $sqlUpdate = "UPDATE nilai SET Nilai = ?, Grade = ? WHERE NIM = ? AND Kd_Matkul = ?";
    //         $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);
    //         mysqli_stmt_bind_param($stmtUpdate, "ssss", $nilaiEncrypted, $gradeEncrypted, $nim, $kode_mk);
    
    //         if (mysqli_stmt_execute($stmtUpdate)) {
    //             echo "Nilai berhasil diupdate! Grade berubah menjadi: " + $grade;
    //         }
    //     }
    // }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $kode_mk = $_POST['Kd_Matkul'];
        $nilaiInput = $_POST['Nilai'];

        if ($kode_mk && $id && $nilaiInput !== '') {
            $gradeBaru = hitungGrade($nilaiInput);

            $nilaiEncrypted = encryptFunc($nilaiInput, $keyEncrypt);
            $gradeEncrypted = encryptFunc($gradeBaru, $keyEncrypt);

            $sqlNim = "SELECT NIM FROM mahasiswa WHERE userID= ?";
            $stmtNim = mysqli_prepare($conn, $sqlNim);
            mysqli_stmt_bind_param($stmtNim, "s", $id);
            mysqli_stmt_execute($stmtNim);
            $resultNim = mysqli_stmt_get_result($stmtNim);
            $rowNim = mysqli_fetch_array($resultNim);

            if ($rowNim) {
                $nim = $rowNim['NIM'];

                $sqlUpdate = "UPDATE nilai SET Nilai = ?, Grade = ? WHERE NIM = ? AND Kd_Matkul = ?";
                $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);
                mysqli_stmt_bind_param($stmtUpdate, "ssss", $nilaiEncrypted, $gradeEncrypted, $nim, $kode_mk);

                if (mysqli_stmt_execute($stmtUpdate)) {
                    $message = "<div class='alert alert-success'>Nilai berhasil diupdate! Grade berubah menjadi: <b>$gradeBaru</b></div>";
                } else {
                    $message = "<div class='alert alert-danger'>Gagal mengupdate nilai.</div>";
                }
            }
        }
    }

    $nilaiData = null;
    if (isset($_GET['Kd_Matkul'])) {
        $kode_mk = $_GET['Kd_Matkul'];
        $sql = "SELECT n.Kd_Matkul, mk.Nama_Matkul, mk.sks, n.Nilai, n.Grade, m.NIM, u.nama, u.userID 
            FROM nilai n 
            JOIN matkul mk ON n.Kd_Matkul = mk.Kd_Matkul 
            JOIN mahasiswa m ON n.NIM = m.NIM 
            JOIN users u ON m.userID = u.userID 
            WHERE m.userID = $id AND n.Kd_Matkul = $kode_mk";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $id, $target_mk);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $nilaiData = mysqli_fetch_array($result);

        if ($nilaiData) {
            $namaMK = $nilaiData['Nama_Matkul'];
            $sks = $nilaiData['sks'];
            $nilai = decryptFunc($nilaiData['Nilai'], $keyDecrypt);
            $grade = decryptFunc($nilaiData['Grade'], $keyDecrypt);
            $namaMhs = decryptFunc($nilaiData['nama'], $keyDecrypt);
        }
        echo '<form action="post" >
                <input type="hidden" name="id" value="' . $nilaiData['userID'] . '">
              <label for="Kode MK"></label><br>
              <input type="text" name="kodeMK" value="' . $kode_mk. '"><br>
              <label for="Nama Matkul"></label><br>
              <input type="text" name="namaMK" value="' . $nilaiData['Nama_Matkul']. '"><br>
              <label for="SKS"></label><br>
              <input type="number" name="sks" value="' . $sks. '"><br>
              <label for="Nilai"></label><br>
              <input type="text" name="nilai" value="' . $nilai. '"><br>
              <label for="Grade"></label><br>
              <input type="text" name="grade" value="' . $grade. '"><br>
              <input type="submit" name="submit" value="save">
              </form>';
    }
    ?>



</body>

</html>