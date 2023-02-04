<?php

/*
 * 
 * Uptime Mikrotik 
 * 
 */

require_once __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

use \RouterOS\Config;
use \RouterOS\Client;
use \RouterOS\Query;

// Create config object with parameters
$config =
    (new Config())
        ->set('host', '192.168.0.245')
        ->set('user', 'admin')
        ->set('pass', 'admin');

// Initiate client with config object
$client = new Client($config);

// Build query (Get resources in RouterOS)
$query = new Query("/system/resource/print");

// Send query to RouterOS
$request = $client->query($query);

// Read answer from RouterOS
$response = $client->read();

// Show uptime active in RouterOS
echo 'Uptime: ' . json_encode($response[0]['uptime']);

?>
