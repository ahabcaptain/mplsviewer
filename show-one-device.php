<?php

// Global includes
include 'includes/header.php';
include 'includes/config.php';
include 'includes/connect.php';
include 'includes/mysql-functions.php';
include 'includes/php-functions.php';

// Mysql fucntions:
//
// $data = get_data($table);
// $vrf = get_data_vrf($table, 'ytranet');
// $single_device = get_data_device($table, 'hyscore01');
// $value = get_data_vrfvalue($table, $data, 'hyscore01', 'ytranet', 'rd');
//
// Gets lists of strings into an array, no data:
//
// $devices = get_devices($table);
// $RDs = get_RDs($table);
// $vrfs = get_vrfs($table);

// PHP functions:
//
// get_data_vrfvalue($table, Array $array, $device, $vrf, $searchkey) 
// super_unique($array,$key)
//

// #########################
// # Main code area starts #
// #########################

//$sort = 'vrf' ;
if($_POST['sort']!=''){ $sort = $_POST['sort']; } else { if($_GET['sort']){ $sort = $_GET['sort']; }}
if($_POST['select']==''){ if($_GET['select']) { $_POST['select'] = $_GET['select']; } }

echo "<body style='font-family: monospace; height: 100%' GCOLOR='#FFFFFF'>";
echo "<div align='center'>";
echo "<TABLE width='100%' CELLPADDING='0' cellspacing='0' width=550px class='generaltext' style='border-style:solid; border-width:1px; border-color: gray; margin-top:10px;'>";
echo "<tr>";
echo "<td style='text-align: left; background:#300060; color: white; padding:5px' valign='top'>";
echo "<B><FONT FACE='Verdana, Arial, Helvetica, sans-serif' SIZE='2'>";
echo "MPLS Viewer : Devices";
if($_POST['select'])
{
echo " : ".$_POST['select'];
}
echo "</FONT></B>";
echo "</td>";
echo "</tr>";
echo "</TABLE>";
echo "</div>";
echo "<form onsubmit='return show(this)' id='form1' name='form1' method='post' action='' accept-charset='UTF-8'>";
echo "<TABLE WIDTH='100%' BORDER='0' CELLSPACING='0' CELLPADDING='1' BGCOLOR='white'>";
echo "<TR>";
echo "<TD valign='top'>";
echo "<TABLE WIDTH='100%' BORDER='1' ALIGN='center' CELLPADDING='5' CELLSPACING='0'>";
echo "<TR>";
echo "<TD valign='top' WIDTH='135px' ROWSPAN='2' ALIGN='left' BGCOLOR='#FFFFFF'>";
echo "<b><FONT FACE='Arial, Helvetica, sans-serif' SIZE='2'>Type to search:</FONT></b>";
echo "<p style='margin-top:0px;'>";
echo "<SELECT style='float:left; width:160px' onclick='formSubmit()' NAME='select' SIZE='40'>";
echo "<option value='0'></option>";

$devices = get_devices($table);

foreach ($devices as $entry)
{
	echo "<OPTION VALUE=".$entry['device']." ";
	if ($_POST["select"]==$entry['device']) {echo "selected";} 
	echo ">".$entry['device']."</OPTION>";
}
unset($devices, $entry);

echo "</SELECT>";
echo "<input name='formtxt' onkeyup='search(this.form)' style='float:left; margin:3px 0px 0px -158px; padding-left:3px; width:138px; border:0px; background:#eee'>";
echo "</p>";
echo "<BR />";
echo "<INPUT TYPE='hidden' NAME='Submit' VALUE='View' /> <BR />";
echo "<INPUT TYPE='hidden' NAME='sort' VALUE='' /> ";
echo "</form>";
echo "</TD>";
echo "<TD ALIGN='left' BGCOLOR='#FFFFFF' style='padding-right:12px;'>";
echo "<div style='min-height:700px;'>";

if($_POST["select"])
{
	$data = get_data($table);
	$vrfdata = get_data_device($table, $_POST["select"]);
	echo "<b><FONT FACE='Arial, Helvetica, sans-serif' SIZE='2'>Sort exporters/importers by:</FONT></b>";
	echo "<input type='button' onclick='formSubmit(\"vrf\")' value='vrf'/>";
	echo "<input type='button' onclick='formSubmit(\"device\")' value='device'/>";
	echo "<table BGCOLOR='#F0F0F0' style='height: 100%;' width='100%' cellspacing='0' cellpadding='0' border='1'>";
        echo "<tr>";
	echo "<th BGCOLOR='#A0A0A0'>Device</th>";
	echo "<th BGCOLOR='#A0A0A0' style='width:370px'>Imported Communities</th>";
	echo "<th BGCOLOR='#A0A0A0' style='width:370px'>Exported Communities</th>";
//	echo "<th BGCOLOR='#A0A0A0'>Filters</th>";
	echo "</tr>";
	foreach (super_unique($vrfdata,'name') as $entry)
	{
		echo "<tr>";
		echo "<td BGCOLOR='#808080' style='width: 95px;'><center><b>";
                echo "<a style='text-decoration:none' href='show-one-vrf.php?select=".$entry['name'];
                if ($sort!='') { echo "&sort=".$sort; }
                echo "'>";
		echo $entry['name'];
		echo "</a>";
		echo "</b></center></td>";
		echo "<td style='vertical-align:top;'><center>";

// Import Communities start

				echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr>";
				echo "<th style='background-color: #D0D0D0; border-bottom: solid black 1px;'>Community</th>";
				echo "<th style='background-color: #D0D0D0; '>Exporters</th>";
				echo "</tr>";
		foreach ($vrfdata as $subentry)
		{
			if($subentry['name']==$entry['name'] && $subentry['import']!='')
			{
				echo "<tr><td style='padding-top: 5px; vertical-align:top; background-color: #F0F0F0; width: 100px; border-bottom: solid black 1px'><center><b>";
				echo $subentry['import']."<br>";
				echo "</b></center></td>";
				echo "<td style='background-color: #F0F0F0;'>";
				$result = '' ;
				if($sort!='device')
				{
					// sort by VRF
					$i = '0';
					foreach ($data as $dataentry)
					{
						if ($subentry['import']==$dataentry['export'])
						{
						 if ($dataentry['name']==$_POST['select'] && $dataentry['device']==$entry['device'])
						{
						} else
						 {
						 $result[] =  array('vrf' => $dataentry['name'], 'device' => $dataentry['device']);
						 asort($result);
						 $i++;
						 }
						}
					}
					if ($i=='0')
					{
						echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr>";
						echo "<td style='background-color: #D0D0D0; border-bottom: solid black 1px; border-left: solid black 1px; padding-left: 5px;'><b>vrf</b></td>";
						echo "<td style='background-color: #D0D0D0; border-bottom: solid black 1px; '><b>device</b></td>";
						echo "</tr>";
						echo "<tr><td style='border-bottom: solid black 1px'><center>";
						echo "<i><b><font color='#FF0000'>unused</color></b></i>";
						echo "</center></td>";
						echo "<td style='border-bottom: solid black 1px'><center>";
						echo "<i><b><font color='#F0F0F0'>unused</color></b></i>";
						echo "</center></td>";
						echo "</tr></table>";
					} else {
						echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr>";
						echo "<td style='background-color: #D0D0D0; border-bottom: solid black 1px; border-left: solid black 1px; padding-left: 5px;'><b>vrf</b></td>";
						echo "<td style='background-color: #D0D0D0; border-bottom: solid black 1px; '><b>device</b></td>";
						echo "</tr>";
					foreach (super_unique($result,'vrf') as $row)
					{
						echo "<tr><td style='width: 175px; padding-left: 4px; border-bottom: solid black 1px'>";
						echo "<b>";
						echo "<a style='text-decoration:none' href='show-one-vrf.php?select=".$row['vrf'];
						if ($sort!='') { echo "&sort=".$sort; }
						echo "'>";
						echo $row['vrf'];
						echo "</a>";
						echo "</b>";
						echo "</td>";
						echo "<td style='width: 95px; border-bottom: solid black 1px'>";
						echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'>";
						foreach ($result as $subrow)
						{
							if($subrow['vrf']==$row['vrf'])
							{
								echo "<tr><td>";
								echo $subrow['device'];
								echo "</td></tr>";
							}
						}
						echo "</table>";
					}
						echo "</td></tr></table>";
					}
				} else {
					// Sort by Devices
					$i = '0';
				        foreach ($data as $dataentry)
                                        {
                                                if ($subentry['import']==$dataentry['export'])
                                                {
                                                 if ($dataentry['name']==$_POST['select'] && $dataentry['device']==$entry['device'])
                                                {
                                                } else
                                                 {
                                                 $result[] =  array('device' => $dataentry['device'], 'vrf' => $dataentry['name']);
                                                 asort($result);
						 $i++;
                                                 }
                                                }
                                        }
					if ($i=='0')
					{
                                                echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr>";
                                                echo "<td style='width: 95px; background-color: #D0D0D0; border-bottom: solid black 1px; border-left: solid black 1px; padding-left: 5px;'><b>device</b></td>";
                                                echo "<td style='width: 175px; background-color: #D0D0D0; border-bottom: solid black 1px; '><b>vrf</b></td>";
                                                echo "</tr>";
                                                echo "<tr><td style='border-bottom: solid black 1px'><center>";
                                                echo "<i><b><font color='#FF0000'>unused</color></b></i>";
                                                echo "</center></td>";
                                                echo "<td style='border-bottom: solid black 1px'><center>";
                                                echo "<i><b><font color='#F0F0F0'>unused</color></b></i>";
                                                echo "</center></td>";
                                                echo "</tr></table>";
                                        } else {
                                                echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr>";
                                                echo "<td style='width: 95px; background-color: #D0D0D0; border-bottom: solid black 1px; border-left: solid black 1px; padding-left: 5px;'><b>device</b></td>";
                                                echo "<td style='width: 175px; background-color: #D0D0D0; border-bottom: solid black 1px; '><b>vrf</b></td>";
                                                echo "</tr>";

                                        foreach (super_unique($result,'device') as $row)
                                        {
                                                echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr>";
                                                echo "<td style='width: 95px; padding-left: 4px; border-bottom: solid black 1px'>";
                                                echo "<b>";
                                                echo $row['device'];
                                                echo "</b>";
                                                echo "</td>";
                                                echo "<td style='width: 175px; border-bottom: solid black 1px'>";
                                                echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'>";
                                                foreach ($result as $subrow)
                                                {
                                                        if($subrow['device']==$row['device'])
                                                        {
                                                                echo "<tr><td>";
                                                		echo "<a style='text-decoration:none' href='show-one-vrf.php?select=".$subrow['vrf'];
		                                                if ($sort!='') { echo "&sort=".$sort; }
		                                                echo "'>";
                                                                echo $subrow['vrf'];
								echo "</a>";
                                                                echo "</td></tr>";
                                                        }
                                                }
                                                echo "</table>";
                                                echo "</td></tr></table>";
                                        }
					}
				}
				reset($data);
				echo "</td>";
			} 
		} 
				echo "</tr></table>";
		echo "</center></td>";

// Export communities start (same code as import communities, if you change import a lot then just copy it here and change 'import' to 'export' etc.. much faster) ;-)

		echo "<td style='vertical-align:top;'><center>";
                                echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr>";
                                echo "<th style='background-color: #D0D0D0; border-bottom: solid black 1px;'>Community</th>";
                                echo "<th style='background-color: #D0D0D0; '>Importers</th>";
                                echo "</tr>";
                foreach ($vrfdata as $subentry)
                {
                        if($subentry['name']==$entry['name'] && $subentry['export']!='')
                        {
                                echo "<tr><td style='padding-top: 5px; vertical-align:top; background-color: #F0F0F0; width: 100px; border-bottom: solid black 1px'><center><b>";
                                echo $subentry['export']."<br>";
                                echo "</b></center></td>";
                                echo "<td style='background-color: #F0F0F0;'>";
                                $result = '' ;
                                if($sort!='device')
                                {
                                        // sort by VRF
                                        $i = '0';
                                        foreach ($data as $dataentry)
                                        {
                                                if ($subentry['export']==$dataentry['import'])
                                                {
                                                 if ($dataentry['name']==$_POST['select'] && $dataentry['device']==$entry['device'])
                                                {
                                                } else
                                                 {
                                                 $result[] =  array('vrf' => $dataentry['name'], 'device' => $dataentry['device']);
                                                 asort($result);
                                                 $i++;
                                                 }
                                                }
                                        }
                                        if ($i=='0')
                                        {
                                                echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr>";
                                                echo "<td style='background-color: #D0D0D0; border-bottom: solid black 1px; border-left: solid black 1px; padding-left: 5px;'><b>vrf</b></td>";
                                                echo "<td style='background-color: #D0D0D0; border-bottom: solid black 1px; '><b>device</b></td>";
                                                echo "</tr>";
                                                echo "<tr><td style='border-bottom: solid black 1px'><center>";
                                                echo "<i><b><font color='#FF0000'>unused</color></b></i>";
                                                echo "</center></td>";
                                                echo "<td style='border-bottom: solid black 1px'><center>";
                                                echo "<i><b><font color='#F0F0F0'>unused</color></b></i>";
                                                echo "</center></td>";
                                                echo "</tr></table>";
                                        } else {
                                                echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr>";
                                                echo "<td style='background-color: #D0D0D0; border-bottom: solid black 1px; border-left: solid black 1px; padding-left: 5px;'><b>vrf</b></td>";
                                                echo "<td style='background-color: #D0D0D0; border-bottom: solid black 1px; '><b>device</b></td>";
                                                echo "</tr>";
                                        foreach (super_unique($result,'vrf') as $row)
                                        {
                                                echo "<tr><td style='width: 175px; padding-left: 4px; border-bottom: solid black 1px'>";
                                                echo "<b>";
                                                echo "<a style='text-decoration:none' href='show-one-vrf.php?select=".$row['vrf'];
                                                if ($sort!='') { echo "&sort=".$sort; }
                                                echo "'>";
                                                echo $row['vrf'];
                                                echo "</a>";
                                                echo "</b>";
                                                echo "</td>";
                                                echo "<td style='width: 95px; border-bottom: solid black 1px'>";
                                                echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'>";
                                                foreach ($result as $subrow)
                                                {
                                                        if($subrow['vrf']==$row['vrf'])
                                                        {
                                                                echo "<tr><td>";
                                                                echo $subrow['device'];
                                                                echo "</td></tr>";
                                                        }
                                                }
                                                echo "</table>";
                                        }
                                                echo "</td></tr></table>";
                                        }
                                } else {
                                        // Sort by Devices
                                        $i = '0';
                                        foreach ($data as $dataentry)
                                        {
                                                if ($subentry['export']==$dataentry['import'])
                                                {
                                                 if ($dataentry['name']==$_POST['select'] && $dataentry['device']==$entry['device'])
                                                {
                                                } else
                                                 {
                                                 $result[] =  array('device' => $dataentry['device'], 'vrf' => $dataentry['name']);
                                                 asort($result);
                                                 $i++;
                                                 }
                                                }
                                        }
                                        if ($i=='0')
                                        {
                                                echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr>";
                                                echo "<td style='width: 95px; background-color: #D0D0D0; border-bottom: solid black 1px; border-left: solid black 1px; padding-left: 5px;'><b>device</b></td>";
                                                echo "<td style='width: 175px; background-color: #D0D0D0; border-bottom: solid black 1px; '><b>vrf</b></td>";
                                                echo "</tr>";
                                                echo "<tr><td style='border-bottom: solid black 1px'><center>";
                                                echo "<i><b><font color='#FF0000'>unused</color></b></i>";
                                                echo "</center></td>";
                                                echo "<td style='border-bottom: solid black 1px'><center>";
                                                echo "<i><b><font color='#F0F0F0'>unused</color></b></i>";
                                                echo "</center></td>";
                                                echo "</tr></table>";
                                        } else {
                                                echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr>";
                                                echo "<td style='width: 95px; background-color: #D0D0D0; border-bottom: solid black 1px; border-left: solid black 1px; padding-left: 5px;'><b>device</b></td>";
                                                echo "<td style='width: 175px; background-color: #D0D0D0; border-bottom: solid black 1px; '><b>vrf</b></td>";
                                                echo "</tr>";

                                        foreach (super_unique($result,'device') as $row)
                                        {
                                                echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr>";
                                                echo "<td style='width: 95px; padding-left: 4px; border-bottom: solid black 1px'>";
                                                echo "<b>";
                                                echo $row['device'];
                                                echo "</b>";
                                                echo "</td>";
                                                echo "<td style='width: 175px; border-bottom: solid black 1px'>";
                                                echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'>";
                                                foreach ($result as $subrow)
                                                {
                                                        if($subrow['device']==$row['device'])
                                                        {
                                                                echo "<tr><td>";
                                                                echo "<a style='text-decoration:none' href='show-one-vrf.php?select=".$subrow['vrf'];
                                                                if ($sort!='') { echo "&sort=".$sort; }
                                                                echo "'>";
                                                                echo $subrow['vrf'];
                                                                echo "</a>";
                                                                echo "</td></tr>";
                                                        }
                                                }
                                                echo "</table>";
                                                echo "</td></tr></table>";
                                        }
                                        }
                                }
                                reset($data);
                                echo "</td>";
                        }
                }
                                echo "</tr></table>";
		echo "</center></td>";
// Useless to show the actual name of the map with no extra info.. either need to fix parser to add the route-maps / acls .. or just add a mark on vrfs saying wether they are filtered or not.. that'd be the fastest way for now i think..

//		echo "<td><center>";
//		 foreach ($vrfdata as $subentry)
//                {
//                        if($subentry['device']==$entry['device'] && $subentry['importmap']!='')
//                        {
//			echo "Importmap: ".$subentry['importmap'];
//			echo "<br>";
//			}
//                        if($subentry['device']==$entry['device'] && $subentry['exportmap']!='')
//                        {
//			echo "Exportmap: ".$subentry['exportmap'];
//			echo "<br>";
//			}
//		}
//
//		echo "</center></td>";
		echo "</tr>";
	}
	echo "</table>";
	
} else {
	echo "<b>Select from the list.</b><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
}

echo "</TD></TR></table>";

// Cleanup
unset($data, $vrf);

// Include footer
include 'includes/footer.php';

?>
