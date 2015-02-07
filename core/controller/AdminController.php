<?php 

require("../entity/Ret.class.php");
require("../busi/AdminBo.class.php");
include("../libs/log4php/Logger.php");

header('Content-Type:text/html;charset=utf-8'); 

$response = "";
$logger = Logger::getLogger("main");
switch ($_GET['action']) { 
    case "login":
		$bo = new AdminBo(); 
        $response = $bo->login($_GET['username'],$_GET['password']);
    break; 
	case "addAdmin":
		$bo = new AdminBo(); 
        $response = $bo->add_admin($_GET['username'],$_GET['password']);
    break; 
	case "isLogin":
		$bo = new AdminBo(); 
        $response = $bo->is_login();
    break; 
	case "logout":
		$bo = new AdminBo(); 
        $response = $bo->logout();
    break; 
	
    default:         
		$response = json_encode(new Ret());
    break; 
} 
//$logger->info($response);
echo $response;

?>