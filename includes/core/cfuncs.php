<?php
/**********************************
	Copyright: Aaron Skinner 2013;
**********************************/

if( !defined('CURSI') ){
	die('Un-defined var.. exiting.');
}
include_once(__ROOT__."/includes/config.php");
include_once(__ROOT__."/includes/core/connect.php");
$Connection = new Connection($GDATA);

function getPostDataSafe($pst = array(), $specialchars = false){
	$retPost = array();
	foreach($pst as $key=>$var){
		$retPost[$key] = mysql_real_escape_string($var);
	}
	if( $specialchars ){
		foreach($retPost as $key=>$var){
			$retPost[$key] = htmlspecialchars($var);
		}
	}
	return $retPost;
}

//Get's the database table name, returns users table if no argument is given.
function getTable($tbl = "users"){
	global $GDATA;
	return $GDATA["tables"][$tbl];
}

function getTokenLogin(){
	if( isset($_COOKIE['devnet_user']) ){
		global $Connection;
		$Connection->Connect();
		$TokenCookie = $Connection->Query("SELECT * FROM devnet_tokens WHERE token='".$_COOKIE['devnet_user']."'");
		$rowID = mysql_fetch_assoc($TokenCookie);
		if($rowID['id']){
			$Details = $Connection->Query("SELECT * FROM devnet_users WHERE id='".$rowID['id']."'");
			$row = mysql_fetch_assoc($Details);
			$_SESSION['curuser'] = $row;
		}
		$Connection->Close();
	}
}

function devnetIsLogged(){
	return isset($_SESSION['curuser']);
}
		

//Pulla the requested page from the URL
function getPage(){
	$action = strtolower($_GET['p']);
	if( $action ) {
		$action .= '.php';
		if( file_exists(__ROOT__.'/includes/core/pages/'.$action) ) {
			include(__ROOT__.'/includes/core/pages/'.$action);
		} else {
			include(__ROOT__.'/includes/core/pages/home.php');
		}
	} else {
		include(__ROOT__.'/includes/core/pages/home.php');
	}
}

function breakPosterDetails($dets){
	global $Connection;

	$UserArray = array();
	$Details = explode(";", $dets);
	$userQuery = $Connection->Query("SELECT * FROM devnet_users WHERE id='".$Details[1]."'");
	if( $userQuery ){
		$UserArray['user'] = mysql_fetch_assoc($userQuery);
	}
	$UserArray['timestamp'] = date("jS M h:ia", $Details[2]);
	return $UserArray;

}



function loginBox(){
	echo "<form id='login_user' action='login.php' onsubmit='return;' method='post'>
			<table>
				<tr align='left'>
					<td style='font: 80% \"Arial\"; color:#DBDBDB;'><b>Username:</b></td>
					<td><input style='width:100%;' type='text' id='lguname' name='lguname' /></td>
				</tr>
				<tr align='left'>
					<td style='font:80% \"Arial\"; color:#DBDBDB;'><b>Password:</b></td>
					<td><input style='width:100%;' type='password' id='lgpass' name='lgpass' /></td>
				</tr>
				<tr align='left'>
					<td style='font:10px \"Arial\"; color:#DBDBDB;''>Remember</td>
					<td><input type='checkbox' name='rem' value='1' class='selection' /><a class='defaultButton' onclick='devnetLogin(); return;'>Login</a> <a class='defaultButton' href='index.php?p=register'>Register</a></td>
				</tr>
			</table>
		</form>";
}

function implodeAssoc($glue,$arr) 
{ 
   $keys=array_keys($arr); 
   $values=array_values($arr); 

   return(implode($glue,$keys).$glue.implode($glue,$values)); 
}; 

function explodeAssoc($glue,$str) 
{ 
   $arr=explode($glue,$str); 

   $size=count($arr); 

   for ($i=0; $i < $size/2; $i++) 
       $out[$arr[$i]]=$arr[$i+($size/2)]; 

   return($out); 
};
		


?>