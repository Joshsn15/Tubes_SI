<?php
include 'Database/connection.php';

if(isset($_POST['submit'])){
    $Kd_Matkul = $_POST['Kd_Matkul'];
    $Matkul = $_POST['Matkul'];
    $sks = $_POST['sks'];

    if(empty($Kd_Matkul) || empty($Matkul) || empty($sks)){
        echo "Data tidak boleh kosong.";
    }else{
        if(addMatkul($Kd_Matkul, $Matkul, $sks)){
            echo "Data berhasil disimpan.";
        }else{
            echo "Data gagal disimpan.";
        }
    }
}

function addMatkul($Kd_Matkul, $Matkul, $sks){
   try{
     $sql = "INSERT INTO matkul(Kd_Matkul,Nama_Matkul,sks) VALUES (?,?,?)";
    global $conn;
    $stmt = mysqli_prepare($conn, $sql);
    $stmt->bind_param("ssi", $Kd_Matkul, $Matkul, $sks);
    if($stmt->execute()){
        $stmt->close();
        return true;
    }
   }
   catch(Exception $e){
    echo $e;
   }
    $stmt->close();
    return false;
}