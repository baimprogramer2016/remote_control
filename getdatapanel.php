<?php
session_start();
if($_SESSION['npk'] == "")
{
  header("location:index.php");
}
include('configs/db_scadap2.php');

$querypanel = "SELECT a.panel_id,b.description ,sum(convert(float,a.kwh)) as kwh from 
PHouse_act_history a , PHouse_act_status b with(nolock) WHERE 
a.panel_id = b.panel_id
and month(datetime) = month(getdate()) and year(datetime) = year(getdate())
group by a.panel_id,b.description 
";

if($_GET['request_data'] == 'panel_id')
{ 
    $color = ["4285F4", "DB4437", "F4B400", "0F9D58", "36C5F0","2EB67D", "E01E5A", "ECB22E", "F25022", "7FBA00","00A4EF", "00A4EF","4285F4", "DB4437", "F4B400", "0F9D58", "36C5F0","2EB67D", "E01E5A", "ECB22E", "F25022", "7FBA00","00A4EF", "00A4EF","4285F4", "DB4437", "F4B400", "0F9D58", "36C5F0","2EB67D", "E01E5A", "ECB22E", "F25022", "7FBA00","00A4EF", "00A4EF"];
    //panel id
    $panel_id = [];
    $panelid_query = mssql_query($querypanel);
    $rpanelrows = mssql_num_rows($panelid_query);
    if($rpanelrows != 0)
    {   
        $nocolor = 0;
        while($rpanel = mssql_fetch_assoc($panelid_query))
        {
           array_push($panel_id,array("panel_id" => $rpanel['panel_id'], "kwh" => $rpanel['kwh'], "color" =>$color[$nocolor]));

           $nocolor++;
        }
        // print_r($panel_id);
        echo json_encode($panel_id);
    }
    else
    {
        
        echo [];
    }

}

if($_GET['request_data'] == 'data_panel_id')
{
    $panel_id               = $_GET['panel_id'];
    $panel_color            = $_GET['color'];
    // echo $panel_color;
    $arraydatakwh           = [];
    $arraydatakwhresult     = [];
    $arraycolor             = [];

    for($i = 0; $i<=31; $i++) //sejumlah hari
    {
        array_push($arraydatakwh, array("hari" => $i, "kwh" => 0));   //jadikan nol semua
        array_push($arraycolor, "#".$panel_color);
    }
    
    $querykwhday = mssql_query("SELECT day(datetime) as hari,sum(convert(float,kwh)) as kwh from PHouse_act_history with(nolock) WHERE 
                                panel_id ='$panel_id'
                                and month(datetime) = month(getdate()) and year(datetime) = year(getdate())
                                group by day(datetime)
                                order by day(datetime) asc");

    //mndapatkan lengkap dari tanggal 1 sd akhir, dan update jika ada tanggal yang tidak 0 kwh nya
    while($rday = mssql_fetch_assoc($querykwhday))
    {
        $arraydatakwh[$rday['hari']-1]['kwh'] = $rday['kwh']; //update jika ada kwh nya
    }           
    
    foreach($arraydatakwh as $res)
    {
        array_push($arraydatakwhresult, $res['kwh']);
    }

     $dataresponse = array("datajson"=> $arraydatakwhresult,
                            "color" => $arraycolor,
                        );
    echo json_encode($dataresponse);
}


?>