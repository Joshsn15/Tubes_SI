<?php

include 'Database/connection.php';

if (isset($_POST['submit'])) {
    $nim = $_POST['nim'];
    $Kd_Matkul = $_POST['Kd_Matkul'];
    $nilai = $_POST['nilai'];
    $grade = $_POST['grade'];

    if (empty($nim) || empty($Kd_Matkul) || empty($nilai) || empty($grade)) {
        echo "Data tidak boleh kosong.";
    } else {
        if (addNilai($nim, $Kd_Matkul, $nilai, $grade)) {
            echo "Data berhasil disimpan.";
        } else {
            echo "Data gagal disimpan.";
        }
    }
}

if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

function addNilai($nim, $Kd_Matkul, $nilai, $grade)
{
    try {
        $sql = "INSERT INTO nilai(Nim,Kd,Matkul,Nilai,Grade) VALUES (?,?,?,?,?)";
        global $conn;
        $stmt = mysqli_prepare($conn, $sql);
        $nilai_md5 = md5($nilai);
        $grade_md5 = md5($grade);
        $stmt->bind_param("ssss", $nim, $Kd_Matkul, $nilai_md5, $grade_md5);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
    } catch (Exception $e) {
        echo $e;
        $stmt->close();
        return false;
    }
}
