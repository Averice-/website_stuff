<?php
/**********************************
	Copyright: Aaron Skinner 2013;
**********************************/
include_once(__ROOT__."/includes/core/cfuncs.php");

$Conn = $Connection;

function userGetGroupArray($user){
	if( isset($user['groups']) ){
		return explode(";", $user['groups']);
	}
	return array();
}

function userIsGroup($user, $groupID){
	if( isset($user['groups']) ){
		$groupArray = explode(";", $user['groups']);
		return in_array($groupID, $groupArray);
	}
	return false;
}

function userIsRoot($user){
	return userIsGroup($user, "9001");
}

function userIsBanned($user){
	return $user['bantime'] > time();
}

function userGetBanExpiry($user, $format = "D, d M Y H:i"){
	return date($format, $user['bantime']);
}

function userRequiresApproval($user){
	return $user['approved'] == 1;
}

function userPostCount($user){
	return $user['postcount'];
}

function userAddGroup($user, $groupID){
	global $Conn;
	if( !userIsGroup($user, $groupID) ){
		$grpArray = userGetGroupArray($user);
		array_push($grpArray, $groupID);
		$user['groups'] = implode(";", $grpArray);
		$Conn->Connect();
		$Conn->Query("UPDATE devnet_users WHERE id = '".$user['id']."' SET groups='".$user['groups']."'");
		$Conn->Close();
		return true;
	}
	return false;
}

function userIsLogged(){
	return isset($_SESSION['curuser']);
}

function userSetMainGroup($user, $groupID){
	global $Conn;
	if( userIsGroup($user, $groupID) ){
		$user['maingroup'] = $groupID;
		$Conn->Connect();
		$Conn->Query("UPDATE devnet_users WHERE id = '".$user['id']."' SET maingroup='$groupID'");
		$Conn->Close();
	}
}

function userRemoveGroup($user, $groupID){
	global $Conn;
	if( userIsGroup($user, $groupID) ){
		$grpArray = userGetGroupArray($user);
		for( $i = 0; i < count($grpArray); $i++ ){
			if( $grpArray[$i] == $groupID ){
				array_splice($grpArray, $i, 1);
			}
		}
		$user['groups'] = implode(";", $grpArray);
		$Conn->Connect();
		$Conn->Query("UPDATE devnet_users WHERE id = '".$user['id']."' SET groups='".$user['groups']."'");
		$Conn->Close();
	}
}
		
		
	
?>