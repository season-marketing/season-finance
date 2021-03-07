<?php 
require 'vendor/autoload.php';

class Index {
    function __construct()
    {
        return new landingPage\Blade\View('index', ['hey' => 'hey']);
    }
}

new Index();