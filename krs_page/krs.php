<?php
include '../Database/connection.php';
include '../Database/checkRole.php';


function insertKRS($nim, $kd_matkul, $sks, $status)
{
    if (checkRoleByCookie()) {
        $sql = "INSERT INTO krs(nim,kd_matkul,status,sks) VALUES (?,?,?,?)";
        global $conn;
        $stmt = mysqli_prepare($conn, $sql);
        $stmt->bind_param("sss", $nim, $kd_matkul, $sks, $status);
        $stmt->execute();
        $stmt->close();
    } else {
        return false;
    }
}

function updateKRS($nim, $kd_matkul, $sks, $status)
{
    if (checkRoleByCookie()) {

        $sql = "UPDATE krs SET sks = ? , status = ? WHERE nim = ? ";
        global $conn;
        $stmt = mysqli_prepare($conn, $sql);
        $stmt->bind_param("sss", $sks, $status, $nim);
        $stmt->execute();
        $stmt->close();
    } else {
        return false;
    }
}

// function showKRS($nim)
// {
//     $sql = "SELECT * FROM krs WHERE nim = ?";
//     global $conn;
//     $stmt = mysqli_prepare($conn, $sql);
//     $stmt->bind_param("s", $nim);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $stmt->close();
//     return $result;
// }
