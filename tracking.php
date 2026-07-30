<?php

header('Content-Type: application/javascript');

require ('api.php');

$api = new Portal();

$domain = 'https://portal.seasonmarketing.co.uk';

$api->requestApi($domain . '/api/api/tracking-gambling-customers', $_GET);