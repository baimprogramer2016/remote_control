<?php
session_start();
if($_SESSION['npk'] == "")
{
  header("location:index.php");
}
//get from api
function apiTimeOut()
{
    $ctx = stream_context_create(array('http'=>
    array(
        'timeout' => 3,  //1200 Seconds is 20 Minutes
    )
    ));
    return $ctx;
}

function checkApiPreasure()
{
  $ctx = apiTimeOut();
  $datacompresor = file_get_contents('this url');

  $datadecode =json_decode($datacompresor);
  if(count($datadecode) == 0)
  {
      return [];
  }else{
     return $datadecode;
  }
}
function getDataPreasure()
{
$ctx = apiTimeOut();
$datacompresor = file_get_contents('this url');

$datadecode =json_decode($datacompresor);
$array_comp = array();

array_push($array_comp, array("key" => 'pressure_mach_a', "title" => 'Pressure Mach A', "value" => $datadecode->pressure_mach_a));
array_push($array_comp, array("key" => 'pressure_mach_b', "title" => 'Pressure Mach B', "value" => $datadecode->pressure_mach_b));
array_push($array_comp, array("key" => 'pressure_mach_c', "title" => 'Pressure Mach C', "value" => $datadecode->pressure_mach_c));
array_push($array_comp, array("key" => 'pressure_mach_d', "title" => 'Pressure Mach D', "value" => $datadecode->pressure_mach_d));

return $array_comp;
}
function getDataCompressor()
{
$datacompresor = file_get_contents('this url');

$datadecode =json_decode($datacompresor);
$array_comp = array();

array_push($array_comp, array("key" => 'chart-compressor-realtime', "title" => 'Compressor Realtime', "value" => $datadecode->flow_comp_house));


return $array_comp;
}

//untuk request dari ajax
if($_GET['request_data'] == "compressore_code")
{
    echo json_encode(getDataPreasure());
}
//untuk request dari ajax
if($_GET['request_data'] == "compressore_realtime")
{
    echo json_encode(getDataCompressor());
}

?>