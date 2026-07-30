<?php

header('Content-Type: application/json');
require ('api.php');

if(!empty($_POST)){
    $api = new Portal();

    if(isset($_REQUEST['is_live']) && $_REQUEST['is_live']){
        $api_domain = 'https://portal.seasonmarketing.co.uk';
    }else{
        $api_domain = 'http://dev.portal.seasonmarketing.co.uk';
    }

    $url = '/api/user/create-affiliates';

    echo $api->requestApi($api_domain . $url, $_POST);


    exit();

}else{
    echo json_encode(array(
        "success" => false,
        "message" => "Method not allow",
        "price"   => 0
    ));
    exit();
}
