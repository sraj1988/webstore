<?php

require_once 'config.php';
require_once 'database.php';
require_once 'core/response.php';
require_once 'core/restController.php';
require_once 'core/dispatch.php';


$username = (isset($_SERVER['HTTP_USERNAME'])) ? $_SERVER['HTTP_USERNAME'] : '';
$password = (isset($_SERVER['HTTP_PASSWORD'])) ? $_SERVER['HTTP_PASSWORD'] : '';

$dispatcher = new Dispatch($_SERVER['REQUEST_METHOD'], $_GET['dispatch'] , new Response(), $username, $password);
$data = $dispatcher->handle();
