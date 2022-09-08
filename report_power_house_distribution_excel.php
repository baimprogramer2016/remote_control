<?php
session_start();
if($_SESSION['npk'] == "")
{
  header("location:index.php");
}
  header("Pragma: public" );
  header("Expires: 0" );
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0" );
  header("Content-Type: application/force-download" );
  header("Content-Type: application/octet-stream" );
  header("Content-Type: application/download" );;
  header("Content-Disposition: attachment;filename=Report_PHouse_line.xls " );
  error_reporting(0);
  ?>
<?php

	function query($query)
	{
    include('configs/db_scadap2.php');
		// global $connection_scada2;
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

  // =============================================================================
	// PREPARED DATA
	// =============================================================================

  $this_url = $_SERVER['PHP_SELF']."?page=report-power-house-distribution"    ;
  $this_title = '';

  // DO UPDATE First / Deny Error
  $queryUpdate = "UPDATE PHouse_act_history SET kwh='0' WHERE kwh='NaN' OR kwh='undefined'";
  $update = mssql_query($queryUpdate, $scada_p2);

  $data = query("SELECT * FROM PHouse_act_history");
  // debug($data);die;
  if( $_GET['date'] && $_GET['panel_id']){
    $data = query("SELECT panel_id, kwh, datetime FROM  PHouse_act_history WHERE CONVERT(date, datetime) ='". $_GET['date']."' AND panel_id='". $_GET['panel_id'] ."' ORDER BY datetime DESC");
  }
  elseif( $_GET['date'] != ''){
    // $data = query("SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use, CONVERT(VARCHAR(10),datetime,111) AS date FROM  PHouse_act_history WHERE CONVERT(date, datetime) ='". $_GET['date']."' GROUP BY panel_id, CONVERT(VARCHAR(10),datetime,111) ORDER BY CONVERT(VARCHAR(10),datetime,111) DESC");
    $data = query("SELECT sum(CONVERT(float, daily_use)) AS daily_use, panel_id, date, sum(CONVERT(float, cost_energy)) AS cost_energy FROM
        (
          SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use, CONVERT(VARCHAR(10),datetime,111) AS date, sum(CONVERT(float, kwh))*1553.67 cost_energy FROM [SCADAP2].[dbo].[PHouse_act_history] WHERE datetime BETWEEN '". $_GET['date']." 18:00' AND '". $_GET['date']." 22:59' 
          GROUP BY panel_id, CONVERT(VARCHAR(10),datetime,111) 
          UNION ALL
          SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use, CONVERT(VARCHAR(10),datetime,111) AS date, sum(CONVERT(float, kwh))*1035.78 cost_energy FROM [SCADAP2].[dbo].[PHouse_act_history] WHERE (datetime BETWEEN '". $_GET['date']." 00:00' AND '". $_GET['date']." 18:00') OR (datetime BETWEEN '". $_GET['date']." 23:00' AND '". $_GET['date']." 23:59') 
          GROUP BY panel_id, CONVERT(VARCHAR(10),datetime,111) 
        ) TBL 
        WHERE SUBSTRING(panel_id,1,4) = 'DIST' 
        GROUP BY panel_id, date"
    );

    $this_title = strtoupper(date('d F Y', strtotime($_GET['date'])));
  }
  else if( $_GET['month'] && $_GET['panel_id'] ){
    $data = query("SELECT sum(CONVERT(float, kwh)) AS daily_use, CONVERT(VARCHAR(10),datetime,111) AS date FROM  PHouse_act_history WHERE FORMAT(datetime, 'yyyy-MM')='". $_GET['month']."' AND panel_id='". $_GET['panel_id'] ."' GROUP BY CONVERT(VARCHAR(10),datetime,111) ");
  }
  else if( $_GET['month'] != ''){
    //$data = query("SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use FROM  PHouse_act_history WHERE FORMAT(datetime, 'yyyy-MM')='". $_GET['month']."' GROUP BY panel_id");
    $data = query("SELECT sum(CONVERT(float, daily_use)) AS daily_use, panel_id, sum(CONVERT(float, cost_energy)) AS cost_energy FROM
      (
        SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use, sum(CONVERT(float, kwh))*1553.67 cost_energy 
        FROM [SCADAP2].[dbo].[PHouse_act_history] WHERE FORMAT(datetime, 'yyyy-MM') = '". $_GET['month']."' AND CONVERT(VARCHAR(5), datetime, 114) BETWEEN '18:00' AND '22:59' 
        GROUP BY panel_id
        UNION ALL
        SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use, sum(CONVERT(float, kwh))*1035.78 cost_energy 
        FROM [SCADAP2].[dbo].[PHouse_act_history] WHERE FORMAT(datetime, 'yyyy-MM') = '". $_GET['month']."' 
        AND ((CONVERT(VARCHAR(5), datetime, 114) BETWEEN '00:00' AND '18:00') OR (CONVERT(VARCHAR(5), datetime, 114) BETWEEN '23:00' AND '23:59') )
        GROUP BY panel_id
      ) TBL 
      WHERE SUBSTRING(panel_id,1,4) = 'DIST' 
      GROUP BY panel_id
    ");

    $this_title = strtoupper('BULAN '.date('F Y', strtotime($_GET['month'])));
  }
  else{
    $data = query("SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use, CONVERT(VARCHAR(10),datetime,111) AS date FROM  PHouse_act_history GROUP BY panel_id, CONVERT(VARCHAR(10),datetime,111) ORDER BY CONVERT(VARCHAR(10),datetime,111) DESC");
  }


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
   "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>LPH Portal</title>
	
  <!-- Source File -->

	
  <style>
  .tdNo
  {
    width:10px!important;
    text-align:center!important;
  }
  </style>

	<style>
	.heading
	{
		width: 200px;
	}
	</style>
</head>
<body>
	<div id="outerContainer">

		<div id="bodyContainer">
		  <!-- <div id="pageBodyTitle">Report Konsumsi Listrik Bulan Juni 2022</div> -->
      
	  	<br>
      <?php if( $_GET['panel_id'] && $_GET['date'] ): ?> 
        <h2 style="font-size: 18px !important; margin-top: 10px !important; margin-bottom: 10px !important"> 
          DETAIL CONSUMPTION: <?= $_GET['panel_id']; ?> | <?= date('d-F-Y', strtotime($_GET['date'])); ?>
        </h2>
      <?php elseif( $_GET['panel_id'] && $_GET['month']): ?> 
        <h2 style="font-size: 18px !important; margin-top: 10px !important; margin-bottom: 10px !important"> 
          DETAIL CONSUMPTION: <?= $_GET['panel_id']; ?> | <?= date('F Y', strtotime($_GET['month'])); ?>
        </h2>
      <?php endif; ?>
      <table id="searchTable">	
        <thead>
          <tr>
            <td rowspan="2" colspan="7" class="heading" style="text-align:center!important;border:thin gray solid;font-size: 26px;">
              <center><b>REPORT KONSUMSI LISTRIK <?php echo $this_title; ?></b></center></td>
          </tr>
          <tr>
          </tr>
          <tr>
            <td class="heading" style="color: white;background-color: #0066CC;text-align:center!important; width: 50px !important;border:thin gray solid;"><center><b>NO</b></center></td>
            <td class="heading" style="color: white;background-color: #0066CC;text-align:center!important;border:thin gray solid;"><center><b>DATE<?= ( $_GET['date'] && $_GET['panel_id'] ) ? " | TIME" :"" ?></b></center></td>
            <?php if( !$_GET['panel_id'] ): ?>
            <td class="heading" style="color: white;background-color: #0066CC;text-align:center!important;border:thin gray solid;"><center><b>PANEL</b></center></td>
            <?php endif; ?>
            <td class="heading" style="color: white;background-color: #0066CC;text-align:center!important;border:thin gray solid;"><center><b>TOTAL CONSUMPTION</b></center></td>
         
            <td class="heading" style="color: white;background-color: #0066CC;text-align:center!important;border:thin gray solid;"><center><b>COST ENERGY</b></center></td>
            <td class="heading" style="color: white;background-color: #0066CC;text-align:center!important;border:thin gray solid;"><center><b>CO2 EMISSION</b></center></td>
            <td class="heading" style="color: white;background-color: #0066CC;text-align:center!important;border:thin gray solid;"><center><b>COST CO2</b></center></td>
          </tr>
        </thead>
        <tbody>
          <?php $i=1; $total_usage = array(); ?>

            <!-- DATE & TANK -->
            <?php if( $data && $_GET['date'] && $_GET['panel_id'] ): ?>
              <?php foreach($data as $row): ?>
                <?php $desc = query("SELECT TOP(1) description FROM PHouse_act_status WHERE panel_id='$row[panel_id]'")[0]['description']; ?>
                <tr>
                  <td style="text-align:center"><?= $i++; ?></td>
                  <td style="text-align:center"><?= date('d-M-Y | H:i', strtotime($row['date'])) ?></td>
                  <?php if( !$_GET['panel_id'] ): ?>
                  <td style="text-align:center"><?= $desc ?></td>
                  <?php endif; ?>
                  <td style="text-align:center"><?= number_format($row['kwh']); ?> KWH</sup></td>
                  
                </tr>
                <?php $total_usage[] = $row['kwh']; ?>
              <?php endforeach; ?>
              <tr>
                <td style="text-align:center;font-weight: bold;color: red;" colspan="<?= (!$_GET['panel_id']) ? 3 : 2; ?>"><center><b>TOTAL CONSUMPTION</b></center></td>
                <td colspan="1" style="text-align:right;font-weight: bold;"><?= number_format(array_sum($total_usage)); ?> kWh</sub></td>
              </tr>
            
            
            <!-- DATE -->
            <?php elseif( $data && $_GET['date'] ): ?>
              <?php foreach($data as $row): ?>
                <?php $desc = query("SELECT TOP(1) description FROM PHouse_act_status WHERE panel_id='$row[panel_id]'")[0]['description']; ?>
                <tr>
                  <td style="text-align:center;border:thin gray solid;"><?= $i++; ?></td>
                  <td style="text-align:center;border:thin gray solid;"><?= date('d-M-Y', strtotime($row['date'])) ?></td>
                  <?php if( !$_GET['panel_id'] ): ?>
                  <td style="text-align:center;border:thin gray solid;"><?= $desc; ?></td>
                  <?php endif; 
                    // $cost_energy = $row['daily_use']*1444.70;
                    $cost_energy = $row['cost_energy'];
                    $co2 = $row['daily_use']*0.870;
                    $cost_co2 = $co2*30;
                  ?>
                  <td style="text-align:right;;border:thin gray solid;"><?= number_format($row['daily_use']); ?> KWH</sup></td>
                  <td style="text-align:right;;border:thin gray solid;">Rp. <?= number_format($cost_energy); ?></sup></td>
                  <td style="text-align:right;;border:thin gray solid;"><?= number_format($co2); ?> kg-CO2 </sup></td>
                  <td style="text-align:right;;border:thin gray solid;">Rp. <?= number_format($cost_co2); ?></sup></td>
                </tr>
                <?php $total_usage[] = $row['daily_use']; ?>
                <?php $total_cost_energy[] = $cost_energy; ?>
                <?php $total_co2[] = $co2; ?>
                <?php $total_cost_co2[] = $cost_co2; ?>
              <?php endforeach; ?>
              <tr>
                <td style="text-align:center;border:thin gray solid;font-weight: bold;color: red;" colspan="<?= (!$_GET['panel_id']) ? 3 : 2; ?>"><center><b>TOTAL CONSUMPTION</b></center></td>
                <td style="text-align:right;border:thin gray solid;font-weight: bold;"><?= number_format(array_sum($total_usage)); ?> kWh</td>
                <td style="text-align:right;border:thin gray solid;font-weight: bold;">Rp. <?= number_format(array_sum($total_cost_energy)); ?></td>
                <td style="text-align:right;border:thin gray solid;font-weight: bold;"><?= number_format(array_sum($total_co2)); ?>  kg-CO2</td>
                <td style="text-align:right;border:thin gray solid;font-weight: bold;">Rp. <?= number_format(array_sum($total_cost_co2)); ?></td>
              </tr>
            
            
            <!-- Bulan & Tanki -->
            <?php elseif( $data && $_GET['month'] && $_GET['panel_id'] ): ?>
              <?php foreach($data as $row): ?>
                <?php $desc = query("SELECT TOP(1) description FROM PHouse_act_status WHERE panel_id='$row[panel_id]'")[0]['description']; ?>
                
                <tr>
                  <td style="text-align:center;border:thin gray solid;"><?= $i++; ?></td>
                  <td style="text-align:center;border:thin gray solid;"><?= date('d-M-Y', strtotime($row['date'])) ?></td>
                  <?php if( !$_GET['panel_id'] ): ?>
                  <td style="text-align:center;border:thin gray solid;"><?= $desc; ?></td>
                  <?php endif; ?>
                  <td style="text-align:center;border:thin gray solid;"><?= number_format($row['daily_use']); ?> KWH</sup></td>
                </tr>
                <?php $total_usage[] = $row['daily_use']; ?>
              <?php endforeach; ?>
              <tr>
                <td style="text-align:center;border:thin gray solid;font-weight: bold;color: red;" colspan="<?= (!$_GET['panel_id']) ? 3 : 2; ?>"><center><b>TOTAL CONSUMPTION</b></center></td>
                <td colspan="1" style="text-align:center;border:thin gray solid;font-weight: bold;"><?= number_format(array_sum($total_usage)); ?> kWh</sub></td>
              </tr>
            
            
            <!-- BULAN -->
            <?php elseif( $data && $_GET['month'] ): ?>
              <?php foreach($data as $row): ?>
                <?php $desc = query("SELECT TOP(1) description FROM PHouse_act_status WHERE panel_id='$row[panel_id]'")[0]['description']; ?>

                <?php 
                  $datadet = query("SELECT sum(CONVERT(float, daily_use)) AS daily_use, panel_id, sum(CONVERT(float, cost_energy)) AS cost_energy, date FROM
                  (
                    SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use, sum(CONVERT(float, kwh))*1553.67 cost_energy, CONVERT(VARCHAR(10),datetime,111) AS date 
                    FROM [SCADAP2].[dbo].[PHouse_act_history] WHERE FORMAT(datetime, 'yyyy-MM') = '". $_GET['month']."' AND CONVERT(VARCHAR(5), datetime, 114) BETWEEN '18:00' AND '22:59' 
                    AND panel_id='". $row['panel_id'] ."'
                    GROUP BY panel_id, CONVERT(VARCHAR(10),datetime,111) 
                    UNION ALL
                    SELECT panel_id, sum(CONVERT(float, kwh)) AS daily_use, sum(CONVERT(float, kwh))*1035.78 cost_energy, CONVERT(VARCHAR(10),datetime,111) AS date 
                    FROM [SCADAP2].[dbo].[PHouse_act_history] WHERE FORMAT(datetime, 'yyyy-MM') = '". $_GET['month']."' 
                    AND ((CONVERT(VARCHAR(5), datetime, 114) BETWEEN '00:00' AND '18:00') OR (CONVERT(VARCHAR(5), datetime, 114) BETWEEN '23:00' AND '23:59') )
                    AND panel_id='". $row['panel_id'] ."'
                    GROUP BY panel_id, CONVERT(VARCHAR(10),datetime,111) 
                  ) TBL 
                  WHERE SUBSTRING(panel_id,1,4) != 'DIST' 
                  GROUP BY panel_id, date
                ");
                ?>

                <tr>
                  <td style="text-align:center;border:thin gray solid;"><?= $i++; ?></td>
                  <td style="text-align:center;border:thin gray solid;"><?= date('M-Y', strtotime($_GET['month'])) ?></td>
                  <?php if( !$_GET['panel_id'] ): ?>
                  <td style="text-align:center;border:thin gray solid;"><?= $desc; ?></td>
                  <?php endif; 
                    $cost_energy = $row['cost_energy'];
                    $co2 = $row['daily_use']*0.870;
                    $cost_co2 = $co2*30;
                  ?>
                  <td style="text-align:center;border:thin gray solid;"><?= number_format($row['daily_use']); ?> KWH</sup></td>
                  <td style="text-align:right;;border:thin gray solid;">Rp. <?= number_format($cost_energy); ?></sup></td>
                  <td style="text-align:right;;border:thin gray solid;"><?= number_format($co2); ?> kg-CO2 </sup></td>
                  <td style="text-align:right;;border:thin gray solid;">Rp. <?= number_format($cost_co2); ?></sup></td>
                </tr>
                <?php foreach($datadet as $rowdet): 
                    $cost_energydet = $rowdet['cost_energy'];
                    $co2det = $rowdet['daily_use']*0.870;
                    $cost_co2det = $co2det*30;
                  ?>
                      <tr>
                        <td colspan="3" style="text-align:center;border:thin gray solid;"><?= date('d-M-Y', strtotime($rowdet['date'])) ?></td>
                        <td style="text-align:right;;border:thin gray solid;"><?= number_format($rowdet['daily_use']); ?> KWH</sup></td>
                        <td style="text-align:right;;border:thin gray solid;">Rp. <?= number_format($cost_energydet); ?></sup></td>
                        <td style="text-align:right;;border:thin gray solid;"><?= number_format($co2det); ?> kg-CO2 </sup></td>
                        <td style="text-align:right;;border:thin gray solid;">Rp. <?= number_format($cost_co2det); ?></sup></td>
                      </tr>
                <?php endforeach; ?>

                <?php $total_usage[] = $row['daily_use']; ?>
                <?php $total_cost_energy[] = $cost_energy; ?>
                <?php $total_co2[] = $co2; ?>
                <?php $total_cost_co2[] = $cost_co2; ?>
              <?php endforeach; ?>
              <tr>
                <td style="text-align:center;border:thin gray solid;font-weight: bold;color: red;" colspan="<?= (!$_GET['panel_id']) ? 3 : 2; ?>">TOTAL MONTH CONSUMPTION</td>
                <td colspan="1" style="text-align:right;border:thin gray solid;font-weight: bold;"><?= number_format(array_sum($total_usage)); ?> kWh</td>
                <td colspan="1" style="text-align:right;border:thin gray solid;font-weight: bold;">Rp. <?= number_format(array_sum($total_cost_energy)); ?></td>
                <td colspan="1" style="text-align:right;border:thin gray solid;font-weight: bold;"><?= number_format(array_sum($total_co2)); ?> kg-CO2 </td>
                <td colspan="1" style="text-align:right;border:thin gray solid;font-weight: bold;">Rp. <?= number_format(array_sum($total_cost_co2)); ?></td>
              </tr>
            
            <?php elseif( $_GET['month'] OR $_GET['date'] AND !$data ): ?>
              <tr>
                <td style="text-align:center;border:thin gray solid;" colspan="8">Belum ada data pada waktu tersebut </td>
              </tr>
            <?php else: ?>
            <tr>
              <td style="text-align:center;border:thin gray solid;" colspan="8">Silahkan gunakan filter untuk melihat data</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
	  </div>
	</div>
  <script>
    $('#date').on('change', function () {
      var date = $("#date").val();
      var url = "<?= $this_url ?>?date="+date; 
      if (url) { 
        window.location = url; 
      }
      return false;
    });
    $('#date').val($("#date_url").val());
    
    
    $('#month').on('change', function () {
      var month = $("#month").val();
      var url = "<?= $this_url ?>?month="+month; 
      if (url) { 
        window.location = url; 
      }
      return false;
    });
    $('#month').val($("#month_url").val());


    $("#btnDownload").on('click', function() {
      var date = $("#date_url").val();

      var fileRpt = "down_phouse_line.php?date=" + date;
      window.location.href = fileRpt;
    });

  </script>
</body>
</html>
