<?php
include 'Database/connection.php';

if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}
if (isset($_POST['submit'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $prodi = $_POST['prodi'];

    if (empty($nim) || empty($nama) || empty($alamat) || empty($prodi)) {
        echo "Data tidak boleh kosong.";
    } else {
        if (addUser($nim,$nama, $alamat, $prodi)) {
            echo "Data berhasil disimpan.";
        } else {
            echo "Data gagal disimpan.";
        }
    }
}
function addUser($username, $password, $alamat, $role){
    $sql = "INSERT INTO 'user'(Username,Password,Alamat,Role) VALUES (?,?,?,?)";
    global $conn;
    $stmt = mysqli_prepare($conn, $sql);
    $pass_md5 = md5($password);
    $stmt->bind_param("sssi", $username, $pass_md5, $alamat, $role);

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    }

}