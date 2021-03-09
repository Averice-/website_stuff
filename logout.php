<?php
/**********************************
	Copyright: Aaron Skinner 2013;
**********************************/

session_start();

if( isset($_SESSION['curuser']) ){
	session_unset();
	if( isset($_COOKIE['devnet_user']) ){
	
		include_once('includes/config.php');
		include_once('includes/core/connect.php');

		$Connection = new Connection($GDATA);
		$Connection->Connect();
		
		$id = $_COOKIE['devnet_user'];
		$Connection->Query("DELETE FROM devnet_tokens WHERE token='$id'");
		$Connection->Close();
		
		setcookie("devnet_user","",time()-10);
	}
}
?>