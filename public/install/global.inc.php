<?php  
/*
	Global.inc
*/
if (file_exists('../conf.txt')) {
 
	header("Location: ../", true, 302); 
}else{
	header("Location: installation", true, 302);
	exit();
}
// date_default_timezone_set(TIMEZONE);
if (FORCE_HTTPS == 1) {
	if($_SERVER["HTTPS"] != "on")
	{
	    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
	    exit();
	}
}

//classes needed
require_once 'classes/DB.class.php';
require_once 'classes/User.class.php';  
require_once 'classes/UserTools.class.php';
require_once 'classes/functions.class.php';  
require_once 'custom/custom.class.php';  


session_start(); 

//get language array
// $lan_sele = LANGUAGE;
include("language/en.php");


//connect to the database  
// $db = new DB();  
// $db->connect(); 

//mysql_query("SET NAMES 'utf8'"); 
  
//initialize UserTools object  
$userTools = new UserTools();  

//initialize Functions object  
$functions = new Functions();  

//initialize Custom Functions object  
$customfunc = new CustomFunctions(); 


?>