<?php 
include('configs/db.php');

//OFF
$minutes = 3;
$queryoff = mssql_query("SELECT ip_address,id,Remote_address, datediff(minute, CONVERT(VARCHAR(5),getdate(),108) , time_schedule) as rangeminutes 
                        FROM Compressor_akebono_scheduler WHERE status_schedule = 'off'
                        AND datediff(minute, CONVERT(VARCHAR(5),getdate(),108) , time_schedule) between 0 AND 1
                        AND datename(dw,getdate()) IN(
                        SELECT hari FROM Lighting_day WHERE status = 1
                        )
                        ");
$cekoff = mssql_num_rows($queryoff);
if($cekoff !=0)
{
    //logs
    mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
    VALUES('','Automatic Schedule','SCHEDULE COMPRESSOR','OFF','',
    'Mematikan Compressor sesuai dengan Pengaturan Schedule',
    getdate())
    ");
while($roff = mssql_fetch_assoc($queryoff)){
        $json_data      = file_get_contents("http://".$roff['ip_address'].":1880/comp/cmd?address=" . $roff['Remote_address'] . "&status=0");
        $data           = json_decode($json_data);
        mssql_query("UPDATE Compressor_akebono_scheduler SET 
                        last_process = getdate()
                        WHERE id = '".$roff['id']."'
                    "); 

        // echo $roff['Remote_address'];
    sleep(1); //kasih napas 
}
}

$queryoon = mssql_query("SELECT ip_address,id,Remote_address, datediff(minute, CONVERT(VARCHAR(5),getdate(),108) , time_schedule) as rangeminutes 
                        FROM Compressor_akebono_scheduler WHERE status_schedule = 'on'
                        AND datediff(minute, CONVERT(VARCHAR(5),getdate(),108) , time_schedule) between 0 AND 1
                        AND datename(dw,getdate()) IN(
                        SELECT hari FROM Lighting_day WHERE status = 1
                        )
                        ");
$cekon = mssql_num_rows($queryoon);
if($cekon !=0)
{
    //logs
    mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
    VALUES('','Automatic Schedule','SCHEDULE COMPRESSOR','ON','',
    'Menghidupkan Compressor sesuai dengan Pengaturan Schedule',
    getdate())
    ");

while($ron = mssql_fetch_assoc($queryoon)){
        $json_data_on      = file_get_contents("http://".$ron['ip_address'].":1880/comp/cmd?address=" . $ron['Remote_address'] . "&status=1");
        $dataon           = json_decode($json_data_on);
        mssql_query("UPDATE Compressor_akebono_scheduler SET 
                        last_process = getdate()
                        WHERE id = '".$ron['id']."'
                    "); 

        // echo $roff['Remote_address'];
    sleep(1); //kasih napas 
}
}


?>