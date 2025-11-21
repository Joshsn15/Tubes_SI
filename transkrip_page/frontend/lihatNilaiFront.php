<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lihat Nilai</title>
  <meta name="description" content="Free Bootstrap Theme by ProBootstrap.com">
  <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700|Open+Sans" rel="stylesheet">
  <link rel="stylesheet" href="css/styles-merged.css">
  <link rel="stylesheet" href="css/style.min.css">
  <link rel="stylesheet" href="css/custom.css">
</head>

<body>
  <nav class="navbar navbar-default probootstrap-navbar">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.html">Enlight</a>
      </div>

      <div id="navbar-collapse" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="index.html">Home</a></li>
          <li class="active"><a href="#">Nilai</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <section class="probootstrap-section probootstrap-section-colored">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-left section-heading">
          <h1>LIHAT NILAI</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="probootstrap-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          
          <?php
          include_once '../../Database/connection.php';
          include_once '../../Database/config.php';
          include_once '../../Database/checkRole.php';
          include_once '../../Database/encrypt-decrypt.php';

          if (isset($_GET['userID']) && $_GET['userID'] != '') {
              $id = $_GET['userID'];
          }

          if ($id) {
              $sql = "SELECT mk.Kd_Matkul, mk.Nama_Matkul, mk.sks, n.Nilai, n.Grade, m.NIM, u.nama 
                      FROM nilai n 
                      JOIN matkul mk ON n.Kd_Matkul = mk.Kd_Matkul 
                      JOIN mahasiswa m ON n.NIM = m.NIM 
                      JOIN users u ON m.userID = u.userID
                      WHERE m.userID = ?";

              $stmt = mysqli_prepare($conn, $sql);
              mysqli_stmt_bind_param($stmt, "s", $id);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
              
              echo "<div class='table-responsive'>
                      <table class='table table-bordered table-striped'> 
                        <thead>
                          <tr> 
                            <th>Kode MK</th> 
                            <th>Nama Matkul</th> 
                            <th>SKS</th> 
                            <th>Nilai</th> 
                            <th>Grade</th> 
                            <th>Aksi</th> 
                          </tr>
                        </thead>
                        <tbody>";

              while ($row = mysqli_fetch_array($result)) {
                  $nilai = decryptFunc($row['Nilai'], $keyDecrypt);
                  $grade = decryptFunc($row['Grade'], $keyDecrypt);
                  
                  echo "<tr>";
                  echo "<td>" . $row['Kd_Matkul'] . "</td>";
                  echo "<td>" . $row['Nama_Matkul'] . "</td>";
                  echo "<td>" . $row['sks'] . "</td>";
                  echo "<td>" . $nilai . "</td>";
                  echo "<td>" . $grade . "</td>";
                  echo "<td>
                          <a href='nilaiIndexFront.php?userID=" . $id . "&Kd_Matkul=" . $row['Kd_Matkul'] . "' class='btn btn-primary btn-sm'>Update</a>
                        </td>";
                  echo "</tr>";
              }
              echo "</tbody></table></div>";
          } else {
              echo "<div class='alert alert-danger'>User ID tidak ditemukan. Silakan login kembali.</div>";
          }
          ?>

        </div>
      </div>
    </div>
  </section>

  <footer class="probootstrap-footer probootstrap-bg">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <p>&copy; 2017 Enlight. All Rights Reserved.</p>
        </div>
      </div>
    </div>
  </footer>

  <script src="js/scripts.min.js"></script>
  <script src="js/main.min.js"></script>
  <script src="js/custom.js"></script>

</body>
</html>