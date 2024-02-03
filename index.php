<?php

/** This file needs major update */

ob_start();

require "core/config.php";
require "config.php";
require "autoload.php";

require "core/proxy/Logger.php";
require "core/proxy/Router.php";
require "core/proxy/Response.php";

// Initialize the logger
$logger = Logger::getLogger();

$router = new Router();
$route = $router->route();

if (!isset($route->endpoint))
  $response = new Response("Endpoint Not Found", 404);
else {
  try {
    if ($route->test && $route->endpoint instanceof Testable)
      $response = $route->endpoint->test();
    else
      $response = $route->endpoint->run();
  } catch (Exception $e) {
    $code = is_integer($e->getCode()) ? $e->getCode() : 500;
    $response = new Response(trace($e), $code);
  }
}

// Output the response
$response->echo();

// Perform post-handle actions on the endpoint
PostHandle::run();

// Log the request and response
$logger->log($route, $response);
