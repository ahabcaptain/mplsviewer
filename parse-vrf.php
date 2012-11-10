<?php

// this file needs some cleanup .. it's using two directories as sources from rancid. easily made into one or more..

// Includes
include 'includes/header.php';
include 'includes/config.php';
include 'includes/connect.php';

function makesql($Device, $Name, $Value, $Type, $Vrftype, $Address_family) {
$returnstring = "INSERT INTO ipvrf (`device`, `name`, `".$Type."`, `type`, `vrftype`, `address-family`) VALUES ('".$Device."', '".$Name."', '".$Value."', '".$Type."', '".$Vrftype."', '".$Address_family."');\n";
mysql_query($returnstring) or die ('Error entering: $returnstring');
return $returnstring;
}

function parseconfig($fileName) {
    $string = ""; $vrftype = ""; $done = "0";
    if(substr($fileName,0,28) == '/var/rancid/var/hys/configs/') { 
       $device = preg_replace('#/var/rancid/var/hys/configs/#','',$fileName);
    }
    if(substr($fileName,0,30) == '/var/rancid/var/skyrr/configs/') { 
       $device = preg_replace('#/var/rancid/var/skyrr/configs/#','',$fileName);
    }
    if(file_exists($fileName))
    {
        $file = fopen($fileName,'r');
        while(!feof($file)) 
	{ 
		$name = fgets($file);
		if(substr($name,0,10) == 'interface ') { $done = "1"; }
		if($done!="1")
		{ // stop running after ^interface is hit.
			$array = explode(' ', $name);
			if(substr($name,0,7) == 'ip vrf ')
			{
				$vrf = trim($array[2]);
				$vrftype = 'ipvrf';
				$vrfhit = "1";
			} elseif (substr($name,0,15) == 'vrf definition ')
			{
		                $vrf = trim($array[2]);
		                $vrftype = 'definition';
		                $vrfhit = "1";
			} elseif (substr($name,0,16) == ' address-family ')
			{
				$address_family = trim($array[2]);
			} else 
			{
			// set different lookup based on vrf type
			if($vrftype=="ipvrf")
			{
		        	if(substr($name,0,13) == ' description ') { 
					if($vrfhit=="1") {
						$sliced = array_slice($array, 2);
						$imploded = implode(' ',$sliced);
						$string .= makesql($device, $vrf, trim($imploded), 'desc', $vrftype, '');
						$vrfhit = "0";
						}
				} else {		
			        	if(substr($name,0,4) == ' rd ') { $string .= makesql($device, $vrf, trim($array[2]), 'rd', $vrftype, '');
				} else {		
			        	if(substr($name,0,21) == ' route-target export ') { $string .= makesql($device, $vrf, trim($array[3]), 'export', $vrftype, '');
				} else {
			        	if(substr($name,0,21) == ' route-target import ') { $string .= makesql($device, $vrf, trim($array[3]), 'import', $vrftype, '');
				} else {
			        	if(substr($name,0,12) == ' export map ') { $string .= makesql($device, $vrf, trim($array[3]), 'exportmap', $vrftype, '');
				} else {
			        	if(substr($name,0,12) == ' import map ') { $string .= makesql($device, $vrf, trim($array[3]), 'importmap', $vrftype, '');
				} else {
					$vrfhit = "0";
				}}}}}}
			} elseif($vrftype=="definition")
			{
                                if(substr($name,0,13) == ' description ') {
					if($vrfhit=="1")
					{
                                                $sliced = array_slice($array, 2);
                                                $imploded = implode(' ',$sliced);
                                                $string .= makesql($device, $vrf, trim($imploded), 'desc', $vrftype, '');
                                                $vrfhit = "0";
                                        }
                                } else {
                                        if(substr($name,0,4) == ' rd ') { $string .= makesql($device, $vrf, trim($array[2]), 'rd', $vrftype, '');
                                } else {
                                        if(substr($name,0,22) == '  route-target export ') { $string .= makesql($device, $vrf, trim($array[4]), 'export', $vrftype, $address_family);
                                } else {
                                        if(substr($name,0,22) == '  route-target import ') { $string .= makesql($device, $vrf, trim($array[4]), 'import', $vrftype, $address_family);
                                } else {
                                        if(substr($name,0,13) == '  export map ') { $string .= makesql($device, $vrf, trim($array[4]), 'exportmap', $vrftype, $address_family);
                                } else {
                                        if(substr($name,0,13) == '  import map ') { $string .= makesql($device, $vrf, trim($array[4]), 'importmap', $vrftype, $address_family);
                                } else {
                                        $vrfhit = "0";
                                }}}}}}
			}
			}
		} // stop running after ^interface is hit.
	}
        fclose($file);
    } else { echo "File not found: ".$fileName;  }

return $string;
}

function get_vrf_enabled_devices() {

	shell_exec("grep -l \"^ip vrf \" /var/rancid/var/hys/configs/* | grep -v .new$ > /var/www/html/mpls/check.txt");
	shell_exec("grep -l \"^ip vrf \" /var/rancid/var/skyrr/configs/* |  grep -v .new$ >> /var/www/html/mpls/check.txt");
	shell_exec("echo > /var/www/html/mpls/vrfenabled.txt");
	$file = fopen("/var/www/html/mpls/check.txt",'r');
	while(!feof($file)) {
		$name = fgets($file);
		if(trim($name)!=""){
		shell_exec("grep -l '^ rd ' ".trim($name)." >> /var/www/html/mpls/vrfenabled.txt");
		}
	}
}

function countsql() {
$result = mysql_query("SELECT ID FROM ipvrf") or die('countsql: Could not read table.');
$num_rows = mysql_num_rows($result);
return $num_rows;
}

// Main area starts
$count = countsql();
echo "<pre>";
echo "Flusing ".$count." records from database: ".$database.".<br><br>";
mysql_query("truncate table ipvrf") or die ('Error clearing table.');
echo ".. parsing rancid configs ...<br><br>";
get_vrf_enabled_devices();
$file = fopen("/var/www/html/mpls/vrfenabled.txt",'r');
while(!feof($file)) {
	$name = trim(fgets($file));
	if(trim($name)!=""){ // for some reason all these while loops run once after last line of file.. .. fix
//		echo "<br><hr width='100%'><br>Device: ".$name."<br><br>";
		$parsedconfig = parseconfig($name);
		parse_str($parsedconfig, $array);
//		print($parsedconfig);
//		print_r($array);
	}
}

$count = countsql();
echo "Imported ".$count." records to database: ".$database." <br>";

// Include footer
include 'includes/footer.php';

?>
