<?php
session_start();
if($_SESSION['npk'] == "")
{
  header("location:index.php");
}
//mendapatkan status ac seluruhnya

function apiTimeOut()
{
    $ctx = stream_context_create(array('http'=>
    array(
        'timeout' => 3,  //1200 Seconds is 20 Minutes
    )
    ));
    return $ctx;
}
function statusAcFromApi()
{
    $ctx          = apiTimeOut();
    $json_ac      = file_get_contents('this url');
    $dataac       = json_decode($json_ac);

    if(count($dataac) == 0)
    {
        return [];
    }else{
       return $dataac;
    }
}

//mengecek status ac berdasarkan address dan port
function cekStatusAc($datastatus, $param_address, $param_port)
{
    foreach($datastatus as $dstatus)
    {
        if($dstatus->address == $param_address && $dstatus->port == $param_port)
        {
            return array("status" =>$dstatus->status, "temp" => $dstatus->temp);
            break;
        }
    }
}

//=================================================================
//mendapatkan status ac seluruhnya
function statusLightingFromApi()
{
    $ctx            = apiTimeOut();
    $json_lamp      = file_get_contents('this url');
    $datalamp       = json_decode($json_lamp);
        if(count($datalamp) == 0)
        {
            return [];
        }else{
           return $datalamp;
        }
}


//mendapatkan status ac seluruhnya
function statusLightingFromApiAssy()
{
    $ctx            = apiTimeOut();
    $json_lamp      = file_get_contents('this url');
    $datalamp       = json_decode($json_lamp);

    if(count($datalamp) == 0)
    {
        return [];
    }else{
       return $datalamp;
    }
}
function statusLightingFromApiNew($paramipaddress)
{
    $ctx            = apiTimeOut();
    $json_lamp      = file_get_contents('this url');
    $datalamp       = json_decode($json_lamp);
        if(count($datalamp) == 0)
        {
            return [];
        }else{
           return $datalamp;
        }
}


//mengecek status ac berdasarkan address dan port
function cekStatusLighting($datastatus, $param_address)
{
    foreach($datastatus as $dstatus)
    {
        if($dstatus->address == $param_address)
        {
            return array("status" =>$dstatus->status);
            break;
        }
    }
}

//=================================================================
//mendapatkan seluruh data scheduler ac
function getAllDataAcScheduler()
{
    include('configs/db.php');

    $dataac = array();
    $queryacschedule = mssql_query("SELECT * FROM AC_akebono_scheduler ORDER BY Remote_address DESC");
    while($racschedule = mssql_fetch_assoc($queryacschedule))
    {
        $id                 = $racschedule['id'];
        $address            = $racschedule['Remote_address'];
        $port               = $racschedule['port'];
        $timeschedule       = $racschedule['time_schedule'];
        $statusschedule     = $racschedule['status_schedule'];

       array_push($dataac,array( 
            "id"=> $id, 
            "address" => $address, 
            "port" => $port, 
            "timeschedule" => $timeschedule,
            "statusschedule" => $statusschedule
        ));
    }
    return $dataac;
}

//mendapatkan seluruh data scheduler ac
function getAllDataAc()
{
    include('configs/db.php');
    $datascheduler      = getAllDataAcScheduler(); //mendapatkan seluruh data scheduler
    $arrayac            = array();
    $queryacschedule    = mssql_query("SELECT * FROM AC_akebono ORDER BY id ASC");
    $i = 0;
    while($racschedule  = mssql_fetch_assoc($queryacschedule))
    {
        //data address
        $arrayscheduler[$i] = array();
        $address        = $racschedule['Remote_address'];
        $port           = $racschedule['port'];
        $description    = $racschedule['Description'];
        $id             = $racschedule['id'];

        //menampung data scheduler yang ada pada tiap2 address
        foreach($datascheduler as $value)
        {
           if($value['address'] == $address && $value['port'] == $port)
           {
            
            array_push($arrayscheduler[$i], array(
                        "id"             => $value['id'],
                        // "address"        => $value['address'],
                        // "port"           => $value['port'],
                        "timeschedule"   => $value['timeschedule'],
                        "statusschedule" => $value['statusschedule'],
                    ));
           }
        }
        //menghitung jumlah scheduler
        $jumlahscheduler = count($arrayscheduler[$i]);
        if($jumlahscheduler > 0 )
        {
            $onscheduler = "1";
            $arraysch    =  $arrayscheduler[$i];
        }else{
            $onscheduler = "0";
            $arraysch    =  [];
        }
        
        //array utama
        array_push($arrayac,array(
            "id"            => $id, 
            "address"       => $address, 
            "port"          => $port, 
            "description"   => $description,
            "dataac"        => $arraysch,
            "onscheduler"   => $onscheduler 
        ));

     
        $i++;
    }
    return $arrayac;
}

//=================================================================
//mendapatkan seluruh data scheduler lampu
function getAllDataLightingScheduler()
{
    include('configs/db.php');

    $dataac = array();
    $queryacschedule = mssql_query("SELECT * FROM Lamp_akebono_scheduler ORDER BY Remote_address DESC");
    while($racschedule = mssql_fetch_assoc($queryacschedule))
    {
        $id                 = $racschedule['id'];
        $address            = $racschedule['Remote_address'];
        $timeschedule       = $racschedule['time_schedule'];
        $statusschedule     = $racschedule['status_schedule'];
        

       array_push($dataac,array( 
            "id"=> $id, 
            "address" => $address, 
            "timeschedule" => $timeschedule,
            "statusschedule" => $statusschedule,
        ));
    }
    return $dataac;
}


//mendapatkan seluruh data scheduler lighitng
function getAllDataLighting()
{
    include('configs/db.php');
    $datascheduler      = getAllDataLightingScheduler(); //mendapatkan seluruh data scheduler
    $arrayac            = array();
    $queryacschedule    = mssql_query("SELECT * FROM Lighting_akebono ORDER BY id ASC");
    $i = 0;
    while($racschedule  = mssql_fetch_assoc($queryacschedule))
    {
        //data address
        $arrayscheduler[$i] = array();
        $address        = $racschedule['Address'];
        $area           = $racschedule['Area'];
        $floor          = $racschedule['floor'];
        $description    = $racschedule['Description'];
        $id             = $racschedule['id'];
        $ip_address     = $racschedule['ip_address'];

        //menampung data scheduler yang ada pada tiap2 address
        foreach($datascheduler as $value)
        {
           if($value['address'] == $address && $value['port'] == $port)
           {
            
            array_push($arrayscheduler[$i], array(
                        "id"             => $value['id'],
                        // "address"        => $value['address'],
                        // "port"           => $value['port'],
                        "timeschedule"   => $value['timeschedule'],
                        "statusschedule" => $value['statusschedule'],
                    ));
           }
        }
        //menghitung jumlah scheduler
        $jumlahscheduler = count($arrayscheduler[$i]);
        if($jumlahscheduler > 0 )
        {
            $onscheduler = "1";
            $arraysch    =  $arrayscheduler[$i];
        }else{
            $onscheduler = "0";
            $arraysch    =  [];
        }
        
        //array utama
        array_push($arrayac,array(
            "id"            => $id, 
            "address"       => $address, 
            "area"          => $area, 
            "port"          => $port, 
            "description"   => $description,
            "datalighting"  => $arraysch,
            "onscheduler"   => $onscheduler ,
            "ip_address"            => $ip_address 
        ));

     
        $i++;
    }
    return $arrayac;
}


//mendapatkan  data scheduler ac
function getDataAc($paramid)
{
    include('configs/db.php');
    $arrayac            = array();
    $queryacschedule    = mssql_query("SELECT * FROM AC_akebono WHERE id = '".$paramid."' ORDER BY id ASC");
    $i = 0;
    $racschedule  = mssql_fetch_assoc($queryacschedule);
        //data address
        $arrayscheduler = array();
        $address        = $racschedule['Remote_address'];
        $port           = $racschedule['port'];
        $description    = $racschedule['Description'];
        $id             = $racschedule['id'];

        $queryacscheduler = mssql_query("SELECT * FROM AC_akebono_scheduler WHERE Remote_address = '".$address."' AND port = '".$port."'");
        $ceknum = mssql_num_rows($queryacscheduler);
        if($ceknum !=0)
        {
            while($rsc = mssql_fetch_assoc($queryacscheduler)){
               if($rsc['Remote_address'] == $address && $rsc['port'] == $port)
               { 
                array_push($arrayscheduler, array(
                            "id"             => $rsc['id'],
                            "timeschedule"   => $rsc['time_schedule'],
                            "statusschedule" => $rsc['status_schedule'],
                        ));
               }
            }
        }else{
            $arrayscheduler = [];
        }
        //menghitung jumlah scheduler
        $jumlahscheduler = count($arrayscheduler);
        if($jumlahscheduler > 0 )
        {
            $onscheduler = "1";
            $arraysch    =  $arrayscheduler;
        }else{
            $onscheduler = "0";
            $arraysch    =  [];
        }
        
        //array utama
        array_push($arrayac,array(
            "id"            => $id, 
            "address"       => $address, 
            "port"          => $port, 
            "description"   => $description,
            "dataac"        => $arraysch,
            "onscheduler"   => $onscheduler 
        ));
        return $arrayac;
}
//=====================================================================

//mendapatkan  data scheduler lamp
function getDataLighting($paramid)
{
    include('configs/db.php');
    $arrayac            = array();
    $queryacschedule    = mssql_query("SELECT * FROM Lighting_akebono WHERE id = '".$paramid."' ORDER BY id ASC");
    $i = 0;
    $racschedule  = mssql_fetch_assoc($queryacschedule);
        //data address
        $arrayscheduler = array();
        $address        = $racschedule['Address'];
        $area           = $racschedule['Area'];
        $description    = $racschedule['Description'];
        $ip_address     = $racschedule['ip_address'];
        $id             = $racschedule['id'];

        $queryacscheduler = mssql_query("SELECT * FROM Lamp_akebono_scheduler WHERE Remote_address = '".$address."' AND ip_address = '".$ip_address."'");
        $ceknum = mssql_num_rows($queryacscheduler);
        if($ceknum !=0)
        {
            while($rsc = mssql_fetch_assoc($queryacscheduler)){
               if($rsc['Remote_address'] == $address && $rsc['ip_address'] == $ip_address)
               { 
                array_push($arrayscheduler, array(
                            "id"             => $rsc['id'],
                            "timeschedule"   => $rsc['time_schedule'],
                            "statusschedule" => $rsc['status_schedule'],
                        ));
               }
            }
        }else{
            $arrayscheduler = [];
        }
        //menghitung jumlah scheduler
        $jumlahscheduler = count($arrayscheduler);
        if($jumlahscheduler > 0 )
        {
            $onscheduler = "1";
            $arraysch    =  $arrayscheduler;
        }else{
            $onscheduler = "0";
            $arraysch    =  [];
        }
        
        //array utama
        array_push($arrayac,array(
            "id"            => $id, 
            "address"       => $address, 
            "area"          => $area, 
            "ip_address"    => $ip_address, 
            "description"   => $description,
            "datalighting"  => $arraysch,
            "onscheduler"   => $onscheduler 
        ));
        return $arrayac;
}
    
// print_r(getDataAc(1));
// $data = getDataLighting(173);
// echo json_encode($data);
//=================================================================
//mendapatkan status ac seluruhnya
function statusCompressorFromApi()
{
    $ctx                  = apiTimeOut();
    $json_compressor      = file_get_contents('this url');
    $datacompressor       = json_decode($json_compressor);

    if(count($datacompressor) == 0)
    {
        return [];
    }else{
       return $datacompressor;
    }
   
}

//mengecek status compressor berdasarkan address dan port
function cekStatusCompressor($datastatus, $param_address)
{
    foreach($datastatus as $dstatus)
    {
        if($dstatus->address == $param_address)
        {
            return array("status" =>$dstatus->status);
            break;
        }
    }
}
function statusDayActive()
{
    include('configs/db.php');
    $query_day = mssql_query("SELECT * FROM lighting_day");
    $array_day = array();

    while($rday = mssql_fetch_assoc($query_day))
    {
        array_push($array_day, array("hari" => $rday['hari'], "status" => $rday['status']));
    }

    return $array_day;
}

//mendapatkan seluruh data scheduler compressor
function getAllDataCompressorScheduler()
{
    include('configs/db.php');

    $dataac = array();
    $queryacschedule = mssql_query("SELECT * FROM Compressor_akebono_scheduler ORDER BY Remote_address DESC");
    while($racschedule = mssql_fetch_assoc($queryacschedule))
    {
        $id                 = $racschedule['id'];
        $address            = $racschedule['Remote_address'];
        $timeschedule       = $racschedule['time_schedule'];
        $statusschedule     = $racschedule['status_schedule'];
        

       array_push($dataac,array( 
            "id"=> $id, 
            "address" => $address, 
            "timeschedule" => $timeschedule,
            "statusschedule" => $statusschedule,
        ));
    }
    return $dataac;
}


//get data compressor
function getAllDataCompressor()
{
    include('configs/db.php');
    $datascheduler      = getAllDataCompressorScheduler(); //mendapatkan seluruh data scheduler
    $arrayac            = array();
    $queryacschedule    = mssql_query("SELECT * FROM Compressor_akebono ORDER BY id ASC");
    $i = 0;
    while($racschedule  = mssql_fetch_assoc($queryacschedule))
    {
        //data address
        $arrayscheduler[$i] = array();
        $address        = $racschedule['Address'];
        $area           = $racschedule['Area'];
        $floor          = $racschedule['floor'];
        $description    = $racschedule['Description'];
        $id             = $racschedule['id'];
        $ip_address     = $racschedule['ip_address'];

        //menampung data scheduler yang ada pada tiap2 address
        foreach($datascheduler as $value)
        {
           if($value['address'] == $address && $value['port'] == $port)
           {
            
            array_push($arrayscheduler[$i], array(
                        "id"             => $value['id'],
                        // "address"        => $value['address'],
                        // "port"           => $value['port'],
                        "timeschedule"   => $value['timeschedule'],
                        "statusschedule" => $value['statusschedule'],
                    ));
           }
        }
        //menghitung jumlah scheduler
        $jumlahscheduler = count($arrayscheduler[$i]);
        if($jumlahscheduler > 0 )
        {
            $onscheduler = "1";
            $arraysch    =  $arrayscheduler[$i];
        }else{
            $onscheduler = "0";
            $arraysch    =  [];
        }
        
        //array utama
        array_push($arrayac,array(
            "id"            => $id, 
            "address"       => $address, 
            "area"          => $area, 
            "port"          => $port, 
            "description"   => $description,
            "datacompressor"  => $arraysch,
            "onscheduler"   => $onscheduler ,
            "ip_address"            => $ip_address 
        ));

     
        $i++;
    }
    return $arrayac;
}


//mendapatkan  data scheduler comrepssor
function getDataCompressor($paramid)
{
    include('configs/db.php');
    $arrayac            = array();
    $queryacschedule    = mssql_query("SELECT * FROM Compressor_akebono WHERE id = '".$paramid."' ORDER BY id ASC");
    $i = 0;
    $racschedule  = mssql_fetch_assoc($queryacschedule);
        //data address
        $arrayscheduler = array();
        $address        = $racschedule['Address'];
        $area           = $racschedule['Area'];
        $description    = $racschedule['Description'];
        $ip_address     = $racschedule['ip_address'];
        $id             = $racschedule['id'];

        $queryacscheduler = mssql_query("SELECT * FROM Compressor_akebono_scheduler WHERE Remote_address = '".$address."' AND ip_address = '".$ip_address."'");
        $ceknum = mssql_num_rows($queryacscheduler);
        if($ceknum !=0)
        {
            while($rsc = mssql_fetch_assoc($queryacscheduler)){
               if($rsc['Remote_address'] == $address && $rsc['ip_address'] == $ip_address)
               { 
                array_push($arrayscheduler, array(
                            "id"             => $rsc['id'],
                            "timeschedule"   => $rsc['time_schedule'],
                            "statusschedule" => $rsc['status_schedule'],
                        ));
               }
            }
        }else{
            $arrayscheduler = [];
        }
        //menghitung jumlah scheduler
        $jumlahscheduler = count($arrayscheduler);
        if($jumlahscheduler > 0 )
        {
            $onscheduler = "1";
            $arraysch    =  $arrayscheduler;
        }else{
            $onscheduler = "0";
            $arraysch    =  [];
        }
        
        //array utama
        array_push($arrayac,array(
            "id"            => $id, 
            "address"       => $address, 
            "area"          => $area, 
            "ip_address"    => $ip_address, 
            "description"   => $description,
            "datacompressor"  => $arraysch,
            "onscheduler"   => $onscheduler 
        ));
        return $arrayac;
}

?>