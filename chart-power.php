
            <div class="page-header">
              <h3 class="page-title">Highest kWh / Month</h3>
              <a   onclick="return goDashboard()" class="nav-link btn btn-inverse-warning create-new-button" id="createbuttonDropdown" data-toggle="dropdown" aria-expanded="false" href="#">Back to Dashboard</a>
            </div>
            <!-- MY CONTENT -->
            <div class="row">
            <?php
            include('configs/db_scadap2.php');
            include('getdatapanel.php');
            $panelid_query = mssql_query($querypanel);
            $rpanelrows = mssql_num_rows($panelid_query);
            if($rpanelrows != 0)
            {
              while($rpanel = mssql_fetch_assoc($panelid_query))
              {  
              ?>
                <div class="col-lg-6 col-md-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title"><?php echo $rpanel['description'];?> <span id="total_".$r> ( <?php echo number_format($rpanel['kwh']). ' kWh';?> )</span></h4>
                      <canvas id="<?php echo $rpanel['panel_id'];?>" style="height: 20vh;color:#fff;"></canvas>
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