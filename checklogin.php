<?php
    // include('getdata.php');


    function checkLogin($url, $data){
        $postdata = http_build_query($data);

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => $postdata
            )
        );

        $context = stream_context_create($opts);
        return file_get_contents($url, false, $context);
        // return $context;
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $url 	  = 'this url';
    $data     = array("user" => $username, "pass" => $password);

    include('configs/db.php');
    $querycekakses  = mssql_query("SELECT top 1 * FROM Lighting_user WHERE npk = '".$username."'");
    $ceknum         =   mssql_num_rows($querycekakses);
    $rrole          = mssql_fetch_assoc($querycekakses);
    if($ceknum !=0)
    {
            $json       = checkLogin($url,$data);
            $json_data  = json_decode($json, true);
            $success    = $json_data['success'];

             //dapatin nama
             include('configs/db_usr.php');
             $query_name = mssql_query("SELECT first_name, full_name FROM VIEW_EMPLOYEE_NG WHERE emp_no = '".$username."'");
             $rname = mssql_fetch_assoc($query_name);
             $name = $rname['first_name'];
             $full_name = $rname['full_name'];

            if($success)
            {
                 //log login
                include('configs/db.php');
                $tes = mssql_query("INSERT INTO lighting_logs (npk,name, type,status,settime,description,lastupdate)
                VALUES('$username','$full_name','LOGIN','success','','Login pada waktu '+convert(char(16), getdate(), 121),getdate())
                ");

                session_start();
                $_SESSION['npk']            =  $username;    
                $_SESSION['name']           =  $name;
                $_SESSION['full_name']      =  $full_name;
                $_SESSION['role']           =  $rrole['role_code'];
                
                //ambil access berdasarkan role
                if($rrole['role_code'] != 'ADMIN')
                {
                    $array_access_login = array();
                    $queryroleaccess    = mssql_query("SELECT access_code  FROM Lighting_mapping_access WHERE role_code ='".$rrole['role_code']."'");    
                    while($rcode        = mssql_fetch_assoc($queryroleaccess))
                    {
                        array_push($array_access_login, array("access_code" => $rcode['access_code']));
                    }
                    $_SESSION['access'] =  $array_access_login;

                    //untuk cek head menu
                    $array_head_menu    =   array();
                    $query_head         =   mssql_query("SELECT distinct head_access_code 
                                                        FROM Lighting_access a, Lighting_mapping_access b
                                                        where a.access_code = b.access_code 
                                                        AND b.role_code = '".$rrole['role_code']."'
                                                        AND a.head_access_code is not null ");
                    while($rhead        =   mssql_fetch_assoc($query_head))
                    {
                        array_push($array_head_menu, array("head_access_code" => $rhead['head_access_code']));
                    }
                    $_SESSION['head_access'] =  $array_head_menu;

                }
                else
                {
                    $_SESSION['access']         =  [];   
                    $_SESSION['head_access']         =  [];   
                }

                echo "success";
            }
            else
            {
                //insert logs
                include('configs/db.php');
                mssql_query("INSERT INTO lighting_logs (npk,name,type,status,settime,description,lastupdate)
                VALUES('$username','$full_name','LOGIN','failed','','Login pada waktu '+convert(char(16), getdate(), 121),getdate())
                ");
                echo "failed";
            }

    }else{
        echo  "failed";
    }

?>
