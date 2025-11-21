<?php
include '../Database/connection.php';
include '../Database/checkRole.php';
include '../Database/encrypt-decrypt.php';
include '../Database/config.php';
// $_COOKIE['userID'] = "3c71fd52-65be-4e9c-9fb1-b00d176e7ef3";
$_COOKIE['userID'] = "2391ba08-0376-4795-98c2-6c3b2ede5d63";

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
    $grade = $_POST['grade'];
    updateNilai($nim, $kd_matkul, $nilai, $grade);
}

if (isset($_POST['submitDeleteNilai'])) {
    $nim = $_POST['nim'];
    $kd_matkul = $_POST['kd_matkul'];
    deleteNilai($nim, $kd_matkul);
}

function insertNilai($nim, $kd_matkul, $nilai)
{
    if (checkRoleByCookie()) {
        $sql = "INSERT INTO nilai(nim, kd_matkul, nilai, grade) VALUES (?, ?, ?, NULL)";

        global $conn;
        global $keyDecrypt;

        $stmt = mysqli_prepare($conn, $sql);

        $newNilai = encryptFunc($keyDecrypt, $nilai);
        $stmt->bind_param("sss", $nim, $kd_matkul, $newNilai);

        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            header('Location: nilaiIndex.php');
            exit;
        }
    } else {
        echo "Failed to insert data.";
        return false;
    }
}



function updateNilai($nim, $kd_matkul, $nilai, $grade)
{
    echo $nilai;
    echo $grade;
    if (checkRoleByCookie()) {
        $sql = "UPDATE nilai SET nilai = ?, grade = ?, updatedAt = now() WHERE nim = ? AND kd_matkul = ? AND deletedAt IS NULL";
        global $conn;
        $stmt = mysqli_prepare($conn, $sql);
        global $keyDecrypt;
        $newNilai = encryptFunc($keyDecrypt, $nilai);
        $newGrade = encryptFunc($keyDecrypt, $grade);
        $stmt->bind_param("ssss", $newNilai, $newGrade, $nim, $kd_matkul);
        $result = $stmt->execute();
        $stmt->close();
        echo $newGrade;
        echo $newNilai;
        if ($result) {
            header('Location: nilaiIndex.php');
            exit;
        } else {
            return false;
        }
    }
}

function deleteNilai($nim, $kd_matkul)
{
    if (checkRoleByCookie()) {
        $sql = "UPDATE nilai SET deletedAt = now(), updatedAt = now() WHERE nim = ? AND kd_matkul = ?";
        global $conn;
        $stmt = mysqli_prepare($conn, $sql);
        $stmt->bind_param("ss", $nim, $kd_matkul);
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
