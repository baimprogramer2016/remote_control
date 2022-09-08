
            <div class="page-header">
              <h3 class="page-title">Most Water Use</h3>
              <a   onclick="return goDashboard()" class="nav-link btn btn-inverse-warning create-new-button" id="createbuttonDropdown" data-toggle="dropdown" aria-expanded="false" href="#">Back to Dashboard</a>
            </div>
            <!-- MY CONTENT -->
            <div class="row">
            <?php
            include('configs/db_scadap2.php');
            include('getdatatank.php');
            $panelid_query = mssql_query($querypanel);
            $rpanelrows = mssql_num_rows($panelid_query);
            if($rpanelrows != 0)
            {
              while($rpanel = mssql_fetch_assoc($panelid_query))
              {  
                if($rpanel['dailly_use'] == 0)
                {
                  $rpnlwater = 0;
                }else{
                  $rpnlwater = $rpanel['dailly_use'] /1000;
                }
              ?>
                <div class="col-lg-6 col-md-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title"><?php echo $rpanel['description'];?> <span id="total_".$r> ( <?php echo number_format($rpnlwater). 'K (M3)';?> )</span></h4>
                      <canvas id="<?php echo $rpanel['tank_id'];?>" style="height: 20vh;color:#fff;"></canvas>
                    </div>
                  </div>
                </div>
         

              <?php
              }
            }else{
            ?>

            <?php
            }
            ?>
            </div>
            <!-- END CONTENT -->
<script>
  function goDashboard() {
    // alert("hai");
    location.href = "main.php?page=dashboard";
  }
</script>