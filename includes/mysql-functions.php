<?php

// Get all data in table ipvrf
function get_data($table) {
$SQL = "SELECT * FROM ".$table;
$result = mysql_query($SQL);
while ($db_field = mysql_fetch_assoc($result))
{
        $arr[] = $db_field;
}
return $arr;
unset($arr); unset($db_field); unset($result); unset($SQL);
}

// Get all device names
function get_devices($table) {
$SQL = "select distinct device from ".$table." order by device";
$result = mysql_query($SQL);
while ($db_field = mysql_fetch_assoc($result))
{
        $arr[] = $db_field;
}
return $arr;
unset($arr); unset($db_field); unset($result); unset($SQL);
}

// Get all vrf names
function get_vrfs($table) {
$SQL = "select distinct name from ".$table." order by name";
$result = mysql_query($SQL);
while ($db_field = mysql_fetch_assoc($result))
{
        $arr[] = $db_field;
}
return $arr;
unset($arr); unset($db_field); unset($result); unset($SQL);
}

// Get RD values
function get_RDs($table) {
$SQL = "select distinct rd from ".$table." order by rd";
$result = mysql_query($SQL);
while ($db_field = mysql_fetch_assoc($result))
{
        $arr[] = $db_field;
}
return $arr;
unset($arr); unset($db_field); unset($result); unset($SQL);
}


// Get data for one vrf
function get_data_vrf($table, $var1) {
$SQL = "SELECT * FROM ".$table." where name = '".$var1."'";
$result = mysql_query($SQL);
while ($db_field = mysql_fetch_assoc($result))
{
        $arr[] = $db_field;
}
return $arr;
unset($arr); unset($db_field); unset($result); unset($SQL);
unset($var1);
}

// Get data for one device
function get_data_device($table, $var1) {
$SQL = "SELECT * FROM ".$table." where device = '".$var1."'";
$result = mysql_query($SQL);
while ($db_field = mysql_fetch_assoc($result))
{
        $arr[] = $db_field;
}
return $arr;
unset($arr); unset($db_field); unset($result); unset($SQL);
unset($var1);
}

?>
