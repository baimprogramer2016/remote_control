  <!-- power, water, compressor, machine -->
  <?php
  // include('configs/db_scadap2.php');
  // //jumlah power
  // $countpowerquery = mssql_query("SELECT count(id) countpower FROM PHouse_act_status with(nolock)");
  // $rcountpower = mssql_fetch_assoc($countpowerquery);

  // //jumlah water
  // $countwaterquery = mssql_query("SELECT count(id) countwater FROM WWT_act_status  with(nolock)");
$rcountwater = mssql_fetch_assoc($countwaterquery);
include('getdata.php');
$datainfo         =  dashInfo();
$datachartbottom  =  listChartBottom();
$countpower       =  $datainfo['power_count'];
$percentpower     =  $datainfo['power_percent'];
$countwwt         =  $datainfo['water_count'];
$percentwwt       =  $datainfo['water_percent'];
$countcompressor  =  $datainfo['compressor_count'];
$percentcomp      =  $datainfo['compressor_percent'];
$countmachine     =  $datainfo['machine_count'];
$percentmachine   =  $datainfo['machine_percent'];


  ?>
  <div class="page-header">      
     <a   onclick="return goRealtime()" class="nav-link btn btn-inverse-success create-new-button" id="createbuttonDropdown" data-toggle="dropdown" aria-expanded="false" href="#">Realtime Mode</a>
  </div>
  <div class="row">
              <div class="col-sm-3 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h5>Power</h5>
                    <div class="row">
                      <div class="col-8 col-sm-12 col-xl-8 my-auto">
                        <div
                          class="d-flex d-sm-block d-md-flex align-items-center"
                        >
                          <h2 class="mb-0"><?php echo $countpower;?></h2>
                          <p class="text-success ml-2 mb-0 font-weight-medium">
                            Active
                          </p>
                        </div>
                        <h6 class="text-muted font-weight-normal">
                          <?php echo $percentpower;?>% Consumption Since last month
                        </h6>
                      </div>
                      <div
                        class="col-4 col-sm-12 col-xl-4 text-center text-xl-right"
                      >
                        <i
                          class="icon-lg mdi mdi mdi-battery-charging-100 text-danger ml-auto"
                        ></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-3 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h5>Water</h5>
                    <div class="row">
                      <div class="col-8 col-sm-12 col-xl-8 my-auto">
                        <div
                          class="d-flex d-sm-block d-md-flex align-items-center"
                        >
                          <h2 class="mb-0"><?php echo $countwwt;?></h2>
                          <p class="text-success ml-2 mb-0 font-weight-medium">
                            Active
                          </p>
                        </div>
                        <h6 class="text-muted font-weight-normal">
                          <?php echo $percentwwt; ?>% Consumption Since last month
                        </h6>
                      </div>
                      <div
                        class="col-4 col-sm-12 col-xl-4 text-center text-xl-right"
                      >
                        <i
                          class="icon-lg mdi mdi-water text-primary ml-auto"
                        ></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-3 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h5>Compressor</h5>
                    <div class="row">
                      <div class="col-8 col-sm-12 col-xl-8 my-auto">
                        <div
                          class="d-flex d-sm-block d-md-flex align-items-center"
                        >
                          <h2 class="mb-0"><?php echo $countcompressor;?></h2>
                          <p class="text-success ml-2 mb-0 font-weight-medium">
                            Active
                          </p>
                        </div>
                        <h6 class="text-muted font-weight-normal">
                          <!-- <?php echo $percentcomp; ?> % Since last month -->
                        </h6>
                      </div>
                      <div
                        class="col-4 col-sm-12 col-xl-4 text-center text-xl-right"
                      >
                        <i
                          class="icon-lg mdi mdi-oil-temperature text-warning ml-auto"
                        ></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-3 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h5>Machines</h5>
                    <div class="row">
                      <div class="col-8 col-sm-12 col-xl-8 my-auto">
                        <div
                          class="d-flex d-sm-block d-md-flex align-items-center"
                        >
                          <h2 class="mb-0"><?php echo $countmachine;?></h2>
                          <p class="text-success ml-2 mb-0 font-weight-medium">
                            Active
                          </p>
                        </div>
                        <h6 class="text-muted font-weight-normal">
                          <!-- <?php echo $percentmachine;?> % Since last month -->
                        </h6>
                      </div>
                      <div
                        class="col-4 col-sm-12 col-xl-4 text-center text-xl-right"
                      >
                        <i
                          class="icon-lg mdi mdi-cube-unfolded text-success ml-auto"
                        ></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- End power, water, compressor, machine -->

            <div class="row">
              <div
                class="col-md-3 grid-margin stretch-card chart-power"
                onclick="return goChartPower()"
              >
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title text-center">Top 7 Highest kWh <br>(Today)</h4>
                    <canvas id="power-chart" class="transaction-chart"></canvas>
                    <div class="bg-gray-dark rounded mt-3 p-2"  style="height:200px;">
                      <div class="row w-100">
                        <?php
                          //untuk list dibawah chart
                          foreach($datachartbottom['list_power'] as $item_power)
                          {
                        ?>
                          <div class="col-md-12" style="font-size:11px;"><i class="mdi mdi-brightness-1 ml-2" style="color:<?php echo $item_power['color']?>;"></i> <?php echo $item_power['name_power'];?></div>
                        <?php
                          }
                        ?>
                          
                      </div>
                   
                    </div>
                
                  </div>
                </div>
              </div>
              <div
                class="col-md-3 grid-margin stretch-card chart-water"
                onclick="return goChartWater()"
              >
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title text-center">Top 7 Most Water Use <br>(Today)</h4>
                    <canvas id="water-chart" class="transaction-chart"></canvas>
                    <div class="bg-gray-dark rounded mt-3 p-2" style="height:200px;">
                        <div class="row w-100">
                            <?php
                              //untuk list dibawah chart
                              foreach($datachartbottom['list_water'] as $item_power)
                              {
                            ?>
                              <div class="col-md-12" style="font-size:10.8px;"><i class="mdi mdi-brightness-1 ml-2" style="color:<?php echo $item_power['color']?>;"></i> <?php echo $item_power['name_water'];?></div>
                            <?php
                              }
                            ?>
                              
                          </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 grid-margin stretch-card chart-compressor"
              onclick="return goChartCompressor()">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title text-center">Compressor <br>(Today)</h4>
                    <canvas
                      id="compressor-chart"
                      class="transaction-chart"
                    ></canvas>
                    
                    <div class="bg-gray-dark rounded mt-3 p-2" style="height:200px;">
                        <div class="row w-100">
                            <?php
                              //untuk list dibawah chart
                              foreach($datachartbottom['list_compressor'] as $item_power)
                              {
                            ?>
                              <div class="col-md-12" style="font-size:12px;"><i class="mdi mdi-brightness-1 ml-1" style="color:<?php echo $item_power['color']?>;"></i> <?php echo $item_power['name_compressor'];?></div>
                            <?php
                              }
                            ?>
                          </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title text-center">Quantity Of <br>Machine</h4>
                    <canvas
                      id="machines-chart"
                      class="transaction-chart"
                    ></canvas>
                 
                    <div class="bg-gray-dark rounded mt-3 p-2" style="height:200px;">
                        <div class="row w-100">
                            <?php
                              //untuk list dibawah chart
                              foreach($datachartbottom['list_machine'] as $item_power)
                              {
                            ?>
                              <div class="col-md-12" style="font-size:12px;"><i class="mdi mdi-brightness-1 ml-2" style="color:<?php echo $item_power['color']?>;"></i> <?php echo $item_power['name_machine'];?></div>
                            <?php
                              }
                            ?>
                          </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

<script>
  function goChartPower() {
    location.href = "main.php?page=chart-power";
  }
  function goChartWater() {
    location.href = "main.php?page=chart-water";
  }
  function goChartCompressor() {
    location.href = "main.php?page=chart-compressor";
  }
  function goRealtime() {
    // alert("hai");
    window.open('realtime-dashboard.php', '_blank');

  }
</script>

