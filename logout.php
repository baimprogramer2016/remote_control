<?php
session_start();
   //log login
   include('configs/db.php');
   mssql_query("INSERT INTO lighting_logs (npk,name, type,status,settime,description,lastupdate)
   VALUES('$_SESSION[npk]','".$_SESSION['full_name']."','LOGOUT','success','','Logout pada waktu '+convert(char(16), getdate(), 121),getdate())
   ");

session_destroy();
header("location:index.php");
?>