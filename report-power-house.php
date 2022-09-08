<?php

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
// $this_url = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']."?page=report-power-house"    ;
$this_url = $_SERVER['PHP_SELF']."?page=report-power-house"    ;

$data = query("SELECT * FROM PHouse_act_history");
// debug($data);die;

if( $_GET['date'] && $_GET['panel_id']){

  $data = query("SELECT panel_id, kwh, datetime FROM  PHouse_act_history WHERE CONVERT(date, datetime) ='". $_GET['date']."' AND panel_id='". $_GET['panel_id'] ."' ORDER BY datetime DESC");
}
elseif( $_GET['date'] ){

  // $data = query("SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use, CONVERT(VARCHAR(10),datetime,111) AS date FROM  PHouse_act_history WHERE CONVERT(date, datetime) ='". $_GET['date']."' GROUP BY panel_id, CONVERT(VARCHAR(10),datetime,111) ORDER BY CONVERT(VARCHAR(10),datetime,111) DESC");
  $data = query("SELECT sum(CONVERT(float, daily_use)) AS daily_use, panel_id, date, sum(CONVERT(float, cost_energy)) AS cost_energy FROM
      (
        SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use, CONVERT(VARCHAR(10),datetime,111) AS date, sum(CONVERT(float, kwh))*1553.67 cost_energy FROM [SCADAP2].[dbo].[PHouse_act_history] WHERE datetime BETWEEN '". $_GET['date']." 18:00' AND '". $_GET['date']." 22:59' 
        GROUP BY panel_id, CONVERT(VARCHAR(10),datetime,111) 
        UNION ALL
        SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use, CONVERT(VARCHAR(10),datetime,111) AS date, sum(CONVERT(float, kwh))*1035.78 cost_energy FROM [SCADAP2].[dbo].[PHouse_act_history] WHERE (datetime BETWEEN '". $_GET['date']." 00:00' AND '". $_GET['date']." 18:00') OR (datetime BETWEEN '". $_GET['date']." 23:00' AND '". $_GET['date']." 23:59') 
        GROUP BY panel_id, CONVERT(VARCHAR(10),datetime,111) 
      ) TBL 
      WHERE SUBSTRING(panel_id,1,4) != 'DIST' 
      GROUP BY panel_id, date"
  );
}
else if( $_GET['month'] && $_GET['panel_id'] ){

  // $data = query("SELECT sum(CONVERT(float, kwh)) AS daily_use, CONVERT(VARCHAR(10),datetime,111) AS date FROM  PHouse_act_history WHERE FORMAT(datetime, 'yyyy-MM')='". $_GET['month']."' AND panel_id='". $_GET['panel_id'] ."' GROUP BY CONVERT(VARCHAR(10),datetime,111) ");

  $data = query("SELECT sum(CONVERT(float, daily_use)) AS daily_use, panel_id, sum(CONVERT(float, cost_energy)) AS cost_energy, date FROM
    (
      SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use, sum(CONVERT(float, kwh))*1553.67 cost_energy, CONVERT(VARCHAR(10),datetime,111) AS date 
      FROM [SCADAP2].[dbo].[PHouse_act_history] WHERE FORMAT(datetime, 'yyyy-MM') = '". $_GET['month']."' AND CONVERT(VARCHAR(5), datetime, 114) BETWEEN '18:00' AND '22:59' 
      AND panel_id='". $_GET['panel_id'] ."'
      GROUP BY panel_id, CONVERT(VARCHAR(10),datetime,111) 
      UNION ALL
      SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use, sum(CONVERT(float, kwh))*1035.78 cost_energy, CONVERT(VARCHAR(10),datetime,111) AS date 
      FROM [SCADAP2].[dbo].[PHouse_act_history] WHERE FORMAT(datetime, 'yyyy-MM') = '". $_GET['month']."' 
      AND ((CONVERT(VARCHAR(5), datetime, 114) BETWEEN '00:00' AND '18:00') OR (CONVERT(VARCHAR(5), datetime, 114) BETWEEN '23:00' AND '23:59') )
      AND panel_id='". $_GET['panel_id'] ."'
      GROUP BY panel_id, CONVERT(VARCHAR(10),datetime,111) 
    ) TBL 
    WHERE SUBSTRING(panel_id,1,4) != 'DIST' 
    GROUP BY panel_id, date
  ");
}
else if( $_GET['month'] ){

  //$data = query("SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use FROM  PHouse_act_history WHERE FORMAT(datetime, 'yyyy-MM')='". $_GET['month']."' GROUP BY panel_id");
  $data = query("SELECT sum(CONVERT(float, daily_use)) AS daily_use, panel_id, sum(CONVERT(float, cost_energy)) AS cost_energy FROM
    (
      SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use, sum(CONVERT(float, kwh))*1553.67 cost_energy 
      FROM [SCADAP2].[dbo].[PHouse_act_history] WHERE FORMAT(datetime, 'yyyy-MM') = '". $_GET['month']."' AND CONVERT(VARCHAR(5), datetime, 114) BETWEEN '18:00' AND '22:59' 
      GROUP BY panel_id
      UNION ALL
      SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use, sum(CONVERT(float, kwh))*1035.78 cost_energy 
      FROM [SCADAP2].[dbo].[PHouse_act_history] WHERE FORMAT(datetime, 'yyyy-MM') = '". $_GET['month']."' 
      AND( (CONVERT(VARCHAR(5), datetime, 114) BETWEEN '00:00' AND '18:00') OR (CONVERT(VARCHAR(5), datetime, 114) BETWEEN '23:00' AND '23:59') )
      GROUP BY panel_id
    ) TBL 
    WHERE SUBSTRING(panel_id,1,4) != 'DIST' 
    GROUP BY panel_id
  ");

}
else{

  $data = query("SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use, CONVERT(VARCHAR(10),datetime,111) AS date FROM  PHouse_act_history GROUP BY panel_id, CONVERT(VARCHAR(10),datetime,111) ORDER BY CONVERT(VARCHAR(10),datetime,111) DESC");
}

?>

<div class="page-header">
<h3 class="page-title">Report Power House Monitoring</h3>
</div>
<div class="row">
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
         
                  <?php if( $_GET['panel_id'] && $_GET['date'] ): ?> 
                    <h4 style="font-size: 18px !important; margin-top: 10px !important; margin-bottom: 10px !important"> 
                      Detail Consumption: <?= $_GET['panel_id']; ?> | <?= date('d-F-Y', strtotime($_GET['date'])); ?>
                    </h4>
                  <?php elseif( $_GET['panel_id'] && $_GET['month']): ?> 
                    <h4 style="font-size: 18px !important; margin-top: 10px !important; margin-bottom: 10px !important"> 
                      Detail Consumption: <?= $_GET['panel_id']; ?> | <?= date('F Y', strtotime($_GET['month'])); ?>
                    </h4>
                  <?php endif; ?>
                    <div class="table-responsive">
                      <table class="mytab table table-striped">
                        <thead>
                          <tr>
                            <th> # </th>
                            <th> Date <?php ( $_GET['date'] && $_GET['panel_id'] ) ? " | TIME" :"" ?></th>
                            <?php if( !$_GET['panel_id'] ): ?>
                            <th> Panel </th>
                            <?php endif; ?>
                            <th> Total Consumption </th>
                            <?php if( $_GET['month'] && !$_GET['panel_id'] ): ?>
                            <th> Details </th>
                            <?php endif; ?>
                            <th> Cost Energy </th>
                            <th> Co2 Emission </th>
                            <th> Cost Co2 </th>
                          </tr>
                        </thead>
                        <tbody>

                    <?php $i=1; $total_usage = array(); ?>

                    <!-- DATE & TANK -->
                    <?php if( $data && $_GET['date'] && $_GET['panel_id'] ): ?>
                        <?php foreach($data as $row): ?>
                          <?php $desc = query("SELECT TOP(1) description FROM PHouse_act_status WHERE panel_id='$row[panel_id]'")[0]['description']; ?>
                          <tr style='color:#cedadc;'>
                            <td class="py-1"><?= $i++; ?></td>
                            <td> <?= date('d-M-Y | H:i', strtotime($row['date'])); ?> </td>
                            <?php if( !$_GET['panel_id'] ): ?>
                            <td><?= $desc ?></td>
                            <?php endif; ?>
                            <td> <?= number_format($row['kwh']); ?> KWH </td>
                          </tr>  
                          <?php $total_usage[] = $row['kwh']; ?>
                          <?php endforeach; ?>
                          <tr style='color:#cedadc;'>
                            <td class="py-1" colspan="<?= (!$_GET['panel_id']) ? 3 : 2; ?>">TOTAL CONSUMPTION</td>
                            <td><?= number_format(array_sum($total_usage)); ?>  kWh </td>
                          </tr> 

                    <!-- DATE -->
                    <?php elseif( $data && $_GET['date'] ): ?>
                    <?php foreach($data as $row): ?>
                    <?php $desc = query("SELECT TOP(1) description FROM PHouse_act_status WHERE panel_id='$row[panel_id]'")[0]['description']; ?>
                          <tr style='color:#cedadc;'>
                            <td class="py-1"><?= $i++; ?></td>
                            <td> <?= date('d-M-Y', strtotime($row['date'])) ;?> </td>
                            <?php if( !$_GET['panel_id'] ): ?>
                            <td><?= $desc; ?></td>
                        <?php endif; 
                          // $cost_energy = $row['daily_use']*1444.70;
                          $cost_energy = $row['cost_energy'];
                          $co2 = $row['daily_use']*0.870;
                          $cost_co2 = $co2*30;
                        ?>
                            <td> <?= number_format($row['daily_use']); ?>  KWH </td>
                            <!-- <td> <i class="mdi mdi-table-edit text-primary" size></i>  </td> -->
                            <td> Rp. <?= number_format($cost_energy); ?> </td>
                            <td> <?= number_format($co2); ?> kg-CO2 </td>
                            <td> Rp. <?= number_format($cost_co2); ?> </td>
                          </tr>
                          <?php $total_usage[] = $row['daily_use']; ?>
                          <?php $total_cost_energy[] = $cost_energy; ?>
                          <?php $total_co2[] = $co2; ?>
                          <?php $total_cost_co2[] = $cost_co2; ?>
                    <?php endforeach; ?>
                          <tr  class="text-white">
                            <td style="background-color:#14334b;" colspan="<?= (!$_GET['panel_id']) ? 3 : 2; ?>">TOTAL CONSUMPTION</td>
                            <td><?= number_format(array_sum($total_usage)); ?>  KWH </td>
                            <td> Rp. <?= number_format(array_sum($total_cost_energy)); ?> </td>
                            <td> <?= number_format(array_sum($total_co2)); ?>  kg-CO2 </td>
                            <td> Rp. <?= number_format(array_sum($total_cost_co2)); ?></td>
                          </tr>
                     <!-- Bulan & Tanki -->
                      <?php elseif( $data && $_GET['month'] && $_GET['panel_id'] ): ?>
                      <?php foreach($data as $row): ?>   
                      <?php $desc = query("SELECT TOP(1) description FROM PHouse_act_status WHERE panel_id='$row[panel_id]'")[0]['description']; ?>   
                      <tr style='color:#cedadc;'>
                            <td class="py-1"><?php $i++; ?></td>
                            <td> <?= date('d-M-Y', strtotime($row['date'])) ;?> </td>
                            <?php if( !$_GET['panel_id'] ): ?>
                            <td><?= $desc; ?></td>
                            <?php endif; 
                              $cost_energy = $row['cost_energy'];
                              $co2 = $row['daily_use']*0.870;
                              $cost_co2 = $co2*30;
                            ?>
                            <td> <?= number_format($row['daily_use']); ?> KWH  </td>
                            <!-- <td> <i class="mdi mdi-table-edit text-primary" size></i>  </td> -->
                            <td> Rp. <?= number_format($cost_energy); ?> </td>
                            <td> <?= number_format($co2); ?> kg-CO2 </td>
                            <td> Rp. <?= number_format($cost_co2); ?> </td>
                      </tr>
                          <?php $total_usage[] = $row['daily_use']; ?>
                          <?php $total_cost_energy[] = $cost_energy; ?>
                          <?php $total_co2[] = $co2; ?>
                          <?php $total_cost_co2[] = $cost_co2; ?>
                        <?php endforeach; ?>
                        <tr  class="text-white">
                            <td style="background-color:#14334b;" colspan="<?= (!$_GET['panel_id']) ? 3 : 2; ?>">TOTAL CONSUMPTION</td>
                            <td><?= number_format(array_sum($total_usage)); ?>  KWH </td>
                            <td> Rp. <?= number_format(array_sum($total_cost_energy)); ?> </td>
                            <td> <?= number_format(array_sum($total_co2)); ?>  kg-CO2 </td>
                            <td> Rp. <?= number_format(array_sum($total_cost_co2)); ?></td>
                          </tr>
                  <!-- BULAN -->
                   <?php elseif( $data && $_GET['month'] ): ?>
                   <?php foreach($data as $row): ?>
                   <?php $desc = query("SELECT TOP(1) description FROM PHouse_act_status WHERE panel_id='$row[panel_id]'")[0]['description']; ?>
                        <tr style='color:#cedadc;'>
                            <td class="py-1"><?= $i++; ?></td>
                            <td> <?= date('M-Y', strtotime($_GET['month'])) ;?> </td>
                            <?php if( !$_GET['panel_id'] ): ?>
                            <td><?= $desc; ?></td>
                            <?php endif; 
                              $cost_energy = $row['cost_energy'];
                              $co2 = $row['daily_use']*0.870;
                              $cost_co2 = $co2*30;
                            ?>
                            <td> <?= number_format($row['daily_use']);?> KWH  </td>
                            <td>  <a href="<?= $this_url; ?>&month=<?= $_GET['month']; ?>&panel_id=<?= $row['panel_id'] ?>"><i class="mdi mdi-table-edit text-primary" ></i> </a> </td>
                            <td> Rp. <?= number_format($cost_energy); ?> </td>
                            <td> <?= number_format($co2); ?> kg-CO2 </td>
                            <td> Rp. <?= number_format($cost_co2); ?> </td>
                        </tr>
                        <?php $total_usage[] = $row['daily_use']; ?>
                          <?php $total_cost_energy[] = $cost_energy; ?>
                          <?php $total_co2[] = $co2; ?>
                          <?php $total_cost_co2[] = $cost_co2; ?>
                        <?php endforeach; ?>
                        <tr  class="text-white">
                            <td style="background-color:#14334b;" colspan="<?= (!$_GET['panel_id']) ? 3 : 2; ?>">TOTAL CONSUMPTION</td>
                            <td><?= number_format(array_sum($total_usage)); ?>  KWH </td>
                            <td></td>
                            <td> Rp. <?= number_format(array_sum($total_cost_energy));  ?> </td>
                            <td> <?= number_format(array_sum($total_co2)); ?>  kg-CO2 </td>
                            <td> Rp. <?= number_format(array_sum($total_cost_co2)); ?></td>
                          </tr>
                          <?php elseif( $_GET['month'] OR $_GET['date'] AND !$data ): ?>
                          <tr>
                            <td colspan="8">
                              <div class="alert alert-primary" role="alert">
                               No Data Available 
                              </div> 
                            </td>
                          </tr>
                          <?php else: ?>
                          <tr>
                            <td style="text-align:center!important" colspan="8">
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

      var fileRpt = "report_power_house_excel.php?date=" + date+"&month=" + month;
      // alert(fileRpt);
      // console.log(fileRpt)
      window.location.href = fileRpt;
    });

  </script>