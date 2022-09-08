<?php
session_start();
if($_SESSION['npk'] == "")
{
  header("location:index.php");
}
include('configs/db.php');

//get last id 
function getLastId() {
    $result = mssql_fetch_assoc(mssql_query("select @@IDENTITY as id"));
    return $result['id'];
}


//submit ac scheduler
if($_GET['type'] == 'scheduler-ac-submit')
{
    $address        = $_POST['address'];
    $time           = $_POST['time'];
    $port           = $_POST['port'];
    $status         = $_POST['status'];
    $description    = $_POST['description'];

    $query_insert_scheduler = "INSERT INTO AC_akebono_scheduler(Remote_address, time_schedule, status_schedule, last_process, port)
                               VALUES('".$address."','".$time."','".$status."',getdate(),'".$port."')   
                              ";
    $result                 =   mssql_query($query_insert_scheduler);
    $id                     = getLastId();

  
    if($result)
    {   
         //insert logs
         mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
         VALUES('".$_SESSION['npk']."','".$_SESSION['full_name']."','SETTING AC','".strtoupper($status)."','".$time."',
         'Membuat Schedule AC dengan status ".strtoupper($status)." pukul ".$time." untuk ruangan ".$description."',
         getdate())
         ");

        $response = array("statuscode" => 200, "address" => $address, "time" =>$time, "port" => $port, "status" => ucfirst($status), "lastid" => $id);
    }else{
        $response = array("statuscode" => 400);
    }    
    // $response = array("statuscode" => 200, "address" => $address, "time" =>$time, "port" => $port, "status" =>ucfirst($status));                          
    echo json_encode($response);
}

//delete ac scheduler
if($_GET['type'] == 'scheduler-ac-delete')
{
    $paramid        = $_GET['param_id'];

    $query_scheduler = mssql_query("SELECT a.*, b.Description FROM AC_akebono_scheduler a, AC_akebono b WHERE a.Remote_address = b.Remote_address AND a.port = b.port AND a.id = '".$paramid."'");
    $rdelete         =    mssql_fetch_assoc($query_scheduler);


    $query_delete_scheduler = "DELETE FROM AC_akebono_scheduler WHERE id = '".$paramid."'";
    $result                 =   mssql_query($query_delete_scheduler);

    if($result)
    {   
         //insert logs
         mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
         VALUES('".$_SESSION['npk']."','".$_SESSION['full_name']."','SETTING AC','DELETE','".$rdelete['time_schedule']."',
         'Menghapus Schedule AC dengan status ".strtoupper($rdelete['status_schedule'])." pukul ".$rdelete['time_schedule']." untuk ruangan ".$rdelete['Description']."',
         getdate())
         ");

        $response = array("statuscode" => 200);
    }else{
        $response = array("statuscode" => 400);
    }    
    $response = array("statuscode" => 200);                          
    echo json_encode($response);
}

//=====================================================================================
//submit lighting scheduler
if($_GET['type'] == 'scheduler-lighting-submit')
{
    $address        = $_POST['address'];
    $time           = $_POST['time'];
    $ip_address     = $_POST['ip_address'];
    $status         = $_POST['status'];
    $description    = $_POST['description'];

    $query_insert_scheduler = "INSERT INTO Lamp_akebono_scheduler(Remote_address, time_schedule, status_schedule, last_process, ip_address)
                               VALUES('".$address."','".$time."','".$status."',getdate(),'".$ip_address."')   
                              ";
    $result                 =   mssql_query($query_insert_scheduler);
    $id                     =   getLastId();

  
    if($result)
    {   
          //insert logs
          mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
          VALUES('".$_SESSION['npk']."','".$_SESSION['full_name']."','SETTING LIGHTING','".strtoupper($status)."','".$time."',
          'Membuat Schedule Lampu dengan status ".strtoupper($status)." pukul ".$time." untuk ruangan ".$description."',
          getdate())
          ");
          
        $response = array("statuscode" => 200, "address" => $address, "time" =>$time, "ip_address" => $ip_address, "status" => ucfirst($status), "lastid" => $id);
    }else{
        $response = array("statuscode" => 400);
    }    
    // $response = array("statuscode" => 200, "address" => $address, "time" =>$time, "ip_address" => $ip_address, "status" =>ucfirst($status));                          
    echo json_encode($response);
}


//delete lighting scheduler
if($_GET['type'] == 'scheduler-lighting-delete')
{
    $paramid        = $_GET['param_id'];

    $query_scheduler = mssql_query("SELECT a.*, b.Description FROM Lamp_akebono_scheduler a, Lighting_akebono b WHERE a.Remote_address = b.Address AND a.ip_address = b.ip_address AND a.id = '".$paramid."'");
    $rdelete         =    mssql_fetch_assoc($query_scheduler);


    $query_delete_scheduler = "DELETE FROM Lamp_akebono_scheduler WHERE id = '".$paramid."'";
    $result                 =   mssql_query($query_delete_scheduler);

    if($result)
    {   
           //insert logs
           mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
           VALUES('".$_SESSION['npk']."','".$_SESSION['full_name']."','SETTING LIGHTING','DELETE','".$rdelete['time_schedule']."',
           'Menghapus Schedule Lampu dengan status ".strtoupper($rdelete['status_schedule'])." pukul ".$rdelete['time_schedule']." untuk ruangan ".$rdelete['Description']."',
           getdate())
           ");

        $response = array("statuscode" => 200);
    }else{
        $response = array("statuscode" => 400);
    }    
    $response = array("statuscode" => 200);                          
    echo json_encode($response);
}



//setting hari aktif
if($_GET['type'] == 'scheduler-day-submit')
{
    $hari           = $_POST['hari'];
    $status         = $_POST['status'];

    if($status == "true")
    {
        $sts = 1;
        $sts_ket = "ON";
    }else{
        $sts = 0;
        $sts_ket = "OFF";
    }
    $update_query = mssql_query("UPDATE Lighting_day SET
                                 status = '".$sts."'   
                                 WHERE hari = '".$hari."'       
                                ");

        //  insert logs
         mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
         VALUES('".$_SESSION['npk']."','".$_SESSION['full_name']."','SETTING HARI','".strtoupper($status)."','".$time."',
         'Mengubah Pengaturan Hari ".$hari." dengan status ".strtoupper($sts_ket)."',
         getdate())
         ");

         $response = array("statuscode" => 200);                          
         echo json_encode($response);

}


//=====================================================================================
//submit compressor scheduler
if($_GET['type'] == 'scheduler-compressor-submit')
{
    $address        = $_POST['address'];
    $time           = $_POST['time'];
    $ip_address     = $_POST['ip_address'];
    $status         = $_POST['status'];
    $description    = $_POST['description'];

    $query_insert_scheduler = "INSERT INTO Compressor_akebono_scheduler(Remote_address, time_schedule, status_schedule, last_process, ip_address)
                               VALUES('".$address."','".$time."','".$status."',getdate(),'".$ip_address."')   
                              ";
    $result                 =   mssql_query($query_insert_scheduler);
    $id                     =   getLastId();

  
    if($result)
    {   
          //insert logs
          mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
          VALUES('".$_SESSION['npk']."','".$_SESSION['full_name']."','SETTING COMPRESSOR','".strtoupper($status)."','".$time."',
          'Membuat Schedule Compressor dengan status ".strtoupper($status)." pukul ".$time." untuk ruangan ".$description."',
          getdate())
          ");
          
        $response = array("statuscode" => 200, "address" => $address, "time" =>$time, "ip_address" => $ip_address, "status" => ucfirst($status), "lastid" => $id);
    }else{
        $response = array("statuscode" => 400);
    }    
    // $response = array("statuscode" => 200, "address" => $address, "time" =>$time, "ip_address" => $ip_address, "status" =>ucfirst($status));                          
    echo json_encode($response);
}


//delete comrepssor scheduler
if($_GET['type'] == 'scheduler-compressor-delete')
{
    $paramid        = $_GET['param_id'];

    $query_scheduler = mssql_query("SELECT a.*, b.Description FROM Compressor_akebono_scheduler a, Compressor_akebono b WHERE a.Remote_address = b.Address AND a.ip_address = b.ip_address AND a.id = '".$paramid."'");
    $rdelete         =    mssql_fetch_assoc($query_scheduler);


    $query_delete_scheduler = "DELETE FROM Compressor_akebono_scheduler WHERE id = '".$paramid."'";
    $result                 =   mssql_query($query_delete_scheduler);

    if($result)
    {   
           //insert logs
           mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
           VALUES('".$_SESSION['npk']."','".$_SESSION['full_name']."','SETTING COMPRESSOR','DELETE','".$rdelete['time_schedule']."',
           'Menghapus Schedule Compressor dengan status ".strtoupper($rdelete['status_schedule'])." pukul ".$rdelete['time_schedule']." untuk ruangan ".$rdelete['Description']."',
           getdate())
           ");

        $response = array("statuscode" => 200);
    }else{
        $response = array("statuscode" => 400);
    }    
    $response = array("statuscode" => 200);                          
    echo json_encode($response);
}

  
if($_GET["type"] == 'search_name_auto'){

    include('configs/db_usr.php');

    $term = trim(strip_tags($_GET['term']));
    
    $query_account  =   mssql_query("SELECT emp_no, full_name FROM VIEW_EMPLOYEE_NG WHERE full_name LIKE '%".$term."%'");
    $rowaccount     =   mssql_num_rows($query_account);
    $array=array();
    if($rowaccount != 0)
    {
        while($raccount = mssql_fetch_assoc($query_account))
        {
            $row['value']=$raccount['full_name'];
            array_push($array, $row);
        }
    }
    else {
        $array = [];  
    }  
    echo json_encode($array);
  }
  
  //simpan setting access

//save access master
if($_GET['type'] == 'setting-access-submit')
{
    $access_code        = $_POST['access_code_param'];
    $access             = $_POST['access_param'];
    $description        = $_POST['description_param'];

    $query_scheduler    = mssql_query("SELECT TOP 1 id,access_code FROM Lighting_access WHERE access_code = '".$access_code."' ");
    $checkNum           = mssql_fetch_assoc($query_scheduler);

    if($checkNum != "0")
    {
 
        $id             =   $checkNum['id'];
        $result         =   mssql_query("UPDATE Lighting_access SET
                            access = '".$access."',
                            description = '".$description."'
                            WHERE access_code = '".$access_code."'");
        $message        =  "Data Updated";
        $response_code  =   205;

    }else{
        $query_insert   = "INSERT INTO Lighting_access (access_code, access, description,lastupdate) VALUES('".$access_code."','".$access."','".$description."',getdate())";
        $result         =   mssql_query($query_insert);
        $id             =   getLastId();
        $message        =  "Data Saved";
        $response_code  =   200;
    }


    if($result)
    {   
           //insert logs
           mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
           VALUES('".$_SESSION['npk']."','".$_SESSION['full_name']."','SETTING ACCESS','INSERT','',
           'Menambahkan Master Access dengan kode ".strtoupper($access_code)." ',
           getdate())
           ");

        $response = array("statuscode" => $response_code,"message" => $message,"access_code" => $access_code,"access" => $access, "description" => $description,"lastid"=>$id);
    }else{
        $response = array("statuscode" => 400,"message" => "Failed to Save Data");
    }    
                        
  

    echo json_encode($response);
}



//delete access master
if($_GET['type'] == 'setting-access-delete')
{
    $paramid          = $_GET['param_id'];
    $paramcode        = $_GET['param_code'];

    $query_delete_scheduler = "DELETE FROM Lighting_access WHERE id = '".$paramid."'";
    $result                 =   mssql_query($query_delete_scheduler);

    // mssql_query("DELETE from Lighting_access_area where area_code = '".$param_code."'");

    if($result)
    {   
           //insert logs
           mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
           VALUES('".$_SESSION['npk']."','".$_SESSION['full_name']."','SETTING ACCESS','DELETE','',
           'Menghapus Access dengan kode ".strtoupper($paramcode)."',
           getdate())
           ");
           

        $response = array("statuscode" => 200,"message" => "Deletion is successfully ");
    }else{
        $response = array("statuscode" => 400,"message" => "Deletion is Failed ");
    }    
                      
    echo json_encode($response);
}



//save role master
if($_GET['type'] == 'setting-role-submit')
{
    $role_code        = $_POST['role_code_param'];
    $role             = $_POST['role_param'];
   
    $query_scheduler    = mssql_query("SELECT TOP 1 role_code FROM Lighting_role WHERE role_code = '".$role_code."' ");
    $checkNum           = mssql_fetch_assoc($query_scheduler);

    if($checkNum != "0")
    {
        $response = array("statuscode" => 300, "message" => "Code must be Unique");
    }else{

    $query_insert = "INSERT INTO Lighting_role (role_code, role,lastupdate) VALUES('".$role_code."','".$role."',getdate())";
    $result       =   mssql_query($query_insert);
    $id           =   getLastId();

    if($result)
    {   
        
           //insert logs
           mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
           VALUES('".$_SESSION['npk']."','".$_SESSION['full_name']."','SETTING ROLE','INSERT','',
           'Menambahkan Master Role dengan kode ".strtoupper($role_code)." ',
           getdate())
           ");

        $response = array("statuscode" => 200,"message" => "success ","role_code" => $role_code, "role" => $role,"lastid"=>$id);
    }else{
        $response = array("statuscode" => 400,"message" => "Failed to Save Data");
    }    
                        
  
    }
    echo json_encode($response);
}




//delete access master
if($_GET['type'] == 'setting-role-delete')
{
    $paramid          = $_GET['param_id'];
    $paramcode        = $_GET['param_code'];

    //check lisghting_role
    $checkqueryrl   = mssql_query("SELECT top 1 id from Lighting_user WHERE role_code = '".$paramcode."'");
    $ckrl           = mssql_num_rows($checkqueryrl);
    if($ckrl == 0)
    {
            $query_delete_scheduler = "DELETE FROM Lighting_role WHERE id = '".$paramid."'";
            $result                 =   mssql_query($query_delete_scheduler);

            mssql_query("DELETE FROM Lighting_mapping_access WHERE role_code = '".$paramcode."'");
            mssql_query("DELETE FROM Lighting_role_area WHERE role_code = '".$paramcode."'");

            if($result)
            {   
                //insert logs
                mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
                VALUES('".$_SESSION['npk']."','".$_SESSION['full_name']."','SETTING ROLE','DELETE','',
                'Menghapus Role dengan kode ".strtoupper($paramcode)."',
                getdate())
                ");
                

                $response = array("statuscode" => 200,"message" => "Deletion is successfully ");
            }else{
                $response = array("statuscode" => 400,"message" => "Deletion is Failed ");
            }    
    }else{
        $response = array("statuscode" => 400,"message" => "Failed, Role is Being Used");
    }                        
    echo json_encode($response);
}

//simpan mapping role dan access

if($_GET['type'] == 'setting-mapping-access')
{
    $rolecode = $_POST['role_code_param'];
    $mappingaccess = $_POST['mapping_access'];
    
    $query_delete_role = mssql_query("DELETE FROM Lighting_mapping_access WHERE role_code = '".$rolecode."'");

    if($query_delete_role)
    {
        foreach($mappingaccess as $key => $item_access)
        {
            mssql_query("INSERT INTO Lighting_mapping_access(role_code, access_code, lastupdate) VALUES('".$rolecode."','".$item_access."',getdate())");
        }
    }
}
//simpan mapping role dan area

if($_GET['type'] == 'setting-mapping-area')
{
    $rolecode = $_POST['role_code_param'];
    $mappingarea = $_POST['mapping_access'];
    
    $query_delete_role = mssql_query("DELETE FROM Lighting_role_area WHERE role_code = '".$rolecode."'");

    if($query_delete_role)
    {
        foreach($mappingarea as $key => $item_area)
        {
            mssql_query("INSERT INTO Lighting_role_area(role_code, area, lastupdate) VALUES('".$rolecode."','".$item_area."',getdate())");
        }
    }
}



//save account master
if($_GET['type'] == 'setting-account-submit')
{
    $npk_split          = explode('|',$_POST['npk_param']);
    $npk                = $npk_split[0];
    $name               = $npk_split[1];
    $role_code          = $_POST['role_code_param'];

    $query_scheduler    = mssql_query("SELECT TOP 1 id, npk,role_code FROM Lighting_user WHERE npk = '".$npk."' ");
    $checkNum           = mssql_fetch_assoc($query_scheduler);

    if($checkNum != "0")
    {
 
        $id             =   $checkNum['id'];
        $result         =   mssql_query("UPDATE Lighting_user SET
                            npk         = '".$npk."',
                            role_code   = '".$role_code."',
                            name        = '".$name."',
                            lastupdate  = getdate()
                            WHERE npk   = '".$npk."'");
        $message        =  "Data Updated";
        $response_code  =   205;

    }else{
        $query_insert   = "INSERT INTO Lighting_user (npk, name, role_code,lastupdate) VALUES('".$npk."','".$name."','".$role_code."',getdate())";
        $result         =   mssql_query($query_insert);
        $id             =   getLastId();
        $message        =  "Data Saved";
        $response_code  =   200;
    }


    if($result)
    {   
           //insert logs
           mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
           VALUES('".$_SESSION['npk']."','".$_SESSION['full_name']."','SETTING ACCOUNT','INSERT','',
           'Menambahkan Master Account dengan Npk ".strtoupper($npk)." ',
           getdate())
           ");

        $response = array("statuscode" => $response_code,"message" => $message,"npk" => $npk,"name" => $name, "role_code" => $role_code,"lastid"=>$id);
    }else{
        $response = array("statuscode" => 400,"message" => "Failed to Save Data");
    }    
                        
  

    echo json_encode($response);
}



//delete account master
if($_GET['type'] == 'setting-account-delete')
{
    $paramid          = $_GET['param_id'];
    $param_npk        = $_GET['param_npk'];

    $query_delete_scheduler = "DELETE FROM Lighting_user WHERE id = '".$paramid."'";
    $result                 =   mssql_query($query_delete_scheduler);

    // mssql_query("DELETE from Lighting_access_area where area_code = '".$param_npk."'");

    if($result)
    {   
           //insert logs
           mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
           VALUES('".$_SESSION['npk']."','".$_SESSION['full_name']."','SETTING ACCOUNT','DELETE','',
           'Menghapus Account dengan NPK ".strtoupper($param_npk)."',
           getdate())
           ");
           

        $response = array("statuscode" => 200,"message" => "Deletion is successfully ");
    }else{
        $response = array("statuscode" => 400,"message" => "Deletion is Failed ");
    }    
                      
    echo json_encode($response);
}


?>