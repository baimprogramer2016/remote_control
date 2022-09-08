<?php
include('configs/db_scadap2.php');
$sethourPower = $_GET['sethourPower'];


//dapatkan 6 jam 
$query_jam = mssql_query("SELECT jam FROM (
select TOP $sethourPower DATEPART(HOUR, [datetime]) as jam from PHouse_act_history WHERE convert(date,[datetime]) = convert(date,getdate())
group by DATEPART(HOUR, [datetime]) order by jam desc
) jam order by jam asc");

$i_jam = 0;
$array_map_jam = array();
while($rjam = mssql_fetch_assoc($query_jam)){
    $array_jam_label[] =  $rjam['jam'].':00';
    $array_jam[] =  $rjam['jam'];
    array_push($array_map_jam, array("jam" => $rjam['jam'], "idx" => $i_jam));
    $i_jam++;
}

//dapatkan nama power house
$query_name_power = mssql_query("SELECT panel_id , kwh FROM (
    select TOP 7 panel_id ,sum(convert(float,kwh)) as kwh from PHouse_act_history WHERE convert(date,[datetime]) = convert(date,getdate())
    group by panel_id order by kwh desc 
    )  a order by kwh asc
    ");
while($rname = mssql_fetch_assoc($query_name_power))
{
    $array_name[] = $rname['panel_id'];
}

//buat data per 6 jam per panel id dan jadikan 0 semua
$color = ["0000FF", "FF00BF", "FFFF00", "00FF00", "FFFFFF","f80000", "00FFFF", "FFFC00", "F25022", "7FBA00","00A4EF", "00A4EF","4285F4", "DB4437", "F4B400", "0F9D58", "36C5F0","2EB67D", "E01E5A", "ECB22E", "F25022", "7FBA00","00A4EF", "00A4EF","4285F4", "DB4437", "F4B400", "0F9D58", "36C5F0","2EB67D", "E01E5A", "ECB22E", "F25022", "7FBA00","00A4EF", "00A4EF"];
$array_label = array();

foreach($array_name as $kcolor => $item_name)
{
   array_push($array_label,array( "label" => $item_name, "data" => [0,0,0,0,0,0], "borderWidth" => 2,"fill" => false, "borderColor" => ['#'.$color[$kcolor]]));
}

//dapatkan data all
$array_kwh_all = array();
$query_kwh_all = mssql_query("SELECT panel_id,DATEPART(HOUR, [datetime]) as jam, sum(convert(float,kwh)) as kwh from PHouse_act_history WHERE convert(date,[datetime]) = convert(date,getdate())
group by panel_id,DATEPART(HOUR, [datetime]) order by jam desc");
while($rkwh_all = mssql_fetch_assoc($query_kwh_all))
{
    array_push($array_kwh_all,array( "panel_id" => $rkwh_all['panel_id'], "jam" => $rkwh_all['jam'], "kwh" => $rkwh_all['kwh']));
}

//function mencari nilai kwh
function cekKwh($data_all, $panel_id, $jam)
{
    foreach($data_all as $data)
    {
        if($data['panel_id'] == $panel_id && $data['jam'] == $jam)
        {
            return $data['kwh'];
            break;
        }
    }
}

//cek satu2 per panel id dan per jam
foreach($array_name as $key => $panel_id)
{

    foreach($array_map_jam as $jam)
    {
        $nilaikwh = cekKwh($array_kwh_all, $panel_id, $jam['jam']);    
        $array_label[$key]['data'][$jam['idx']] = $nilaikwh;
    }

}

$array_result= array(
    "status_code"   => 200,
    "hours_label"   => $array_jam_label,
    "hours"         => $array_jam,
    "hours_map"     => $array_map_jam,
    "panel_id"      => $array_name,
    "datasets"      => $array_label,
    "kwh"           => $array_kwh_all,
);

echo json_encode($array_result);



?>