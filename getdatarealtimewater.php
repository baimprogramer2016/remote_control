<?php
include('configs/db_scadap2.php');
$sethourWater = $_GET['sethourWater'];

//dapatkan 6 jam 
$query_jam = mssql_query("SELECT jam FROM (
select TOP $sethourWater DATEPART(HOUR, [datetime]) as jam from WWT_act_history WHERE convert(date,[datetime]) = convert(date,getdate())
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
$query_name_power = mssql_query("SELECT tank_id , dailly_use FROM (
    select TOP 7 tank_id ,sum(convert(float,dailly_use)) as dailly_use from WWT_act_history WHERE convert(date,[datetime]) = convert(date,getdate())
    group by tank_id order by dailly_use desc 
    )  a order by dailly_use asc
    ");
while($rname = mssql_fetch_assoc($query_name_power))
{
    $array_name[] = $rname['tank_id'];
}

//buat data per 6 jam per panel id dan jadikan 0 semua
$color = ["0000FF", "FF00BF", "FFFF00", "00FF00", "FFFFFF","f80000", "00FFFF", "FFFC00", "F25022", "7FBA00","00A4EF", "00A4EF","4285F4", "DB4437", "F4B400", "0F9D58", "36C5F0","2EB67D", "E01E5A", "ECB22E", "F25022", "7FBA00","00A4EF", "00A4EF","4285F4", "DB4437", "F4B400", "0F9D58", "36C5F0","2EB67D", "E01E5A", "ECB22E", "F25022", "7FBA00","00A4EF", "00A4EF"];
$array_label = array();

foreach($array_name as $kcolor => $item_name)
{
   array_push($array_label,array( "label" => $item_name, "data" => [0,0,0,0,0,0], "borderWidth" => 2,"fill" => false, "borderColor" => ['#'.$color[$kcolor]]));
}

//dapatkan data all
$array_dailly_use_all = array();
$query_dailly_use_all = mssql_query("SELECT tank_id,DATEPART(HOUR, [datetime]) as jam, sum(convert(float,dailly_use)) as dailly_use from WWT_act_history WHERE convert(date,[datetime]) = convert(date,getdate())
group by tank_id,DATEPART(HOUR, [datetime]) order by jam desc");
while($rdailly_use_all = mssql_fetch_assoc($query_dailly_use_all))
{
    if($rdailly_use_all['dailly_use'] == 0)
    {
        $rdwall = 0;
    }else{
        $rdwall = $rdailly_use_all['dailly_use'] /1000;
    }
    array_push($array_dailly_use_all,array( "tank_id" => $rdailly_use_all['tank_id'], "jam" => $rdailly_use_all['jam'], "dailly_use" => $rdwall));
}

//function mencari nilai dailly_use
function cekdailly_use($data_all, $tank_id, $jam)
{
    foreach($data_all as $data)
    {
        if($data['tank_id'] == $tank_id && $data['jam'] == $jam)
        {
            return $data['dailly_use'];
            break;
        }
    }
}

//cek satu2 per panel id dan per jam
foreach($array_name as $key => $tank_id)
{

    foreach($array_map_jam as $jam)
    {
        $nilaidailly_use = cekdailly_use($array_dailly_use_all, $tank_id, $jam['jam']);    
        $array_label[$key]['data'][$jam['idx']] = $nilaidailly_use;
    }

}

$array_result= array(
    "status_code"   => 200,
    "hours_label"   => $array_jam_label,
    "hours"         => $array_jam,
    "hours_map"     => $array_map_jam,
    "tank_id"       => $array_name,
    "datasets"      => $array_label,
    "dailly_use"    => $array_dailly_use_all,
);

echo json_encode($array_result);



?>