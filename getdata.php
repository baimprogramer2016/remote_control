<?php
session_start();
if($_SESSION['npk'] == "")
{
  header("location:index.php");
}

//#########################################################################
//area - ata area master
function getDataAreaLighting()
{
    include('configs/db.php');
    $img = ["bod.jpg", "room1.jpg","room2.jpg","roomother.jpg","meetingroom.jpg","industry.jpg","other.jpg","bod.jpg", "room1.jpg","room2.jpg","roomother.jpg","meetingroom.jpg","industry.jpg","other.jpg","bod.jpg", "room1.jpg","room2.jpg","roomother.jpg","meetingroom.jpg","industry.jpg","other.jpg"];
    // include('configs/db.php');
    // $array_image = array();
    // array_push($array_image,array("area" => "Ruang Direksi", "image" => "bod.jpg"));
    // array_push($array_image,array("area" => "Ruang Kerja Lantai 1", "image" => "room1.jpg"));
    // array_push($array_image,array("area" => "Ruang Kerja Lantai 2", "image" => "room2.jpg")) ;
    // array_push($array_image,array("area" => "Ruang Lain2", "image" => "roomother.jpg"));
    // array_push($array_image,array("area" => "Ruang Meeting", "image" => "meetingroom.jpg"));
    // array_push($array_image,array("area" => "P1 Assy R4", "image" => "industry.jpg"));
    // array_push($array_image,array("area" => null, "image" => "other.jpg") ) ;
 
    if($_SESSION['role'] != 'ADMIN')
    {
        $querynext = "AND Area IN(
            SELECT area FROM Lighting_role_area WHERE role_code = '".$_SESSION['role']."'    
        )";
    }else{
        $querynext ="";
    }

    $array_area = array();
    $query_area = mssql_query("SELECT distinct Area as  area FROM Lighting_akebono WITH(Nolock) WHERE 1=1 ".$querynext." order by area desc");
    $no = 0;
    while($rarea = mssql_fetch_assoc($query_area)){
        array_push($array_area,array("area" => $rarea['area'], "image" => $img[$no]));
        $no++;
    }
    // return json_encode($array_area);
    return $array_area;
}   

// echo getDataAreaLighting();

//area - data floor master
function getDataFloorAc()
{
    // include('configs/db.php');
    $array_image = array();
    array_push($array_image,array("floor" => "1", "desc"=> "1st Floor","image" => "bod.jpg"));
    array_push($array_image,array("floor" => "2", "desc"=> "2dn Floor","image" => "room1.jpg"));
    array_push($array_image,array("floor" => "3", "desc"=> "3rd Floor","image" => "room2.jpg")) ;
   
    return $array_image;
}   
//area comrpessor
function getDataAreaCompressor()
{
    include('configs/db.php');
    $img = ["industry.jpg", "room1.jpg","room2.jpg","roomother.jpg","meetingroom.jpg","industry.jpg","other.jpg","bod.jpg", "room1.jpg","room2.jpg","roomother.jpg","meetingroom.jpg","industry.jpg","other.jpg","bod.jpg", "room1.jpg","room2.jpg","roomother.jpg","meetingroom.jpg","industry.jpg","other.jpg"];
   
    if($_SESSION['role'] != 'ADMIN')
    {
        $querynext = "AND Area IN(
            SELECT area FROM Lighting_role_area WHERE role_code = '".$_SESSION['role']."'    
        )";
    }else{
        $querynext ="";
    }

    $array_area = array();
    $query_area = mssql_query("SELECT distinct Area as  area FROM Compressor_akebono WITH(Nolock) WHERE 1=1 ".$querynext." order by area desc");
    $no = 0;
    while($rarea = mssql_fetch_assoc($query_area)){
        array_push($array_area,array("area" => $rarea['area'], "image" => $img[$no]));
        $no++;
    }
    // return json_encode($array_area);
    return $array_area;
}   


function dashInfo()
{
    //dahsboard - count power, water, compressor , machine
    include('configs/db_scadap2.php');
    //jumlah power
    $countpowerquery = mssql_query("SELECT count(id) countpower FROM PHouse_act_status with(nolock)");
    $rcountpower = mssql_fetch_assoc($countpowerquery);

    //jumlah water
    $countwaterquery = mssql_query("SELECT count(id) countwater FROM WWT_act_status  with(nolock)");
    $rcountwater = mssql_fetch_assoc($countwaterquery);

    //jumlah compressor
    $countcomquery = mssql_query("SELECT 10 countcompressor FROM COMP_act_status  with(nolock)");
    $rcountcomp = mssql_fetch_assoc($countcomquery);

    //jumlah kwh dari awal bulan sampai hari ini dibandingkan dengan hari yang sama di bulan lalu
    $querykwhpower1 = mssql_query("SELECT sum(convert(float,kwh)) as kwh from PHouse_act_history  where convert(date,datetime) 
                                    between DATEADD(month, DATEDIFF(month, 0, getdate()), 0) and convert(date,getdate())");
    $rkwh1          =   mssql_fetch_assoc($querykwhpower1);
    $querykwhpower2 = mssql_query("SELECT sum(convert(float,kwh)) as kwh from PHouse_act_history  where convert(date,datetime) 
                                    between DATEADD(month, DATEDIFF(month, 0, dateadd(month,-1,getdate())), 0) and convert(date,dateadd(month,-1,getdate()))");
    $rkwh2          =   mssql_fetch_assoc($querykwhpower2);
    
    $powerpercent = $rkwh1['kwh'] * 100 / $rkwh2['kwh'];

    //jumlah water dari awal bulan sampai hari ini dibandingkan dengan hari yang sama di bulan lalu
    $querywwtpower1 = mssql_query("SELECT sum(convert(float,dailly_use)) as dailly_use from WWT_act_history  where convert(date,datetime) 
                                    between DATEADD(month, DATEDIFF(month, 0, getdate()), 0) and convert(date,getdate())");
    $rwwt1          =   mssql_fetch_assoc($querywwtpower1);
    $querywwtpower2 = mssql_query("SELECT sum(convert(float,dailly_use)) as dailly_use from WWT_act_history  where convert(date,datetime) 
                                    between DATEADD(month, DATEDIFF(month, 0, dateadd(month,-1,getdate())), 0) and convert(date,dateadd(month,-1,getdate()))");
    $rwwt2          =   mssql_fetch_assoc($querywwtpower2);
    
    $wwtpercent = $rwwt1['dailly_use'] * 100 / $rwwt2['dailly_use'];

    //jumlah compressor dari awal bulan sampai hari ini dibandingkan dengan hari yang sama di bulan lalu
    $querycompower1 = mssql_query("SELECT sum(convert(float,hourly_cons)) as hourly_cons from COMP_act_history  where convert(date,datetime) 
                                    between DATEADD(month, DATEDIFF(month, 0, getdate()), 0) and convert(date,getdate())");
    $rcom1          =   mssql_fetch_assoc($querycompower1);
    $querycomppower2 = mssql_query("SELECT sum(convert(float,hourly_cons)) as hourly_cons from COMP_act_history  where convert(date,datetime) 
                                    between DATEADD(month, DATEDIFF(month, 0, dateadd(month,-1,getdate())), 0) and convert(date,dateadd(month,-1,getdate()))");
    $rcom2          =   mssql_fetch_assoc($querycomppower2);
    
    $compercent = $rcom1['hourly_cons'] * 100 / $rcom2['hourly_cons'];

    $array_info = array(
                    "power_count" => $rcountpower['countpower'],
                    "power_percent" => round($powerpercent),
                    "water_count" => $rcountwater['countwater'],
                    "water_percent" => round($wwtpercent),
                    "compressor_count" => $rcountcomp['countcompressor'],
                    "compressor_percent" => round($compercent),
                    "machine_count" => 8,
                    "machine_percent" => 0,
                );

    return $array_info;
}

function listChartBottom()
{
    $color = [ "#3330E4","#7DCE13","#003865","#FAEA48","#EF5B0C","#D61C4E","#CCD6A6"];

    include('configs/db_scadap2.php');
    //list power  bottom
    $array_power = array();
    $highpowerquery = mssql_query("SELECT TOP 7 b.description,sum(convert(float,a.kwh)) as kwh FROM PHouse_act_history a, PHouse_act_status b with(nolock) WHERE a.panel_id = b.panel_id and convert(date,datetime) = convert(date,getdate()) GROUP BY b.description ORDER BY kwh DESC");
    $ipower = 0;
    while($rhighpower = mssql_fetch_assoc($highpowerquery)){
      $name_power   = $rhighpower['description'];
      $data_kwh     = $rhighpower['kwh'];
      array_push($array_power, array("name_power" => $name_power, "data_kwh" => $data_kwh,"color" => $color[$ipower]));
      $ipower++;
    }

    //list water  bottom
    $array_water = array();
    $highwaterquery = mssql_query("SELECT TOP 7 b.description,sum(convert(float,a.dailly_use)) as dailly_use  FROM WWT_act_history a with(nolock) , WWT_act_status b WHERE a.tank_id = b.tank_id AND convert(date,a.datetime) = convert(date,getdate()) GROUP BY b.description ORDER BY dailly_use DESC");
    $iwater = 0;
    while($rhighwater = mssql_fetch_assoc($highwaterquery)){
        $name_water = $rhighwater['description'];
        $data_water = $rhighwater['dailly_use'];
        array_push($array_water, array("name_water" => $name_water, "data_water" => $data_water,"color" => $color[$iwater]));
        $iwater++;
    }

     //list compressor  bottom
    // $array_compressor = array();
    // $highcomprequery = mssql_query("SELECT comp_id,sum(convert(float,hourly_cons)) as hourly_cons  FROM COMP_act_history with(nolock) WHERE convert(date,datetime) = convert(date,getdate()) GROUP BY comp_id ORDER BY hourly_cons DESC");
    // $icompressor = 0;
    // while($rhighcompre = mssql_fetch_assoc($highcomprequery)){
    //     $name_compressor = $rhighcompre['comp_id'];
    //     $data_compressor = round($rhighcompre['hourly_cons']/1000) .' K';
    //     array_push($array_compressor, array("name_compressor" => $name_compressor, "data_compressor" => $data_compressor,"color" => $color[$icompressor]));
    //     $icompressor++;
    // }
    

    //list compresor bottom
    $array_compressor = array();
    $json_compresor       = file_get_contents('this url');
    $datacomp             = json_decode($json_compresor);
    // array_push($array_compressor, array("name_compressor" => "COMPSUMTION", "data_compressor" => $datacomp->hourly_comp_house, "color" => "#12151E"));
    array_push($array_compressor, array("name_compressor" => "HOURLY  ".number_format(round($datacomp->hourly_comp_house,0)), "data_compressor" => $datacomp->hourly_comp_house, "color" => "#12151E"));
    array_push($array_compressor, array("name_compressor" => "DAILY COMP ".number_format(round($datacomp->daily_comp_house,0)), "data_compressor" => $datacomp->daily_comp_house, "color" => "#12151E"));

    //list machine bottom
    $array_machine = array();                    
    array_push($array_machine, array("name_machine" => 'P3', "data_machine" => 6, "color"=>$color[1]));
    array_push($array_machine, array("name_machine" => 'P4', "data_machine" => 1, "color"=>$color[0]));
    array_push($array_machine, array("name_machine" => 'P1', "data_machine" => 1, "color"=>$color[5]));

    $result_array = array(
        "list_power"        => $array_power,
        "list_water"        => $array_water,
        "list_compressor"   => $array_compressor,
        "list_machine"      => $array_machine,
    );

    return $result_array;
}

function getNameEmployee($npk)
{
    include('configs/db_usr.php');
    $query_name = mssql_query("SELECT first_name FROM VIEW_EMPLOYEE_NG WHERE emp_no = '".$npk."'");
    $rname = mssql_fetch_assoc($query_name);
    return $rname['first_name'];
}

function getLogs()
{
    include('configs/db.php');
    $limitrow = 20;
    $query_count = mssql_query("SELECT count(id) as countlog FROM lighting_logs with(nolock) ");
    $rcountlog = mssql_fetch_assoc($query_count) ;
    $pagecount = ceil($rcountlog['countlog'] / $limitrow) + 1;

    $query_logs = mssql_query("SELECT TOP ".$limitrow." convert(date,lastupdate) as lp,* FROM lighting_logs with(nolock) ORDER BY id desc");
    $array_logs = array();
    while($rlogs = mssql_fetch_assoc($query_logs))
    {
        array_push($array_logs,array(
            "id" => $rlogs['id'], 
            "npk" => $rlogs['npk'], 
            "name" => $rlogs['name'], 
            "type" => $rlogs['type'], 
            "status" => $rlogs['status'], 
            "settime" => $rlogs['settime'],
            "description" => $rlogs['description'],
            "lastupdate" => $rlogs['lp'],
            ));
    }

    $result = array("datacount" => $rcountlog['countlog'], "pagecount" => $pagecount ,"datalog" => $array_logs);

    return $result;
}

function getLogsLoadMore($index)
{
    include('configs/db.php');

    $PageNumber = $index;
    $RowsOfPage = 20;

    $query_logs = mssql_query(
        "SELECT * FROM lighting_logs
        ORDER BY id desc
        OFFSET ($PageNumber-1)* $RowsOfPage ROWS
        FETCH NEXT $RowsOfPage ROWS ONLY"
    );
    $array_logs = array();
    while($rlogs = mssql_fetch_assoc($query_logs))
    {
        array_push($array_logs,array(
            "id" => $rlogs['id'], 
            "npk" => $rlogs['npk'], 
            "name" => $rlogs['name'], 
            "type" => $rlogs['type'], 
            "status" => $rlogs['status'], 
            "settime" => $rlogs['settime'],
            "description" => $rlogs['description'],
            "lastupdate" => $rlogs['lp'],
            ));
    }

    $result = array("count" => $rcountlog['countlog'], "datalog" => $array_logs);

    return $result;
}

//menampilkan data yang punya akses
function getDataUser()
{
    include('configs/db.php');
    $query_emp = mssql_query("SELECT * FROM Lighting_user");

    $array_emp = array();
    while($rusr = mssql_fetch_assoc($query_emp)){
        array_push($array_emp, array("id" => $rusr['id'], "npk" => $rusr['npk'] , "name" => $rusr['name'],"role_code" => $rusr['role_code']));
    }
    return $array_emp;
}

//menampilkan data user akebono
function getAllEmployee()
{
    include('configs/db_usr.php');
    $query_emp = mssql_query("SELECT emp_no, full_name FROM  VIEW_EMPLOYEE_NG ORDER BY full_name ASC");
    $array_emp = array();
    while($rusr = mssql_fetch_assoc($query_emp))
    {
        array_push($array_emp, array("npk" => $rusr['emp_no'] , "name" => $rusr['full_name']));
    }
    return $array_emp;
}

//get data akses
function getDataAccess()
{
    include('configs/db.php');
    $query_emp = mssql_query("SELECT * FROM Lighting_access ORDER BY id desc");

    $array_emp = array();
    while($rusr = mssql_fetch_assoc($query_emp)){
        array_push($array_emp, array("id" => $rusr['id'],"access_code" => $rusr['access_code'] , "access" => $rusr['access'],"description" => $rusr['description']));
    }
    return $array_emp;
}

function getAreaLightingFilter($rolecode)
{
    include('configs/db.php');
    $array_area = array();
    $query_area  = mssql_query("SELECT a.area, b.role_code ,case when b.area is null then '' else 'checked' end  as active from (
        SELECT distinct Area FROM Lighting_akebono WHERE Area is not null
        union all
        SELECT distinct Area FROM Compressor_akebono WHERE Area is not null
        ) as a left join Lighting_role_area b 
        ON a.area =b.area 
        and b.role_code  = '".$rolecode."'
               
    ");
    while($rarea = mssql_fetch_assoc($query_area)){

        if($rarea['area'] == 'ASSY R4')
        {
            $area = $rarea['area'].' (Compressor)';
        }
        elseif($rarea['area'] == 'P1 ASSY R4')
        {
            $area = $rarea['area'].' (Lampu)';
        }else{
            $area = $rarea['area'];
        }
        array_push($array_area, array("kodearea" => $rarea['area'],"area" => $area, "active" => $rarea['active']));
    }

    return $array_area;
}

//get data role
function getDataRole()
{
    include('configs/db.php');
    $query_emp = mssql_query("SELECT * FROM Lighting_role ORDER BY id desc");

    $array_emp = array();
    while($rusr = mssql_fetch_assoc($query_emp)){
        array_push($array_emp, array("id" => $rusr['id'],"role_code" => $rusr['role_code'] , "role" => $rusr['role']));
    }
    return $array_emp;
}

function getMappingAccess($param_rolecode)
{
    include('configs/db.php');
  
    //mendapatkan semua menu berdasarkan group
    $array_access = array();
    $query_menu = mssql_query("SELECT a.*,case when b.access_code is null then '' else 'checked' end as active  FROM 
                                Lighting_access a
                                left join
                                Lighting_mapping_access b 
                                ON a.access_code  = b.access_code 
                                AND b.role_code ='".$param_rolecode."'");
    while($rmenu = mssql_fetch_assoc($query_menu))
    {
        array_push($array_access, array("active" => $rmenu['active'] ,"access" => $rmenu['access'], "id" => $rmenu['id'], "access_code" => $rmenu['access_code'], "description" => $rmenu['description']));
    }


    return $array_access;
}

?>


