<?php
session_start();

$statusaction   = $_GET['statusaction'];
$address        = $_GET['address'];
$id             = $_GET['id'];
$ip_address     = $_GET['ip_address'];

if($statusaction == 1)
{
    $sts = "ON";
    $ds = "Menghidupkan";
}else{
    $sts = "OFF";
    $ds = "Mematikan";
}

//mendapatkan descripsi ruangan
include('configs/db.php');
$query_desc    =    mssql_query("SELECT Description FROM Lighting_akebono WHERE Address = '".$address."'  AND ip_address = '".$ip_address."' ");
$rdesc         =    mssql_fetch_assoc($query_desc);

if($address == ""){
    echo "failed";
}else{
    //insert logs
    mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
    VALUES('".$_SESSION['npk']."','".$_SESSION['full_name']."','MANUAL LIGHTING','".strtoupper($sts)."','',
    '$ds  Lampu pada pukul '+convert(char(16), getdate(), 121)+' untuk ruangan ".$rdesc['Description']."',
    getdate())
    ");


    $json_data      = file_get_contents('this link url');
    $data           = json_decode($json_data);
    echo $json_data;
}
?>
