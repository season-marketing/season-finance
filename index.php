<?php 
require 'vendor/autoload.php';

class Index {
    function __construct()
    {
        return new LandingPage\Blade\View('index', ['hey' => 'hey']);
    }
}

new Index();