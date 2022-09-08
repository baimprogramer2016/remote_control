<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
	function query($query)
	{
        include('configs/db_scadap2.php');
		// global $scada_p2;
		$result = mssql_query($query, $connection_scada2);
		$rows 	= [];
	
		while ($row = mssql_fetch_assoc($result)) {
			$rows[] = $row;
		}
		return $rows;
	}
    function debug($string){
        return print("<pre>".print_r($string,true)."</pre>");
    }

    $this_url = $_SERVER['PHP_SELF']."?page=report-water-distribution" ;

      // DO UPDATE First / Deny Error
    // $query = "UPDATE WWT_act_history SET dailly_use = '0' WHERE dailly_use = 'NaN' OR dailly_use ='undefined'";
    // $update = mssql_query($query, $connection_scada2);
    if( $_GET['date'] && $_GET['tank_id']){
      $data = query("SELECT b.description, a.tank_id, a.dailly_use/1000 dailly_use, datetime FROM  WWT_act_history a JOIN WWT_act_status b ON b.tank_id=a.tank_id WHERE CONVERT(date, datetime) ='". $_GET['date']."' AND a.tank_id='". $_GET['tank_id'] ."' AND b.description != 'INPUT PDAM' ORDER BY datetime DESC");
    }
    elseif( $_GET['date'] ){
      $data = query("SELECT b.description, a.tank_id, sum(CONVERT(float, a.dailly_use)/1000) AS daily_use, CONVERT(VARCHAR(10),datetime,111) AS date FROM  WWT_act_history a JOIN WWT_act_status b ON b.tank_id=a.tank_id WHERE CONVERT(date, datetime) ='". $_GET['date']."' AND b.description != 'INPUT PDAM' GROUP BY b.description, a.tank_id, CONVERT(VARCHAR(10),datetime,111) ORDER BY CONVERT(VARCHAR(10),datetime,111) DESC");
    }
    else if( $_GET['month'] && $_GET['tank_id'] ){
      $data = query("SELECT b.description, sum(CONVERT(float, a.dailly_use)/1000) AS daily_use, CONVERT(VARCHAR(10),datetime,111) AS date FROM  WWT_act_history a JOIN WWT_act_status b ON b.tank_id=a.tank_id WHERE FORMAT(datetime, 'yyyy-MM')='". $_GET['month']."' AND a.tank_id='". $_GET['tank_id'] ."' AND b.description != 'INPUT PDAM' GROUP BY  description, CONVERT(VARCHAR(10),datetime,111) ");
    }
    else if( $_GET['month'] ){
      $data = query("SELECT b.description, a.tank_id, sum(CONVERT(float, a.dailly_use)/1000) AS daily_use FROM  WWT_act_history a JOIN WWT_act_status b ON b.tank_id=a.tank_id WHERE FORMAT(datetime, 'yyyy-MM')='". $_GET['month']."' AND b.description != 'INPUT PDAM' GROUP BY b.description, a.tank_id");
    }
    else{
      $data = query("SELECT b.description, a.tank_id, sum(CONVERT(float, a.dailly_use)/1000) AS daily_use, CONVERT(VARCHAR(10),datetime,111) AS date FROM  WWT_act_history a JOIN WWT_act_status b ON b.tank_id=a.tank_id 
        WHERE b.description != 'INPUT PDAM' GROUP BY b.description, a.tank_id, CONVERT(VARCHAR(10),datetime,111) ORDER BY CONVERT(VARCHAR(10),datetime,111) DESC");
    }
    // debug($data);die;


?>

<div class="page-header">
<h3 class="page-title">Report Water House Monitoring - Distribution</h3>
</div>
<div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                  
                    <div class="form-inline">
                      
                      <div class="input-group mb-2 mr-sm-2">
                        <div class="input-group-prepend">
                          <div class="input-group-text">Filter By Date</div>
                        </div>
                        <input
                          type="date"
                          size="8"
                          id="date"
                          name="date"
                          class="form-control text-white"
                          placeholder="2022-07-31"
                        />
                        <input type="hidden" id="date_url" name="date_url" value="<?= ($_GET['date']) ? $_GET['date'] : '' ?>">
                      </div>
                      <div class="input-group mb-2 mr-sm-2">
                        <div class="input-group-prepend">
                          <div class="input-group-text">Filter By Month</div>
                        </div>
                        <input
                          type="month"
                          size="8"
                          name="month"
                          id="month"
                          class="form-control text-white"
                      
                          placeholder="2022-Juni"
                        />
                        <input type="hidden" id="month_url" name="month_url" value="<?= ($_GET['month']) ? $_GET['month'] : '' ?>"> 
                      </div>
                      <button
                        type="submit"
                        name="btnDownload" id="btnDownload"
                        class="form-control btn btn-inverse-success mb-2"
                      >
                        Export
                      </button>
                    </div>
                  </div>
                </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <?php if( $_GET['tank_id'] && $_GET['date'] ): ?> 
            <h4> Detail Tank: <?= $data[0]['description']; ?> | <?= date('d-F-Y', strtotime($_GET['date'])); ?></h4>
            <?php elseif( $_GET['tank_id'] && $_GET['month']): ?> 
            <h4>Detail Tank: <?= $data[0]['description']; ?> | <?= date('F Y', strtotime($_GET['month'])); ?></h4> 
            <?php endif; ?>
            <div class="table-responsive">
                      <table class="mytab table table-striped ">
                            <thead>
                                <tr>
                                        <th>#</th>
                                        <th>Date <?= ( $_GET['date'] && $_GET['tank_id'] ) ? " | TIME" :"" ?></th>
                                        <?php if( !$_GET['tank_id'] ): ?>
                                        <th>Tank Name</th>
                                        <?php endif; ?>
                                        <th>Total Consumption</th>
                                        <th>Cost</th>
                                        <?php if( !$_GET['tank_id'] ): ?>
                                        <th>Details</th>
                                        <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i=1; $total_usage = array(); ?>
                            <!-- DATE & TANK -->
                            <?php if( $data && $_GET['date'] && $_GET['tank_id'] ): ?>
                            <?php foreach($data as $row): ?>
                    
                                <tr style='color:#cedadc;'>
                                        <td class="py-1"><?= $i++; ?></td>
                                        <td class="py-1"><?= date('d-M-Y | H:i', strtotime($row['datetime'])) ?></td>
                                        <?php if( !$_GET['tank_id'] ): ?>
                                        <td class="py-1"><?= $row['description']; ?></td>
                                        <?php endif; ?>
                                        <td class="py-1"><?= number_format($row['dailly_use']); ?> M<sup>3</sup></td>
                                </tr>
                            <?php $total_usage[] = $row['dailly_use']; ?>
                            <?php endforeach; ?>
                                <tr style='color:#fff;'>
                                        <td style="background-color:#14334b;" colspan="<?= (!$_GET['tank_id']) ? 3 : 2; ?>">TOTAL CONSUMPTION</td>
                                        <td class="py-1"><?= number_format(array_sum($total_usage)); ?> M<sub>3</sub></td>
                                </tr>
                                  <!-- DATE -->
                                <?php elseif( $data && $_GET['date'] ): ?>
                                <?php foreach($data as $row): 
                                    $cost = $row['daily_use']*500;
                                ?>
                                 <tr style='color:#cedadc;'>
                                        <td class="py-1" ><?= $i++; ?></td>
                                        <td class="py-1"><?= date('d-M-Y', strtotime($row['date'])) ?></td>
                                        <?php if( !$_GET['tank_id'] ): ?>
                                        <td class="py-1"><?= $row['description']; ?></td>
                                        <?php endif; ?>
                                        <td class="py-1"><?= number_format($row['daily_use']); ?> M<sup>3</sup></td>
                                        <td class="py-1">Rp. <?= number_format($cost); ?></td>
                                        <td class="py-1"><a href="<?= $this_url; ?>&date=<?= $_GET['date']; ?>&tank_id=<?= $row['tank_id'] ?>"><i class="mdi mdi-table-edit text-primary" ></i></td>
                                </tr>
                                <?php $total_usage[] = $row['daily_use']; ?>
                                <?php $total_cost[] = $cost; ?>
                                <?php endforeach; ?>
                                <tr style='color:#fff;'>
                                        <td style="background-color:#14334b;" colspan="<?= (!$_GET['tank_id']) ? 3 : 2; ?>">TOTAL CONSUMPTION</td>
                                        <td class="py-1"><?= number_format(array_sum($total_usage)); ?> M<sub>3</sub></td>
                                        <td class="py-1">Rp. <?= number_format(array_sum($total_cost)); ?></td>
                                        <td class="py-1"></td>
                                </tr>
                                <!-- Bulan & Tanki -->
                                <?php elseif( $data && $_GET['month'] && $_GET['tank_id'] ): ?>
                                <?php foreach($data as $row): ?>
                                <tr style='color:#cedadc;'>
                                        <td class="py-1"><?= $i++; ?></td>
                                        <td class="py-1"><?= date('d-M-Y', strtotime($row['date'])) ?></td>
                                        <?php if( !$_GET['tank_id'] ): ?>
                                        <td class="py-1"><?= $row['description']; ?></td>
                                        <?php endif; ?>
                                        <td class="py-1"><?= number_format($row['daily_use']); ?> M<sup>3</sup></td>
                                        <td class="py-1"></td>
                                </tr>
                                <?php $total_usage[] = $row['daily_use']; ?>
                                <?php endforeach; ?>
                                <tr style='color:#fff;'>
                                        <td style="background-color:#14334b;" colspan="<?= (!$_GET['tank_id']) ? 3 : 2; ?>">TOTAL CONSUMPTION</td>
                                        <td class="py-1"><?= number_format(array_sum($total_usage)); ?> M<sub>3</sub></td>
                                </tr>
                                 <!-- BULAN -->
                                <?php elseif( $data && $_GET['month'] ): ?>
                                <?php foreach($data as $row): 
                                    $cost = $row['daily_use']*500;
                                ?>
                                <tr style='color:#cedadc;'>
                                        <td class="py-1"><?= $i++; ?></td>
                                        <td class="py-1"><?= date('M-Y', strtotime($_GET['month'])) ?></td>
                                        <?php if( !$_GET['tank_id'] ): ?>
                                        <td class="py-1"><?= $row['description']; ?></td>
                                        <?php endif; ?>
                                        <td class="py-1"><?= number_format($row['daily_use']); ?> M<sup>3</sup></td>
                                        <td class="py-1">Rp. <?= number_format($cost); ?> </td>
                                        <td class="py-1"><a href="<?= $this_url; ?>&month=<?= $_GET['month']; ?>&tank_id=<?= $row['tank_id'] ?>"><i class="mdi mdi-table-edit text-primary" ></i></td>
                                </tr>
                                <?php $total_usage[] = $row['daily_use']; ?>
                                <?php $total_cost[] = $cost; ?>
                                <?php endforeach; ?>
                                <tr style='color:#fff;'>
                                        <td style="background-color:#14334b;" colspan="<?= (!$_GET['tank_id']) ? 3 : 2; ?>">TOTAL CONSUMPTION</td>
                                        <td class="py-1"><?= number_format(array_sum($total_usage)); ?> M<sub>3</sub></td>
                                        <td class="py-1">Rp. <?= number_format(array_sum($total_cost)); ?></td>
                                        <td class="py-1"></td>
                                </tr>
                                <?php elseif( $_GET['month'] OR $_GET['date'] AND !$data ): ?>
                                    <tr>
                                        <td style="text-align:center!important" colspan="5">
                                        <div class="alert alert-primary" role="alert">
                                        No Data Available 
                                        </div> 
                                        </td>
                                    </tr>
                                <?php else: ?>    
                                    <tr>
                                        <td style="text-align:center!important" colspan="5">
                                        <div class="alert alert-primary" role="alert">
                                        Please use the filter menu
                                        </div> 
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/jquery-3.5.1.min.js"></script> 
<script>

    $('#date').on('change', function () {
      var date = $("#date").val();
      var url = "<?= $this_url ?>&date="+date; 
      if (url) { 
        window.location = url; 
      }
      return false;
    });
    $('#date').val($("#date_url").val());
    
    
    $('#month').on('change', function () {
      var month = $("#month").val();
      var url = "<?= $this_url ?>&month="+month; 
      if (url) { 
        window.location = url; 
      }
      return false;
    });
    $('#month').val($("#month_url").val());

    $("#btnDownload").on('click', function() {
      var date = $("#date_url").val();
      var month = $("#month_url").val();

      var fileRpt = "report_water_excel.php?date=" + date+"&month=" + month;
      // alert(fileRpt);
      window.location.href = fileRpt;
    });
  </script>