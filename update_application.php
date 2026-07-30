<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require ('api.php');

if(isset($_REQUEST['ApiKey']) && isset($_REQUEST['ReferenceId'])){

        $api = new Portal();

        $_data =  ['api_key'=>$_REQUEST['ApiKey'], 'application_id' => $_REQUEST['ReferenceId'], 'commission' => isset($_REQUEST['Commission']) ? $_REQUEST['Commission'] : null,  'result' => isset($_REQUEST['Result']) ? $_REQUEST['Result'] : null, 'lead_id' => isset($_REQUEST['LeadId']) ? $_REQUEST['LeadId'] : null, 'request' => $_REQUEST];

        if(isset($_REQUEST['is_live']) && !$_REQUEST['is_live']){
            $api_domain = 'http://dev.portal.seasonmarketing.co.uk';
        }else{
            $api_domain = 'https://portal.seasonmarketing.co.uk';
        }

        echo $api->requestApi($api_domain . '/api/api/update-commission', $_data);

        exit();

}
