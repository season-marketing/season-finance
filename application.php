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

    $url = '/api/api/add-app';

    if(isset($_POST['env'])){

        if($_POST['env'] == 'us'){
            $url = '/api/api/add-app-us';
        }
        if($_POST['env'] == 'au'){
            $url = '/api/api/add-app-au';
        }
        if($_POST['env'] == 'ca'){
            $url = '/api/api/add-app-ca';
        }
        if($_POST['env'] == 'us-ssn'){
            $url = '/api/api/add-app-us-by-ssn';
        }
    }

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
