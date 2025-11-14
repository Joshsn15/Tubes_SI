<?php
include '../Database/connection.php';
include '../Database/uuidGenerator.php';
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}
if (isset($_POST['nim']) && isset($_POST['nama']) && isset($_POST['password']) && isset($_POST['alamat']) && isset($_POST['prodi'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $password = $_POST['password'];
    $alamat = $_POST['alamat'];
    $Prodi = $_POST['prodi'];
    
    if (empty($nim) || empty($nama) || empty($Prodi) || empty($password) || empty($alamat)) {
        echo "Data tidak boleh kosong.";
    } else {
        if (addUser($nama, $password, $alamat, $nim, $Prodi)) {
            echo "Data berhasil disimpan.";
        } else {
            echo "Data gagal disimpan.";
        }
    }
}
function addUser($nama, $password, $alamat,$nim,$prodi)
{
    global $conn;
    $sql = "INSERT INTO users(userID,nama,password,alamat,user_role) VALUES(?,?,?,?,NULL)";
    $stmt = mysqli_prepare($conn, $sql);
    $newUserID = generate_uuid_v4(); // generate uuid
    $newPassword = password_hash($password,PASSWORD_BCRYPT);
    $stmt->bind_param("ssss", $newUserID, $nama, $newPassword, $alamat);
    if($stmt->execute()){
        if (addMahasiswa($nim,$prodi,$newUserID)) {
            return true;
        }
        else {
            return false;
        }
    }
    else{
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        return false;
    }
    
}

function addMahasiswa($nim,$prodi,$userID){
    global $conn;
    $sql = "INSERT INTO mahasiswa(Nim,Prodi,userID) VALUES(?,?,?)";
    $stmt = mysqli_prepare($conn, $sql);
    $stmt->bind_param("sss", $nim, $prodi, $userID);
    if($stmt->execute()){
        return true;
    }
    else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        return false;
    }
}
