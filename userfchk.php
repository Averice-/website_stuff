<?php
/**********************************
	Copyright: Aaron Skinner 2013;
**********************************/

include_once('includes/config.php');
include_once('includes/core/connect.php');
$Connection = new Connection($GDATA);
$Connection->Connect();

$field = mysql_real_escape_string($_GET["f"]);
$data = mysql_real_escape_string($_GET["s"]);

$quRes = $Connection->Query("SELECT * FROM devnet_users WHERE $field='$data'");
if( mysql_num_rows($quRes) > 0 ){
	echo true;
}else{
	echo false;
}
$Connection->Close();
?>