<!DOCTYPE html>

<!-- 
  Theme Name: Enlight
  Theme URL: https://probootstrap.com/enlight-free-education-responsive-bootstrap-website-template
  Author: ProBootstrap.com
  Author URL: https://probootstrap.com
  License: Released for free under the Creative Commons Attribution 3.0 license (probootstrap.com/license)
-->
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ProBootstrap:Enlight &mdash; Free Bootstrap Theme, Free Responsive Bootstrap Website Template</title>
  <meta name="description" content="Free Bootstrap Theme by ProBootstrap.com">
  <meta name="keywords"
    content="free website templates, free bootstrap themes, free template, free bootstrap, free website template">

  <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700|Open+Sans" rel="stylesheet">
  <link rel="stylesheet" href="css/styles-merged.css">
  <link rel="stylesheet" href="css/style.min.css">
  <link rel="stylesheet" href="css/custom.css">

  <!--[if lt IE 9]>
      <script src="js/vendor/html5shiv.min.js"></script>
      <script src="js/vendor/respond.min.js"></script>
    <![endif]-->
</head>

<body>
  <!-- Fixed navbar -->
  <?php
  $id = $_GET['userID'];
  ?>
  <div class="probootstrap-header-top">
    <div class="container">
      <div class="row">
        <form method="post" action="UpdateNilai.php?userID=<?php echo $id ?>">
          <?php
          $nilaiData = null;
          if (isset($_GET['Kd_Matkul'])) {
            $kode_mk = $_GET['Kd_Matkul'];

            $sql = "SELECT n.Kd_Matkul, mk.Nama_Matkul, mk.sks, n.Nilai, n.Grade, m.NIM, u.nama FROM nilai n JOIN matkul mk ON
          n.Kd_Matkul = mk.Kd_Matkul JOIN mahasiswa m ON n.NIM = m.NIM JOIN users u ON m.userID = u.userID WHERE
          m.userID = ? AND n.Kd_Matkul = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $nilaiData = mysqli_fetch_array($result);
            $namaMK = $nilaiData['Nama_Matkul'];
            $sks = $nilaiData['sks'];

            if ($nilaiData) {
              $nilai = decryptFunc($nilaiData['Nilai'], $keyDecrypt);
              $grade = decryptFunc($nilaiData['Grade'], $keyDecrypt);
              $nama = decryptFunc($nilaiData['nama'], $keyDecrypt);
            }
          }
          echo '<input type="hidden" name="id" value="' . $id . '">
          <label for="Kode MK"></label><br>
          <input type="text" name="kodeMK" value="' . $kode_mk. '"><br>
          <label for="Nama Matkul"></label><br>
          <input type="text" name="namaMK" value="' . $namaMK. '"><br>
          <label for="SKS"></label><br>
          <input type="text" name="sks" value="' . $sks. '"><br>
          <label for="Nilai"></label><br>
          <input type="text" name="nilai" value="' . $nilai. '"><br>
          <label for="Grade"></label><br>
          <input type="text" name="grade" value="' . $grade. '"><br>
          <input type="submit" name="submit" value="save">';
          ?>

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
          <h1>School Events</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="probootstrap-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="probootstrap-flex-block">
            <div class="probootstrap-text probootstrap-animate">
              <div class="text-uppercase probootstrap-uppercase">Featured News</div>
              <h3>Students Math Competition for The Year 2017</h3>
              <p>Quis explicabo veniam labore ratione illo vero voluptate a deserunt incidunt odio aliquam commodi </p>
              <p>
                <span class="probootstrap-date"><i class="icon-calendar"></i>July 9, 2017</span>
                <span class="probootstrap-location"><i class="icon-user2"></i>By Admin</span>
              </p>
              <p><a href="#" class="btn btn-primary">Learn More</a></p>
            </div>
            <div class="probootstrap-image probootstrap-animate" style="background-image: url(img/slider_4.jpg)">
              <a href="https://vimeo.com/45830194" class="btn-video popup-vimeo"><i class="icon-play3"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>




  <section class="probootstrap-section">
    <div class="container">

      <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-6 col-xxs-12 probootstrap-animate">
          <a href="#" class="probootstrap-featured-news-box">
            <figure class="probootstrap-media"><img src="img/img_sm_1.jpg"
                alt="Free Bootstrap Template by ProBootstrap.com" class="img-responsive"></figure>
            <div class="probootstrap-text">
              <h3>Tempora consectetur unde nisi</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis, quaerat?</p>
              <span class="probootstrap-date"><i class="icon-calendar"></i>July 9, 2017</span>
              <span class="probootstrap-location"><i class="icon-user2"></i>By Admin</span>
            </div>
          </a>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-6 col-xxs-12 probootstrap-animate">
          <a href="#" class="probootstrap-featured-news-box">
            <figure class="probootstrap-media"><img src="img/img_sm_2.jpg"
                alt="Free Bootstrap Template by ProBootstrap.com" class="img-responsive"></figure>
            <div class="probootstrap-text">
              <h3>Tempora consectetur unde nisi</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil, unde?</p>
              <span class="probootstrap-date"><i class="icon-calendar"></i>July 9, 2017</span>
              <span class="probootstrap-location"><i class="icon-user2"></i>By Admin</span>
            </div>
          </a>
        </div>
        <div class="clearfix visible-sm-block visible-xs-block"></div>
        <div class="col-md-4 col-sm-6 col-xs-6 col-xxs-12 probootstrap-animate">
          <a href="#" class="probootstrap-featured-news-box">
            <figure class="probootstrap-media"><img src="img/img_sm_3.jpg"
                alt="Free Bootstrap Template by ProBootstrap.com" class="img-responsive"></figure>
            <div class="probootstrap-text">
              <h3>Tempora consectetur unde nisi</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Necessitatibus, possimus!</p>
              <span class="probootstrap-date"><i class="icon-calendar"></i>July 9, 2017</span>
              <span class="probootstrap-location"><i class="icon-user2"></i>By Admin</span>
            </div>
          </a>
        </div>

        <div class="clearfix visible-md-block"></div>

        <div class="col-md-4 col-sm-6 col-xs-6 col-xxs-12 probootstrap-animate">
          <a href="#" class="probootstrap-featured-news-box">
            <figure class="probootstrap-media"><img src="img/img_sm_1.jpg"
                alt="Free Bootstrap Template by ProBootstrap.com" class="img-responsive"></figure>
            <div class="probootstrap-text">
              <h3>Tempora consectetur unde nisi</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet, sunt!</p>
              <span class="probootstrap-date"><i class="icon-calendar"></i>July 9, 2017</span>
              <span class="probootstrap-location"><i class="icon-user2"></i>By Admin</span>
            </div>
          </a>
        </div>
        <div class="clearfix visible-sm-block visible-xs-block"></div>
        <div class="col-md-4 col-sm-6 col-xs-6 col-xxs-12 probootstrap-animate">
          <a href="#" class="probootstrap-featured-news-box">
            <figure class="probootstrap-media"><img src="img/img_sm_2.jpg"
                alt="Free Bootstrap Template by ProBootstrap.com" class="img-responsive"></figure>
            <div class="probootstrap-text">
              <h3>Tempora consectetur unde nisi</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus, voluptatem.</p>
              <span class="probootstrap-date"><i class="icon-calendar"></i>July 9, 2017</span>
              <span class="probootstrap-location"><i class="icon-user2"></i>By Admin</span>
            </div>
          </a>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-6 col-xxs-12 probootstrap-animate">
          <a href="#" class="probootstrap-featured-news-box">
            <figure class="probootstrap-media"><img src="img/img_sm_3.jpg"
                alt="Free Bootstrap Template by ProBootstrap.com" class="img-responsive"></figure>
            <div class="probootstrap-text">
              <h3>Tempora consectetur unde nisi</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti, magnam.</p>
              <span class="probootstrap-date"><i class="icon-calendar"></i>July 9, 2017</span>
              <span class="probootstrap-location"><i class="icon-user2"></i>By Admin</span>
            </div>
          </a>
        </div>
        <div class="clearfix visible-sm-block visible-xs-block"></div>

      </div>

    </div>
  </section>

  <section class="probootstrap-cta">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2 class="probootstrap-animate" data-animate-effect="fadeInRight">Get your admission now!</h2>
          <a href="#" role="button" class="btn btn-primary btn-lg btn-ghost probootstrap-animate"
            data-animate-effect="fadeInLeft">Enroll</a>
        </div>
      </div>
    </div>
  </section>
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
      <!-- END row -->

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
  <!-- END wrapper -->


  <script src="js/scripts.min.js"></script>
  <script src="js/main.min.js"></script>
  <script src="js/custom.js"></script>

</body>

</html>