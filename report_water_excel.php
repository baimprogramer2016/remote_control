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
  header("Content-Disposition: attachment;filename=Report_WWT.xls " );
  error_reporting(0);
  ?>
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

  // =============================================================================
	// PREPARED DATA
	// =============================================================================

  $this_url = "https://newlph.akebono-astra.co.id/report_wwt.php";

  // DO UPDATE First / Deny Error
  $query = "UPDATE WWT_act_history SET dailly_use = '0' WHERE dailly_use = 'NaN' OR dailly_use ='undefined'";
  $update = mssql_query($query, $scada_p2);
  if( $_GET['date'] && $_GET['tank_id']){
    $data = query("SELECT tank_id, dailly_use, datetime FROM  WWT_act_history WHERE CONVERT(date, datetime) ='". $_GET['date']."' AND tank_id='". $_GET['tank_id'] ."' ORDER BY datetime DESC");
  }
  elseif( $_GET['date'] ){
    $data = query("SELECT tank_id, sum(CONVERT(float, dailly_use)) AS daily_use, CONVERT(VARCHAR(10),datetime,111) AS date FROM  WWT_act_history WHERE CONVERT(date, datetime) ='". $_GET['date']."' GROUP BY tank_id, CONVERT(VARCHAR(10),datetime,111) ORDER BY CONVERT(VARCHAR(10),datetime,111) DESC");
  }
  else if( $_GET['month'] && $_GET['tank_id'] ){
    $data = query("SELECT  sum(CONVERT(float, dailly_use)) AS daily_use, CONVERT(VARCHAR(10),datetime,111) AS date FROM  WWT_act_history WHERE FORMAT(datetime, 'yyyy-MM')='". $_GET['month']."' AND tank_id='". $_GET['tank_id'] ."' GROUP BY CONVERT(VARCHAR(10),datetime,111) ");
  }
  else if( $_GET['month'] ){
    $data = query("SELECT tank_id, sum(CONVERT(float, dailly_use)) AS daily_use FROM  WWT_act_history WHERE FORMAT(datetime, 'yyyy-MM')='". $_GET['month']."' GROUP BY tank_id");
  }
  else{
    $data = query("SELECT tank_id, sum(CONVERT(float, dailly_use)) AS daily_use, CONVERT(VARCHAR(10),datetime,111) AS date FROM  WWT_act_history GROUP BY tank_id, CONVERT(VARCHAR(10),datetime,111) ORDER BY CONVERT(VARCHAR(10),datetime,111) DESC");
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
		  

	  	<br>
      <?php if( $_GET['tank_id'] && $_GET['date'] ): ?> 
        <h2 style="font-size: 18px !important; margin-top: 10px !important; margin-bottom: 10px !important"> 
          DETAIL TANK: <?= $_GET['tank_id']; ?> | <?= date('d-F-Y', strtotime($_GET['date'])); ?>
        </h2>
      <?php elseif( $_GET['tank_id'] && $_GET['month']): ?> 
        <h2 style="font-size: 18px !important; margin-top: 10px !important; margin-bottom: 10px !important"> 
          DETAIL TANK: <?= $_GET['tank_id']; ?> | <?= date('F Y', strtotime($_GET['month'])); ?>
        </h2>
      <?php endif; ?>
      <table id="searchTable">	
        <thead>
          <tr>
            <td rowspan="2" colspan="4" class="heading" style="text-align:center!important;border:thin gray solid;font-size: 26px;">
              <center><b>REPORT WATER HOUSE MONITORING <?php echo $this_title; ?></b></center></td>
          </tr>
          <tr>
          </tr>
          <tr>
            <td class="heading" style="border:thin gray solid;color: white;background-color: #0066CC;text-align:center; width: 60px "><b>NO</b></td>
            <td class="heading" style="border:thin gray solid;color: white;background-color: #0066CC;text-align:center"><b>DATE<?= ( $_GET['date'] && $_GET['tank_id'] ) ? " | TIME" :"" ?></b></td>
            <?php if( !$_GET['tank_id'] ): ?>
            <td class="heading" style="border:thin gray solid;color: white;background-color: #0066CC;text-align:center"><b>TANK NAME</b></td>
            <?php endif; ?>
            <td class="heading" style="border:thin gray solid;color: white;background-color: #0066CC;text-align:center"><b>TOTAL CONSUMPTION</b></td>
            <?php if( !$_GET['tank_id'] ): ?>
            
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php $i=1; $total_usage = array(); ?>

            <!-- DATE & TANK -->
            <?php if( $data && $_GET['date'] && $_GET['tank_id'] ): ?>
              <?php foreach($data as $row): ?>
                <tr>
                  <td style="border:thin gray solid;text-align:center"><?= $i++; ?></td>
                  <td style="border:thin gray solid;text-align:center"><?= date('d-M-Y | H:i', strtotime($row['datetime'])) ?></td>
                  <?php if( !$_GET['tank_id'] ): ?>
                  <td style="border:thin gray solid;text-align:center"><?= $row['tank_id']; ?></td>
                  <?php endif; ?>
                  <td style="border:thin gray solid;text-align:center"><?= number_format($row['dailly_use']); ?> M<sup>3</sup></td>
                </tr>
                <?php $total_usage[] = $row['dailly_use']; ?>
              <?php endforeach; ?>
              <tr>
                <td style="border:thin gray solid;text-align:center;" colspan="<?= (!$_GET['tank_id']) ? 3 : 2; ?>">TOTAL CONSUMPTION</td>
                <td colspan="1" style="border:thin gray solid;text-align:right;"><?= number_format(array_sum($total_usage)); ?> M<sub>3</sub></td>
              </tr>
            
            <!-- DATE -->
            <?php elseif( $data && $_GET['date'] ): ?>
              <?php foreach($data as $row): ?>
                <tr>
                  <td style="border:thin gray solid;text-align:center"><?= $i++; ?></td>
                  <td style="border:thin gray solid;text-align:center"><?= date('d-M-Y', strtotime($row['date'])) ?></td>
                  <?php if( !$_GET['tank_id'] ): ?>
                  <td style="border:thin gray solid;text-align:center"><?= $row['tank_id']; ?></td>
                  <?php endif; ?>
                  <td style="border:thin gray solid;text-align:right;"><?= number_format($row['daily_use']); ?> M<sup>3</sup></td>
                  
                </tr>
                <?php $total_usage[] = $row['daily_use']; ?>
              <?php endforeach; ?>
              <tr>
                <td style="border:thin gray solid;text-align:center;" colspan="<?= (!$_GET['tank_id']) ? 3 : 2; ?>">TOTAL CONSUMPTION</td>
                <td colspan="1" style="border:thin gray solid;text-align:right;"><?= number_format(array_sum($total_usage)); ?> M<sub>3</sub></td>
                
              </tr>
            
            <!-- Bulan & Tanki -->
            <?php elseif( $data && $_GET['month'] && $_GET['tank_id'] ): ?>
              <?php foreach($data as $row): ?>
                <tr>
                  <td style="border:thin gray solid;text-align:center"><?= $i++; ?></td>
                  <td style="border:thin gray solid;text-align:center"><?= date('d-M-Y', strtotime($row['date'])) ?></td>
                  <?php if( !$_GET['tank_id'] ): ?>
                  <td style="border:thin gray solid;text-align:center"><?= $row['tank_id']; ?></td>
                  <?php endif; ?>
                  <td style="border:thin gray solid;text-align:right;"><?= number_format($row['daily_use']); ?> M<sup>3</sup></td>
                </tr>
                <?php $total_usage[] = $row['daily_use']; ?>
              <?php endforeach; ?>
              <tr>
                <td style="border:thin gray solid;text-align:center;" colspan="<?= (!$_GET['tank_id']) ? 3 : 2; ?>">TOTAL CONSUMPTION</td>
                <td colspan="1" style="border:thin gray solid;text-align:right;"><?= number_format(array_sum($total_usage)); ?> M<sub>3</sub></td>

              </tr>
            
            <!-- BULAN -->
            <?php elseif( $data && $_GET['month'] ): ?>
              <?php foreach($data as $row): ?>
                <tr>
                  <td style="border:thin gray solid;text-align:center"><?= $i++; ?></td>
                  <td style="border:thin gray solid;text-align:center"><?= date('M-Y', strtotime($_GET['month'])) ?></td>
                  <?php if( !$_GET['tank_id'] ): ?>
                  <td style="border:thin gray solid;text-align:center"><?= $row['tank_id']; ?></td>
                  <?php endif; ?>
                  <td style="border:thin gray solid;text-align:right;"><?= number_format($row['daily_use']); ?> M<sup>3</sup></td>
                  
                </tr>
                <?php $total_usage[] = $row['daily_use']; ?>
              <?php endforeach; ?>
              <tr>
                <td style="border:thin gray solid;text-align:center;" colspan="<?= (!$_GET['tank_id']) ? 3 : 2; ?>">TOTAL MONTH CONSUMPTION</td>
                <td colspan="1" style="border:thin gray solid;text-align:right;"><?= number_format(array_sum($total_usage)); ?> M<sub>3</sub></td>
              
              </tr>
            <?php elseif( $_GET['month'] OR $_GET['date'] AND !$data ): ?>
              <tr>
                <td style="border:thin gray solid;text-align:center" colspan="5">Belum ada data pada waktu tersebut </td>
              </tr>
            <?php else: ?>
            <tr>
              <td style="border:thin gray solid;text-align:center" colspan="5">Silahkan gunakan filter untuk melihat data</td>
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
      var month = $("#month_url").val();

      var fileRpt = "down_wwt.php?date=" + date+"&month=" + month;
      // alert(fileRpt);
      window.location.href = fileRpt;
    });
  </script>
</body>
</html>
