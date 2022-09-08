<?php
        include('getdatacompressor.php');
        $checkApiPreasure = checkApiPreasure();

        if(count($checkApiPreasure) == 0){
          ?>
            <div class="alert alert-warning" role="alert">
            Warning, Connection to API state is lost and Remote Control not Working
            </div>
          <?php
        };
?>
       
       <div class="page-header">
          <h3 class="page-title">Consumption Compressor</h3>
          <a   onclick="return goDashboard()" class="nav-link btn btn-inverse-warning create-new-button" id="createbuttonDropdown" data-toggle="dropdown" aria-expanded="false" href="#">Back to Dashboard</a>
          </div>
          <!-- MY CONTENT -->
          <div class="row">
     
          <?php
          $datacompre = getDataPreasure();
          foreach($datacompre as $itemcompre)
          {
          ?>
              <div class="col-lg-6 col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title"><?php echo $itemcompre['title'];?></h4>
                    
                    <div id="<?php echo $itemcompre['key'];?>" class="chartQuageCompressor" style="background-color:#191c24;height: 20vh;color:#fff;"></div>
                    
                  </div>
                </div>
              </div>
          <?php
          }
          ?>
          </div>
          <!-- END CONTENT -->
          <script src="assets/js/jquery-3.5.1.min.js"></script> 
          <!-- <script src="https://cdn.zingchart.com/zingchart.min.js"></script> -->
          <script src="assets/js/zingchart.min.js"></script>
          <script src="assets/js/chart-compressor.js"></script>
<script>
  function goDashboard() {
    // alert("hai");
    location.href = "main.php?page=dashboard";
  }
</script>