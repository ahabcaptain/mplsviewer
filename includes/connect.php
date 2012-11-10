<?php
// connect to database (change to inclide statement at a later point)
mysql_connect ("localhost", $user, $pass) or die('could not connect to database');
mysql_select_db ($database);

?>
