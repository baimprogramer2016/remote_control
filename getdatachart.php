<?php
session_start();
if($_SESSION['npk'] == "")
{
  header("location:index.php");
}
include('configs/db_scadap2.php');

$color = [ "#3330E4","#7DCE13","#003865","#FAEA48","#EF5B0C","#D61C4E","#CCD6A6"];
$color1 = ["#EF5B0C","#D61C4E","#CCD6A6", "#3330E4","#7DCE13","#003865","#FAEA48"];

if($_GET['request_data'] == 'datapower')
{ 
    //pemakaian tertinggi power
    $datalabel = [];
    $datacolor = [];
    $datakwh = [];
    $i = 0;
    $highpowerquery = mssql_query("SELECT TOP 7 panel_id,sum(convert(float,kwh)) as kwh FROM PHouse_act_history with(nolock) WHERE convert(date,datetime) = convert(date,getdate()) GROUP BY panel_id ORDER BY kwh DESC");
    while($rhighpower = mssql_fetch_assoc($highpowerquery)){
      $name_power   = $rhighpower['panel_id'];
      $data_kwh     = $rhighpower['kwh'];
      array_push($datalabel,  $name_power);
      array_push($datakwh,  $data_kwh);
      array_push($datacolor,  $color[$i]);
      $totalkwh += $data_kwh;
      $i++;
    }
    
     $dataresponse = array("label"=> $datalabel,
                            "totalkwh" =>number_format($totalkwh),
                            "color" => $datacolor,
                            "datakwh" => $datakwh,
                        );
    
     echo json_encode($dataresponse);
}

if($_GET['request_data'] == 'datawater')
{
    //pemakaian terbanyak water
  $datalabel = [];
  $datacolor = [];
  $datawater = [];
  $i = 0;
  $highwaterquery = mssql_query("SELECT TOP 7 tank_id,sum(convert(float,dailly_use)) as dailly_use  FROM WWT_act_history with(nolock) WHERE convert(date,datetime) = convert(date,getdate()) GROUP BY tank_id ORDER BY dailly_use DESC");
  while($rhighwater = mssql_fetch_assoc($highwaterquery)){
      $name_power = $rhighwater['tank_id'];
      if($rhighwater['dailly_use'] == 0)
      {
        $datawater = 0;
      }else{
        $data_water = $rhighwater['dailly_use']/1000  ;  
      }
      
      array_push($datalabel,  $name_power);
      array_push($datawater,  $data_water);
      array_push($datacolor,  $color[$i]);
      $totaldailyuse += $data_water;
      $i++;
  }
    $dataresponse = array("label"=> $datalabel,
                            "totaldailyuse" =>number_format($totaldailyuse),
                            "color" => $datacolor,
                            "datawater" => $datawater,
                        );
    echo json_encode($dataresponse);

}

if($_GET['request_data'] == 'datacompressor')
{
    $urlcompressor = 'this url';

    $handle = curl_init($urlcompressor);
    curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

    /* Get the HTML or whatever is linked in $url. */
    $response = curl_exec($handle);

    /* Check for 404 (file not found). */
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    if($httpCode == 404) {
        //Jika Kosong
    
        
        $dataresponse = array("label"=> ["Compressor","Compressor"],
                    "totalcompressor" => ["Not Connect"],
                    "color" => ["#cedadc","#cedadc"],
                    "datacompressor" => ["0","0"],
        );
        echo json_encode($dataresponse);

    }else{
      $json_compresor       = file_get_contents($urlcompressor);
      $dataCompressorvalue  = json_decode($json_compresor);
      $tempsisa             = 12000 - $dataCompressorvalue->flow_comp_house;
      $dataresponse         = array("label"=> ["Compressor","Remaining"],
                          "totalcompressor" => number_format($dataCompressorvalue->flow_comp_house),
                          "color" => ["#FF0000","#373738"],
                          "datacompressor" => [$dataCompressorvalue->flow_comp_house,$tempsisa],
        );
        echo json_encode($dataresponse);
    }

    curl_close($handle);

    /* Handle $response here. */
}

if($_GET['request_data'] == 'datamachines')
{
    //pemakaian terbanyak Machine
    $datalabel = [];
    $datacolor = [];
    $datamachines = [];
    
    array_push($datalabel,"P3");
    array_push($datalabel,"P4");
    array_push($datalabel,"P1");
    array_push($datacolor,$color[1]);
    array_push($datacolor,$color[0]);
    array_push($datacolor,$color[5]);
    array_push($datamachines,6);
    array_push($datamachines,1);
    array_push($datamachines,1);
  
    $dataresponse = array("label"=> $datalabel,
                            "totalmachines" =>number_format(8),
                            "color" => $datacolor,
                            "datamachines" => $datamachines,
                        );
    echo json_encode($dataresponse);

}

//by table
// if($_GET['request_data'] == 'datacompressor')
// {
//     //pemakaian terbanyak water
//   $datalabel = [];
//   $datacolor = [];
//   $datawater = [];
//   $i = 0;
//   $highwaterquery = mssql_query("SELECT comp_id,sum(convert(float,hourly_cons)) as hourly_cons  FROM COMP_act_history with(nolock) WHERE convert(date,datetime) = convert(date,getdate()) GROUP BY comp_id ORDER BY hourly_cons DESC");
//   while($rhighwater = mssql_fetch_assoc($highwaterquery)){
//       $name_power = $rhighwater['comp_id'];
//       $data_water = $rhighwater['hourly_cons'];
//       array_push($datalabel,  $name_power);
//       array_push($datawater,  $data_water);
//       array_push($datacolor,  $color[$i]);
//       $totaldailyuse += $data_water;
//       $i++;
//   }
//     $dataresponse = array("label"=> $datalabel,
//                             "totalhourlycons" =>number_format($totaldailyuse / 1000),
//                             "color" => $datacolor,
//                             "datacompressor" => $datawater,
//                         );
//     echo json_encode($dataresponse);

// }
    

?>