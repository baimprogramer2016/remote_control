<?php 
include('configs/db.php');

//scheduler mati
//OFF
$minutes = 3;
$queryoff = mssql_query("SELECT id,Remote_address, port,datediff(minute, CONVERT(VARCHAR(5),getdate(),108) , time_schedule) as rangeminutes 
                        FROM AC_akebono_scheduler WHERE status_schedule = 'off'
                        AND datediff(minute, CONVERT(VARCHAR(5),getdate(),108) , time_schedule) between 0 AND 1
                        AND datename(dw,getdate()) IN(
                        SELECT hari FROM Lighting_day WHERE status = 1
                        )
                        ");
$cekoff = mssql_num_rows($queryoff);
if($cekoff !=0)
{
    //logs
     //insert logs
     mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
     VALUES('','Automatic Schedule','SCHEDULE AC','OFF','',
     'Mematikan AC sesuai dengan Pengaturan Schedule',
     getdate())
     ");
while($roff = mssql_fetch_assoc($queryoff)){
        // $json_data      = file_get_contents('http://192.168.0.185:1880/ac/cmd?address='.$address.'&temp='.$temperatur.'&power='.$statusaction);
        $json_data      = file_get_contents("http://192.168.0.185:1880/ac/cmd?address=".$roff['Remote_address']."&temp=25&power=0&port=".$roff['port']);
        $data           = json_decode($json_data);
        mssql_query("UPDATE AC_akebono_scheduler SET 
                        last_process = getdate()
                        WHERE id = '".$roff['id']."'
                    "); 
        // echo $data;
        // echo $roff['Remote_address'];
    sleep(1); //kasih napas 
}
}


//scheudler hidup
$queryon = mssql_query("SELECT id,Remote_address,port, datediff(minute, CONVERT(VARCHAR(5),getdate(),108) , time_schedule) as rangeminutes 
                        FROM AC_akebono_scheduler WHERE status_schedule = 'on'
                        AND datediff(minute, CONVERT(VARCHAR(5),getdate(),108) , time_schedule) between 0 AND 1
                        AND datename(dw,getdate()) IN(
                        SELECT hari FROM Lighting_day WHERE status = 1
                        )
                        ");
$cekon = mssql_num_rows($queryon);
if($cekon !=0)
{
    //logs
    mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
     VALUES('','Automatic Schedule','SCHEDULE AC','ON','',
     'Menghidupkan AC sesuai dengan Pengaturan Schedule',
     getdate())
     ");
while($roon = mssql_fetch_assoc($queryon)){
    // $json_data      = file_get_contents('http://192.168.0.185:1880/ac/cmd?address='.$address.'&temp='.$temperatur.'&power='.$statusaction);
    $json_data_on      = file_get_contents("http://192.168.0.185:1880/ac/cmd?address=".$roon['Remote_address']."&temp=25&power=1&port=".$roon['port']);
    $data_on           = json_decode($json_data_on);
    mssql_query("UPDATE AC_akebono_scheduler SET 
                    last_process = getdate()
                    WHERE id = '".$roon['id']."'
                "); 
    // echo $data;
    // echo $roff['Remote_address'];
sleep(1); //kasih napas 
}
}

?>