<?php
/**********************************
	Copyright: Aaron Skinner 2013;
**********************************/

class Connection {

	protected $dbInfo = array();
	protected $connected, $query, $database;
	
	function __construct(array $cinfo){
		$this->dbInfo["host"] = $cinfo["host"];
		$this->dbInfo["user"] = $cinfo["user"];
		$this->dbInfo["data"] = $cinfo["data"];
		$this->dbInfo["pass"] = $cinfo["pass"];
	}
	
	function Connect($no_err = false) {
		$this->connected = new mysqli($this->dbInfo['host'], $this->dbInfo['user'], $this->dbInfo['pass'], $this->dbInfo['data']);
		if( mysqli_connect_errno() ) {
			die('Mysql connection failed -> '.mysql_error());
		}
	}
	
	function Query($query_str, $arrArgs = array()) {
		if( !empty($query_str) ) {
			$query = $this->connected->prepare($query_str);
			if( $arrArgs[0] ){
				call_user_func_array(array($query, 'bind_param'), $arrArgs);
			}
			$query->execute();
			$result = $query->get_result();
			if( $result ){
				return $result;
			}
		}
	}
	
	function Close() {
		$this->connected->close();
	}
	
};
?>