<?php
/**********************************
	Copyright: Aaron Skinner 2013;
**********************************/

session_start();

include_once('includes/config.php');
include_once('includes/core/connect.php');

$Connection = new Connection($GDATA);
$Connection->Connect();

// Registration here:
$regDetails = array();
$regDetails['uname'] = mysql_real_escape_string(htmlspecialchars($_POST['lguname']));
$regDetails['password'] = hash('sha256', $_POST['lgpass']);

$queryResult = $Connection->Query("SELECT * FROM devnet_users WHERE uname = '".$regDetails['uname']."' AND password = '".$regDetails['password']."'");

if( mysql_num_rows($queryResult) == 1 ){
	$row = mysql_fetch_assoc($queryResult);	
	$_SESSION['curuser'] = $row;
	if( isset($_POST['rem']) ){
		$token = hash('sha1', $regDetails['uname']);
		$Connection->Query("INSERT INTO devnet_tokens (`id`, `token`) VALUES ('".$row['id']."', '".$token."')");
		setcookie('devnet_user', $token, time()+60*60*24*999);
	}
	echo true;
}else{
	echo false;
}
	
$Connection->Close();
?>