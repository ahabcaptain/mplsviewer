<?php

// Get value from a vrf in php array, only works with RD right now. needs fix.
function get_data_vrfvalue($table, Array $array, $device, $vrf, $searchkey) {
    foreach ($array as $subarray){
        if ($subarray['device'] == $device && $subarray['name'] == $vrf && $subarray[$searchkey] != '')
          return $subarray[$searchkey];
    }
}

// Make array key unique in php array (like 'select distinct' in mysql)
function super_unique($array,$key)
{
   $temp_array = array();
   foreach ($array as &$v) {
       if (!isset($temp_array[$v[$key]]))
       $temp_array[$v[$key]] =& $v;
   }
   $array = array_values($temp_array);
   return $array;
}

?>
