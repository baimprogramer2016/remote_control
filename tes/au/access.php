<?php
session_start();
include('../../configs/db.php');
//ambil access berdasarkan role

function checkMenu($data, $param)
{
    foreach($data as $item)
    {
        if($item['access_code'] == $param)
        {
            return true;
            break;
        }
    }
    return false;
}
print_r($_SESSION['access']).'<br><br>';

$result = checkMenu($_SESSION['access'], 'setting-acx');
if($result)
{
    echo "ada";
}
else{
    echo "gl ada ";
}
?>
