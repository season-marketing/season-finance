<?php

header('Content-Type: application/json');
require ('api.php');

ini_set('max_execution_time', 0);
ini_set('request_terminate_timeout', 600);

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (empty($_POST) && empty($data)) {
    echo json_encode([
        "success" => false,
        "message" => "No POST data received",
        "price" => 0
    ]);
    exit();
}

// If JSON data exists, use it
if (!empty($data)) {
    $_POST = $data;
}


if(!empty($_POST)){
    $api = new Portal();

    if(isset($_REQUEST['is_live']) && !$_REQUEST['is_live']){
        $api_domain = 'http://dev.portal.seasonmarketing.co.uk';
    }else{
        $api_domain = 'http://35.177.229.97';
    }

    $response =  $api->requestApi($api_domain . '/api/api/lead', $_POST);

    echo $response;

    exit();

}else{
    echo json_encode(array(
        "success" => false,
        "message" => "Method not allow",
        "price"   => 0
    ));
    exit();
}
