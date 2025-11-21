<?php
include '../Database/connection.php';
include '../Database/checkRole.php';
include '../Database/encrypt-decrypt.php';
include '../Database/config.php';


if (isset($_POST['submitInsertNilai'])) {
    $nim = $_POST['nim'];
    $kd_matkul = $_POST['kd_matkul'];
    $nilai = $_POST['nilai'];
    insertNilai($nim, $kd_matkul, $nilai);
}

if (isset($_POST['submitUpdateNilai'])) {
    $nim = $_POST['nim'];
    $kd_matkul = $_POST['kd_matkul'];
    $nilai = $_POST['nilai'];
    updateNilai($nim, $kd_matkul, $nilai);
}

if (isset($_POST['submitDeleteNilai'])) {
    $nim = $_POST['nim'];
    $kd_matkul = $_POST['kd_matkul'];
    deleteNilai($nim, $kd_matkul);
}

function insertNilai($nim, $kd_matkul, $nilai)
{
    if (checkRoleByCookie()) {

        $sql = "INSERT INTO nilai(nim, kd_matkul, nilai, grade, createdAt) VALUES (?, ?, ?, ?, NOW())";

        global $conn;
        global $keyDecrypt;

        $stmt = mysqli_prepare($conn, $sql);

        $newNilai = encryptFunc($nilai, $keyDecrypt);
        $newGrade = hitungGrade($nilai);
        $newGrade = encryptFunc($newGrade, $keyDecrypt);

        $stmt->bind_param("ssss", $nim, $kd_matkul, $newNilai, $newGrade);

        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            header('Location: nilaiIndex.php');
            exit;
        }
    } else {
        return false;
    }
}

function updateNilai($nim, $kd_matkul, $nilai)
{
    if (checkRoleByCookie()) {

        $sql = "UPDATE nilai SET nilai = ?, grade = ?, updatedAt = NOW()
                WHERE nim = ? AND kd_matkul = ? AND deletedAt IS NULL";

        global $conn;
        global $keyDecrypt;

        $stmt = mysqli_prepare($conn, $sql);
        $newNilai = encryptFunc($nilai, $keyDecrypt);
        $newGrade = hitungGrade($nilai);
        $newGrade = encryptFunc($newGrade, $keyDecrypt);

        $stmt->bind_param("ssss", $newNilai, $newGrade, $nim, $kd_matkul);
        $stmt->execute();
        $stmt->close();

        header('Location: nilaiIndex.php');
        exit;
    }
}

function hitungGrade($nilai)
{
    $nilai = (int)$nilai;
    if ($nilai >= 80) return 'A';
    else if ($nilai >= 70) return 'B';
    else if ($nilai >= 60) return 'C';
    else if ($nilai >= 50) return 'D';
    else return 'E';
}

function deleteNilai($nim, $kd_matkul)
{
    if (checkRoleByCookie()) {
        $sql = "UPDATE nilai SET deletedAt = NOW(), updatedAt = NOW()
                WHERE nim = ? AND kd_matkul = ?";

        global $conn;
        $stmt = mysqli_prepare($conn, $sql);
        $stmt->bind_param("ss", $nim, $kd_matkul);
        $stmt->execute();
        $stmt->close();

        header('Location: nilaiIndex.php');
        exit;
    } else {
        return false;
    }
}
