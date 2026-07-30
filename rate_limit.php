<?php

// Define rate limit parameters
$rateLimit = 2; // 1200 requests
$timeWindow = 10; // 60 seconds

// Unique identifier for the client (e.g., IP address or API key)
$clientKey = $_SERVER['REMOTE_ADDR']; // Replace with a proper identifier for the client

// Use a caching mechanism
$cacheFile = sys_get_temp_dir() . "/rate_limit_$clientKey.json";

// Load the current rate limit data
$rateData = file_exists($cacheFile) ? json_decode(file_get_contents($cacheFile), true) : ['requests' => 0, 'timestamp' => time()];


//var_dump($rateData);
// Check the time window
$currentTime = time();
if ($currentTime - $rateData['timestamp'] > $timeWindow) {
    $rateData['requests'] = 0;
    $rateData['timestamp'] = $currentTime;
}

// Increment the request count
$rateData['requests']++;

// Enforce the rate limit
if ($rateData['requests'] > $rateLimit) {
    http_response_code(429);
    echo json_encode(['error' => 'Too many requests. Please try again later.']);
    die();
}

// Save the updated rate data
file_put_contents($cacheFile, json_encode($rateData));