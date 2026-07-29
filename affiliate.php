<?php

header('Content-Type: application/json');
require ('api.php');

require ('rate_limit.php');

if(!empty($_GET)){
    $api = new Portal();

    if(isset($_REQUEST['is_live']) && $_REQUEST['is_live']){
        $api_domain = 'https://portal.seasonmarketing.co.uk';
    }else{
        $api_domain = 'http://dev.portal.seasonmarketing.co.uk';
    }

    $url = '/api/affiliates/lead-data';

    echo $api->requestApi($api_domain . $url, $_GET);


    exit();

}else{
    echo json_encode(array(
        "success" => false,
        "message" => "Method not allow!",
    ));
    exit();
}
