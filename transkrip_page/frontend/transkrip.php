<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Transkrip Nilai</title>
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
          <h1>TRANSKRIP NILAI</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="probootstrap-section">
    <div class="container">
      <?php
      include '../../Database/connection.php';
      include '../../Database/encrypt-decrypt.php';
      include '../../Database/config.php';
      include '../../Database/checkRole.php';

      $id = $_COOKIE['userID'];
      if (checkRoleByCookie()) {
        ?>
        <script>
          function searchData(reset) {
            let name = encodeURIComponent(document.getElementById("searchName").value)
            let nim = encodeURIComponent(document.getElementById("searchNIM").value);

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
              if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("data").innerHTML = xmlhttp.responseText;
              }
            }
            xmlhttp.open("GET", "../search_trans.php?name=" + name + "&nim=" + nim + "&reset=" + reset, true);
            xmlhttp.send();
          }
        </script>

        <div id="search">
          <input type="text" name="name" placeholder="Name" id="searchName">
          <input type="text" name="nim" placeholder="NIM" id="searchNIM">
          <button onclick="searchData()">Search</button>
          <button onclick="searchData('reset')">Reset</button>
        </div>


        <?php
        $sql = "SELECT m.NIM, u.nama, u.userID FROM mahasiswa m JOIN users u ON m.userID = u.userID ORDER BY m.nim ASC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        echo "<table id='data'> <tr> <th>NIM </th> <th> Nama </th> <th> Aksi </th> </tr>";
        while ($row = mysqli_fetch_array($result)) {
          $nama = decryptFunc($row['nama'], $keyDecrypt);
          echo "<tr> <td>" . $row['NIM'] . "</td>";
          echo "<td>" . $nama . "</td>";
          echo "<td><a href='lihatNilaiFront.php?userID=" . $row['userID'] . "'>Lihat Nilai </a></td></tr>";
        }
        echo "</table>";

      } else {
        $sql = "SELECT mk.Kd_Matkul, mk.Nama_Matkul, mk.sks, n.Nilai, n.Grade FROM nilai n JOIN matkul mk ON n.Kd_Matkul = mk.Kd_Matkul JOIN mahasiswa m ON n.NIM = m.NIM WHERE m.userID = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        echo "<table> <tr> <th> Kode MK </th> <th> Nama_Matkul </th> <th> SKS </th> <th> Nilai </th> <th> Grade </th> </tr>";
        while ($row = mysqli_fetch_array($result)) {
          $nilai = decryptFunc($row['Nilai'], $keyDecrypt);
          $grade = decryptFunc($row['Grade'], $keyDecrypt);
          echo "<tr> <td>" . $row['Kd_Matkul'] . "</td>";
          echo "<td>" . $row['Nama_Matkul'] . "</td>";
          echo "<td>" . $row['sks'] . "</td>";
          echo "<td>" . $nilai . "</td>";
          echo "<td>" . $grade . "</td></tr>";
        }
        echo "</table>";
      }
      ?>
      
  <footer class="probootstrap-footer probootstrap-bg">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <div class="probootstrap-footer-widget">
            <h3>About The School</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Porro provident suscipit natus a cupiditate ab
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
            <p>&copy; 2017 <a href="https://probootstrap.com/">ProBootstrap:Enlight</a>. All Rights Reserved. Designed
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