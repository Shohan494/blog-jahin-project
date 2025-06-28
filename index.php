<?php
// Parse the requested path
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = basename($request_uri, '.php');

// Route to the RouteController
require_once 'controller/RouteController.php';
?>