<?php 
include 'Database/connection.php';

if(isset($_POST['submit'])){
    $nim = $_POST['nim'];
    $prodi = $_POST['prodi'];

    if (empty($nim) || empty($prodi)) {
        echo "Data tidak boleh kosong.";
    } else {
        if (updateMahasiswa($nim, $prodi)) {
            echo "Data berhasil diupdate.";
        } else {
            echo "Data gagal diupdate.";
        }
    }
}
function updateMahasiswa($nim, $prodi)
{
    $sql = "UPDATE mahasiswa SET Kd_Prodi = ? WHERE Nim = ?";
    global $conn;
    $stmt = mysqli_prepare($conn, $sql);
    $stmt->bind_param("sss",$prodi, $nim);

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    }

    $stmt->close();
    return false;
}

?>