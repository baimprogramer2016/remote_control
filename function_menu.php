<?php

//check menu jika ada access dalam 1 role
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
function checkHeadMenu($data, $param)
{
    foreach($data as $item)
    {
        if($item['head_access_code'] == $param)
        {
            return true;
            break;
        }
       
    }
    return false;
}
?>