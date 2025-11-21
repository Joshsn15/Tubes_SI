<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lihat Nilai</title>
  <meta name="description" content="Free Bootstrap Theme by ProBootstrap.com">
  <meta name="keywords"
    content="free website templates, free bootstrap themes, free template, free bootstrap, free website template">

  <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700|Open+Sans" rel="stylesheet">
  <link rel="stylesheet" href="css/styles-merged.css">
  <link rel="stylesheet" href="css/style.min.css">
  <link rel="stylesheet" href="css/custom.css">
</head>

<body>
  <div class="probootstrap-header-top">
    <div class="container">
      <div class="row">

        </form>
      </div>
    </div>
  </div>
  </div>
  <nav class="navbar navbar-default probootstrap-navbar">
    <div class="container">
      <div class="navbar-header">
        <div class="btn-more js-btn-more visible-xs">
          <a href="#"><i class="icon-dots-three-vertical "></i></a>
        </div>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse"
          aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.html" title="ProBootstrap:Enlight">Enlight</a>
      </div>

      <div id="navbar-collapse" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="index.html">Home</a></li>
          <li><a href="courses.html">Courses</a></li>
          <li><a href="teachers.html">Teachers</a></li>
          <li><a href="events.html">Events</a></li>
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle">Pages</a>
            <ul class="dropdown-menu">
              <li><a href="about.html">About Us</a></li>
              <li><a href="courses.html">Courses</a></li>
              <li><a href="course-single.html">Course Single</a></li>
              <li><a href="gallery.html">Gallery</a></li>
              <li class="dropdown-submenu dropdown">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle"><span>Sub Menu</span></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Second Level Menu</a></li>
                  <li><a href="#">Second Level Menu</a></li>
                  <li><a href="#">Second Level Menu</a></li>
                  <li><a href="#">Second Level Menu</a></li>
                </ul>
              </li>
              <li class="active"><a href="news.html">News</a></li>
            </ul>
          </li>
          <li><a href="contact.html">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <section class="probootstrap-section probootstrap-section-colored">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-left section-heading probootstrap-animate">
          <h1>UPDATE NILAI</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="probootstrap-section">
    <div class="container">
      <?php
      include_once '../../Database/connection.php';
      include_once '../../Database/config.php';
      include_once '../../Database/checkRole.php';
      include_once '../../Database/encrypt-decrypt.php';
      ?>

      <?php

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
        $nilai = (int) $nilai;
        if ($nilai >= 80)
          return 'A';
        else if ($nilai >= 70)
          return 'B';
        else if ($nilai >= 60)
          return 'C';
        else if ($nilai >= 50)
          return 'D';
        else
          return 'E';
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

      global $conn;
      global $keyDecrypt;

      $admin = checkRoleByCookie();
      echo "Admin: " . ($admin ? "true" : "false") . "<br>";


      if ($admin) {
        $sql = "SELECT * FROM nilai WHERE deletedAt IS NULL";
        $stmt = mysqli_prepare($conn, $sql);
        $stmt->execute();
      } else {

        $sqlCheckNim = "SELECT m.nim FROM mahasiswa m 
                        INNER JOIN users u ON u.userID = m.userID 
                        WHERE u.userID = ? AND m.deletedAt IS NULL";

        $stmtNim = mysqli_prepare($conn, $sqlCheckNim);
        $stmtNim->bind_param("s", $_COOKIE['userID']);
        $stmtNim->execute();
        $resultNim = $stmtNim->get_result();
        $mahasiswa = $resultNim->fetch_assoc();

        if (!$mahasiswa) {
          echo "Tidak ada data mahasiswa.";
          exit;
        }

        $nim = $mahasiswa['nim'];
        echo "NIM: " . htmlspecialchars($nim) . "<br>";

        $sql = "SELECT * FROM nilai WHERE NIM = ? AND deletedAt IS NULL";
        $stmt = mysqli_prepare($conn, $sql);
        $stmt->bind_param("s", $nim);
        $stmt->execute();
      }

      $result = $stmt->get_result();



      echo "<table border='1'>";
      echo "<tr>";

      if ($admin) {
        echo "<th>NIM</th>";
        echo "<th>Kode Matkul</th>";
        echo "<th>Nilai</th>";
        echo "<th>Grade</th>";
        echo "<th>Created At</th>";
        echo "<th>Deleted At</th>";
        echo "<th>Updated At</th>";
        echo "<th>Actions</th>";
      } else {
        echo "<th>Kode Matkul</th>";
        echo "<th>Nilai</th>";
        echo "<th>Grade</th>";
      }

      echo "</tr>";


      while ($row = $result->fetch_assoc()) {
        if ($row['Grade'] == null || $row['Nilai'] == null) {
          continue;
        }

        $decryptedNilai = decryptfunc($row['Nilai'], $keyDecrypt);
        $decryptedGrade = decryptfunc($row['Grade'], $keyDecrypt);
        echo "<tr>";

        if ($admin) {
          echo "<td>" . htmlspecialchars($row['NIM']) . "</td>";
          echo "<td>" . htmlspecialchars($row['Kd_Matkul']) . "</td>";
          echo "<td>" . htmlspecialchars($decryptedNilai) . "</td>";
          echo "<td>" . htmlspecialchars($decryptedGrade) . "</td>";
          echo "<td>" . htmlspecialchars($row['createdAt']) . "</td>";
          echo "<td>" . htmlspecialchars($row['deletedAt']) . "</td>";
          echo "<td>" . htmlspecialchars($row['updatedAt']) . "</td>";
          echo "<td>";
          echo "<button onclick='updateNilai(\"" . htmlspecialchars($row['NIM'], ENT_QUOTES) . "\", \"" . htmlspecialchars($row['Kd_Matkul'], ENT_QUOTES) . "\", \"" . htmlspecialchars($decryptedNilai, ENT_QUOTES) . "\")'>Update</button> ";
          echo "<button onclick='deleteNilai(\"" . htmlspecialchars($row['NIM'], ENT_QUOTES) . "\", \"" . htmlspecialchars($row['Kd_Matkul'], ENT_QUOTES) . "\")'>Delete</button>";
          echo "</td>";
        } else {
          echo "<td>" . htmlspecialchars($row['Kd_Matkul']) . "</td>";
          echo "<td>" . htmlspecialchars($decryptedNilai) . "</td>";
          echo "<td>" . htmlspecialchars($decryptedGrade) . "</td>";
        }

        echo "</tr>";
      }

      echo "</table>";



      echo "<br>";
      if ($admin) {
        echo "<button onclick='insertNilai()'>Insert Nilai</button>";
      }

      echo "<div id='formContainer'></div>";
      ?>

      <script>
        function insertNilai() {
          var form = "<form method='post' action='nilai.php'>";
          form += "<h3>Insert Nilai</h3>";
          form += "<label for='nim'>NIM</label>";
          form += "<input type='text' name='nim' id='nim' required>";
          form += "<label for='kd_matkul'>Kode Matkul</label>";
          form += "<input type='text' name='kd_matkul' id='kd_matkul' required>";
          form += "<label for='nilai'>Nilai</label>";
          form += "<input type='number' step='0.01' name='nilai' id='nilai' required>";
          form += "<button type='submit' name='submitInsertNilai'>Insert Nilai</button>";
          form += "<button type='button' onclick='clearForm()'>Cancel</button>";
          form += "</form>";
          document.getElementById("formContainer").innerHTML = form;
        }

        function updateNilai(nim, kd_matkul, nilai) {
          var form = "<form method='post' action='nilai.php'>";
          form += "<h3>Update Nilai</h3>";
          form += "<input type='hidden' name='nim' value='" + nim + "'>";
          form += "<input type='hidden' name='kd_matkul' value='" + kd_matkul + "'>";
          form += "<label>NIM: " + nim + "</label><br>";
          form += "<label>Kode Matkul: " + kd_matkul + "</label><br>";
          form += "<label for='nilai'>Nilai</label>";
          form += "<input type='number' step='0.01' name='nilai' id='nilai' value='" + nilai + "' required>";
          form += "<button type='submit' name='submitUpdateNilai'>Update Nilai</button>";
          form += "<button type='button' onclick='clearForm()'>Cancel</button>";
          form += "</form>";
          document.getElementById("formContainer").innerHTML = form;
        }

        function deleteNilai(nim, kd_matkul) {
          if (confirm('Are you sure you want to delete this record?')) {
            var form = document.createElement('form');
            form.method = 'post';
            form.action = 'nilai.php';

            var nimInput = document.createElement('input');
            nimInput.type = 'hidden';
            nimInput.name = 'nim';
            nimInput.value = nim;

            var kdMatkulInput = document.createElement('input');
            kdMatkulInput.type = 'hidden';
            kdMatkulInput.name = 'kd_matkul';
            kdMatkulInput.value = kd_matkul;

            var submitInput = document.createElement('input');
            submitInput.type = 'hidden';
            submitInput.name = 'submitDeleteNilai';
            submitInput.value = '1';

            form.appendChild(nimInput);
            form.appendChild(kdMatkulInput);
            form.appendChild(submitInput);

            document.body.appendChild(form);
            form.submit();
          }
        }

        function clearForm() {
          document.getElementById("formContainer").innerHTML = "";
        }
      </script>

      <footer class="probootstrap-footer probootstrap-bg">
        <div class="container">
          <div class="row">
            <div class="col-md-4">
              <div class="probootstrap-footer-widget">
                <h3>About The School</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Porro provident suscipit natus a cupiditate
                  ab
                  minus illum quaerat maxime inventore Ea consequatur consectetur hic provident dolor ab aliquam eveniet
                  alias</p>
                <h3>Social</h3>
                <ul class="probootstrap-footer-social">
                  <li><a href="#"><i class="icon-twitter"></i></a></li>
                  <li><a href="#"><i class="icon-facebook"></i></a></li>
                  <li><a href="#"><i class="icon-github"></i></a></li>
                  <li><a href="#"><i class="icon-dribbble"></i></a></li>
                  <li><a href="#"><i class="icon-linkedin"></i></a></li>
                  <li><a href="#"><i class="icon-youtube"></i></a></li>
                </ul>
              </div>
            </div>
            <div class="col-md-3 col-md-push-1">
              <div class="probootstrap-footer-widget">
                <h3>Links</h3>
                <ul>
                  <li><a href="#">Home</a></li>
                  <li><a href="#">Courses</a></li>
                  <li><a href="#">Teachers</a></li>
                  <li><a href="#">News</a></li>
                  <li><a href="#">Contact</a></li>
                </ul>
              </div>
            </div>
            <div class="col-md-4">
              <div class="probootstrap-footer-widget">
                <h3>Contact Info</h3>
                <ul class="probootstrap-contact-info">
                  <li><i class="icon-location2"></i> <span>198 West 21th Street, Suite 721 New York NY 10016</span></li>
                  <li><i class="icon-mail"></i><span>info@domain.com</span></li>
                  <li><i class="icon-phone2"></i><span>+123 456 7890</span></li>
                </ul>
              </div>
            </div>

          </div>
        </div>

        <div class="probootstrap-copyright">
          <div class="container">
            <div class="row">
              <div class="col-md-8 text-left">
                <p>&copy; 2017 <a href="https://probootstrap.com/">ProBootstrap:Enlight</a>. All Rights Reserved.
                  Designed
                  &amp; Developed with <i class="icon icon-heart"></i> by <a
                    href="https://probootstrap.com/">ProBootstrap.com</a></p>
              </div>
              <div class="col-md-4 probootstrap-back-to-top">
                <p><a href="#" class="js-backtotop">Back to top <i class="icon-arrow-long-up"></i></a></p>
              </div>
            </div>
          </div>
        </div>
      </footer>

    </div>

    <script src="js/scripts.min.js"></script>
    <script src="js/main.min.js"></script>
    <script src="js/custom.js"></script>

</body>

</html>