<?php
/**********************************
	Copyright: Aaron Skinner 2013;
**********************************/

include_once('includes/config.php');
include_once('includes/core/connect.php');
$Connection = new Connection($GDATA);
$Connection->Connect();

$idpost = mysql_real_escape_string($_GET['id']);
$queryResulting = $Connection->Query("UPDATE devnet_users SET authed='1' WHERE regid='$idpost'");
if( $queryResulting ){
	//success
}
$Connection->Close();
?>