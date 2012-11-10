<?php

// Proccessing time counter start
$start=microtime();
$start=explode(" ",$start);
$start=$start[1]+$start[0];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style='height: 100%' xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript">
function formSubmit(df)
{
document.form1.sort.value = df;
document.getElementById("form1").submit();
}

function search(df) {
	val = df.formtxt.value;
	for (var i=0; i<df.select.options.length; i++) {
		var selected = df.select.options[i].text;
		var re = new RegExp(val,"i");
		if (re.test(selected)) {
			df.select.selectedIndex = i;
			break;
		} 
	}
	return false;
}

function show(df) {
	// alert(df.select.options[df.select.selectedIndex].text);
        formSubmit();
	return false;
}


</script>
</head>
