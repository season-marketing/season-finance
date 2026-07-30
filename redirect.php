<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require ('api.php');

if(isset($_GET['code'])){

        $api = new Portal();

        $_data =  ['code'=>$_GET['code'], 'user_agent' => $_SERVER['HTTP_USER_AGENT'], 'ip' => $api->get_ip_address()];

        if(isset($_REQUEST['is_live']) && !$_REQUEST['is_live']){
            $api_domain = 'http://dev.portal.seasonmarketing.co.uk';
        }else{
            $api_domain = 'https://portal.seasonmarketing.co.uk';

        }

        $response = $api->requestApi($api_domain . '/api/api/verify-lead', $_data);


        $response = json_decode($response,true);

        if(!empty($response['data'])){
            $data = $response['data'];
            if(!empty($data['redirect_url'])){
                header('Location: ' . $data['redirect_url']);
                exit;
            }
        }

}

 header('Location: https://rightpaydays.com'); 
 exit;
