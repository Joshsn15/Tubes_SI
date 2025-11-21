
<!-- USER ROLE DEFAULT NULLL -->
<?php
include '../Database/connection.php';
include '../Database/uuidGenerator.php';
include '../Database/encrypt-decrypt.php';
include '../Database/config.php';
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}
if (isset($_POST['nama']) && isset($_POST['password']) && isset($_POST['alamat'])) {
    $nama = $_POST['nama'];
    $password = $_POST['password'];
    $alamat = $_POST['alamat'];

    if (empty($nama) || empty($password) || empty($alamat)) {
        echo "Data tidak boleh kosong.";
    } else {
        if (addUser($nama, $password, $alamat)) {
            header('Location:../Login_page/login-Form.php');
            // echo "Data berhasil disimpan.";
        } else {
            echo "Data gagal disimpan.";
        }
    }
}
function addUser($nama, $password, $alamat)
{
    global $conn;
    global $keyDecrypt;
    $sql = "INSERT INTO users(userID,nama,password,alamat,user_role) VALUES(?,?,?,?,NULL)";
    $stmt = mysqli_prepare($conn, $sql);
    $newNama = encryptFunc($nama, $keyDecrypt);
    $newUserID = generate_uuid_v4(); // generate uuid
    $newPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt->bind_param("ssss", $newUserID, $newNama, $newPassword, $alamat);
    if ($stmt->execute()) {
        // echo "Berhasil";
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        return false;
    }
}

//add mahasiswa
// if (addMahasiswa($nim,$prodi,$newUserID)) {
//             return true;
//         }
//         else {
//             return false;
//         }
// function addMahasiswa($nim,$prodi,$userID){
//     global $conn;
//     $sql = "INSERT INTO mahasiswa(Nim,Prodi,userID) VALUES(?,?,?)";
//     $stmt = mysqli_prepare($conn, $sql);
//     $stmt->bind_param("sss", $nim, $prodi, $userID);
//     if($stmt->execute()){
//         return true;
//     }
//     else {
//         echo "Error: " . $sql . "<br>" . mysqli_error($conn);
//         return false;
//     }
// }
