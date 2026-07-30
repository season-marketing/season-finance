<?php

require ('api.php');

if(isset($_GET['code'])){

        $api = new Portal();

        $_data =  ['code'=>$_GET['code'], 'user_agent' => $_SERVER['HTTP_USER_AGENT'], 'ip' => $api->get_ip_address()];

        if(isset($_REQUEST['is_live']) && !$_REQUEST['is_live']){
            $api_domain = 'http://dev.portal.seasonmarketing.co.uk';
        }else{
            $api_domain = 'https://portal.seasonmarketing.co.uk';
        }
        $response = $api->requestApi($api_domain . '/api/api/verify-app', $_data);

        $response = json_decode($response,true);

        if(!empty($response['redirect_url'])){
            header('Location: ' . $response['redirect_url']);
            exit;
        }

}

 header('Location: https://rightpaydays.com'); 
 exit;
