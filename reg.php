<?php
/**********************************
	Copyright: Aaron Skinner 2013;
**********************************/

include_once('includes/config.php');
include_once('includes/core/connect.php');
if( !$_POST['unamer'] ){
	die("Unidentified file entry, connection removed.");
}
$Connection = new Connection($GDATA);
$Connection->Connect();

// Registration here:
$regDetails = array();
$regDetails['uname'] = mysql_real_escape_string(htmlspecialchars($_POST['unamer']));
$regDetails['password'] = hash('sha256', $_POST['passr']);
$regDetails['email'] = mysql_real_escape_string(htmlspecialchars($_POST['emailr']));
$regDetails['dob'] = $_POST['dob1']."/".$_POST['dob2']."/".$_POST['dob3'];
$regDetails['gender'] = $_POST['sex'];
$regDetails['country'] = $_POST['country'];
$regDetails['reff'] = $_POST['reff'];
$regDetails['joindate'] = date('j F Y');

$val_link = hash('md5', $regDetails['uname'] . $regDetails['email']);

$queryResult = $Connection->Query("INSERT INTO devnet_users (`uname`, `password`, `email`, `dob`, `gender`, `country`, `reff`, `joindate`, `maingroup`, `groups`, `credit`, `regid`) VALUES
									('".$regDetails['uname']."', '".$regDetails['password']."', '".$regDetails['email']."', '".$regDetails['dob']."', '".$regDetails['gender']."', '".$regDetails['country']."',
									'".$regDetails['reff']."', '".$regDetails['joindate']."', '1', '1', '10', '$val_link')");

if( $queryResult ){
	$MAIL_TO = $regDetails['email'];
	$MAIL_SUBJECT = 'Devnet Registration';
	$MAIL_BODY = 'Thank you for registering at Devnet.com' . PHP_EOL . PHP_EOL .
	'An Administrator has required E-mail Validation for new members, to validate your e-mail please follow this link: ' . PHP_EOL .
	'http://shard-engine.com/cursi/validate.php?id=' . $val_link . PHP_EOL . PHP_EOL .
	'Thank you for joining our community!';
	$MAIL_HEADERS = 'From: Devnet.com <noreply@devnet.com>';

	mail($MAIL_TO, $MAIL_SUBJECT, $MAIL_BODY, $MAIL_HEADERS);
	echo true;
}else{
	echo false;
}
$Connection->Close();
?>