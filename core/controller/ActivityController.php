<?php 

require("../entity/Ret.class.php");
require("../busi/ActivityBo.class.php");
include("../libs/log4php/Logger.php");

header('Content-Type:text/html;charset=utf-8'); 

$response = "";
$logger = Logger::getLogger("main");
switch ($_GET['action']) { 
    case "newActivity":
		$bo = new ActivityBo(); 
        $response = $bo->createActivity($_GET['theme'],$_GET['activity_time'],$_GET['deadline_time'],$_GET['location'],$_GET['orgnizer'],$_GET['detail'],$_GET['phone']);
    break; 
	case "queryAll":
		$bo = new ActivityBo(); 
	    $response =  $bo->queryAllActivity($_GET['weixin_id']);
    break;
	case "queryDetail":
		$bo = new ActivityBo(); 
		$response =  $bo->queryDetail($_GET['aid']);
    break;
	case "signup":
		$bo = new ActivityBo(); 
		$response =  $bo->signup($_GET['aid'],$_GET['pid'],$_GET['name'],$_GET['phone'],$_GET['sex']);
	break;
	case "checkActivity":
		$bo = new ActivityBo(); 
		$response =  $bo->checkActivity($_GET['theme'],$_GET['activity_time'],$_GET['location'],$_GET['orgnizer']);
	break;
	case "cancelActivity":
		$bo = new ActivityBo(); 
		$response =  $bo->cancelActivity($_GET['aid']);
	break;
	case "quitActivity":
		$bo = new ActivityBo(); 
		$response =  $bo->quitActivity($_GET['aid'],$_GET['pid']);
	break;
	case "queryPersonPhotos":
		$bo = new ActivityBo(); 
		$response =  $bo->queryPersonPhotos($_GET['aid'],$_GET['pid']);
	break;
	case "deletePhoto":
	$bo = new ActivityBo(); 
		$response =  $bo->deletePhoto($_GET['path']);
	break;
    case "queryParticipants":
    $bo = new ActivityBo(); 
	$response =  $bo->queryParticipants($_GET['aid']);
	break;
	case "queryActivity":
    $bo = new ActivityBo(); 
	$response =  $bo->queryActivity($_GET['aid']);
	break;
	case "updateActivity":
    $bo = new ActivityBo(); 
	$response = $bo->updateActivity($_GET['theme'],$_GET['activity_time'],$_GET['deadline_time'],$_GET['location'],$_GET['detail'],$_GET['phone'],$_GET['aid']);
	break;
    default:         
		$response = json_encode(new Ret());
    break; 
} 
//$logger->info($response);
echo $response;

?>