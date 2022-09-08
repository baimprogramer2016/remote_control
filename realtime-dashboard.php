
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <title>Dashboard</title>
    <!-- plugins:css -->
    <link
      rel="stylesheet"
      href="assets/vendors/mdi/css/materialdesignicons.min.css"
    />
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css" />
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link
      rel="stylesheet"
      href="assets/vendors/jvectormap/jquery-jvectormap.css"
    />
    <link
      rel="stylesheet"
      href="assets/vendors/flag-icon-css/css/flag-icon.min.css"
    />
    <link
      rel="stylesheet"
      href="assets/vendors/owl-carousel-2/owl.carousel.min.css"
    />
    <link
      rel="stylesheet"
      href="assets/vendors/owl-carousel-2/owl.theme.default.min.css"
    />
    
    <!-- End Plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/myStyle.css" />
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/akebono.png" />
   
  </head>
  <body>

    <div class="container-scroller" id="elem">
        <div class="content-wrapper">
        <div class="page-header">      
          <div class="row ml-4">
            <a   onclick="return openFullscreen()" class="mr-2 nav-link btn btn-inverse-info create-new-button" id="btnFull" data-toggle="dropdown" aria-expanded="false" href="#">Full Screen</a>
            <a   onclick="return exitFullScreen()" class="mr-2 nav-link btn btn-inverse-info create-new-button" id="btnExit" data-toggle="dropdown" aria-expanded="false" href="#">Exit Full Screen</a>
            <a   onclick="return closeWindow()" class=" nav-link btn btn-inverse-danger create-new-button" id="createbuttonDropdown" data-toggle="dropdown" aria-expanded="false" href="#">Close Window</a>
          </div>
        </div>
<!--###################LINE CHART POWER & WATER ##########################-->                
                <div class="row w-100 pl-4">
                    <div class="col-lg-6 col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                    <div class="input-group-text text-white">TOP 7 Power House</div>
                                    </div>
                                    <select
                                    name="sethourPower"
                                    class="form-control text-white bg-dark"
                                    id="sethourPower"
                                    >
                                    <option value=6>Default 6 hours before </option>
                                    <?php 
                                        for($ijam =1 ;$ijam <= 12;$ijam++)
                                        {
                                            echo "<option value=$ijam>$ijam hours before</option>";
                                        }
                                    ?>
                                    </select>
                                </div>
                                <canvas id="chart-power-realtime" style="height:250px">
                              </canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                    <div class="input-group-text text-white">TOP 7 Water (K)</div>
                                    </div>
                                    <select
                                    name="sethourWater"
                                    class="form-control text-white bg-dark"
                                    id="sethourWater"
                                    >
                                    <option value=6>Default 6 hours before </option>
                                    <?php 
                                        for($ijam =1 ;$ijam <= 12;$ijam++)
                                        {
                                            echo "<option value=$ijam>$ijam hours before</option>";
                                        }
                                    ?>
                                    </select>
                                </div>
                                <canvas id="chart-water-realtime" style="height:250px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
<!--###################SPEEDOMETER CHART COMPRESSOR ##########################-->                
                <div class="row">
                    <div class="col-lg-4 col-md-4 grid-margin stretch-card">
                          <div class="card">
                            <div class="card-body">
                              <h5 class="card-title">Compressor (Today)</h5>
                             
                              <div id="chart-compressor-realtime" class="chartQuageCompressor" style="background-color:#191c24;height: 20vh;color:#fff;"></div>
                              
                            </div>
                          </div>
                        </div>
                    <?php
                    include('getdatacompressor.php');
                    $datacompre = getDataPreasure();
                    foreach($datacompre as $itemcompre)
                    {
                    ?>
                        <div class="col-lg-2 col-md-2 grid-margin stretch-card">
                          <div class="card">
                            <div class="card-body">
                              <h5 class="card-title"><?php echo $itemcompre['title'];?></h5>
                              
                              <div id="<?php echo $itemcompre['key'];?>" class="chartQuageCompressor" style="background-color:#191c24;height: 20vh;color:#fff;"></div>
                              
                            </div>
                          </div>
                        </div>
                    <?php
                    }
                    ?>
                    </div>
        </div>
    </div>

  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <script src="assets/vendors/chart.js/Chart.min.js"></script>
    <script src="assets/vendors/progressbar.js/progressbar.min.js"></script>
    <script src="assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
    <script src="assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="assets/vendors/owl-carousel-2/owl.carousel.min.js"></script>
    <!-- inject:js -->

    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
    <script src="assets/js/jquery-3.5.1.min.js"></script> 
    <script src="assets/js/zingchart.min.js"></script>
    <script src="assets/js/chart-realtime.js"></script>
    <script src="assets/js/chart-realtime-water.js"></script>
    <script src="assets/js/chart-realtime-compressor.js"></script>
    <script>
      function closeWindow() {
    
        window.open('','_self').close()

      }


      var btnFull = document.getElementById("btnFull");
      var btnExit = document.getElementById("btnExit");
      var elem    = document.getElementById("elem");
      btnExit.style.display = 'none';
      btnFull.style.display = 'block';

      function openFullscreen() {
        btnExit.style.display = 'block';
        btnFull.style.display = 'none';

        if (elem.requestFullscreen) {
          elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
          elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
          elem.msRequestFullscreen();
        }
      }
      function exitFullScreen() {
        btnExit.style.display = 'none';
        btnFull.style.display = 'block';
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
      }
    </script>
  </body>
</html>