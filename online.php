<?php
/**********************************
	Copyright: Aaron Skinner 2013;
**********************************/
include_once('includes/config.php');
include_once('includes/core/connect.php');

session_start();

if( isset($_SESSION['curuser']) ){
	$userID = $_SESSION['curuser']['id'];
	$uname = $_SESSION['curuser']['uname'];
	$avatar = $_SESSION['curuser']['avatar'];
	$time = time()+110;

	$Conn = new Connection($GDATA);
	$Conn->Connect();

	$queryRes = $Conn->Query("INSERT INTO devnet_online (`uid`, `uname`, `avatar`, `expires`) VALUES ('$userID', '$uname', '$avatar', '$time')");
	if( $queryRes ){
		$Conn->Query("DELETE FROM devnet_online WHERE expires < " . time());
	}
	$Conn->Close();
}

