<?php

ob_start();

require "autoload.php";

try {
  // Initialize the logger
  $logger = ArteLogger::getLogger();
} catch (PermissionDenied $e) {
  die('Fix project permissions');
}

$router = ArteRouter::getInst();
$route = $router->route();

if (!isset($route->endpoint))
  $response = new Response("Endpoint Not Found", 404);
else {
  try {
    if ($route->test && $route->endpoint instanceof Testable)
      $response = $route->endpoint->test();
    else
      $response = $route->endpoint->run();
  } catch (Exception | Error $e) {
    $code = is_integer($e->getCode()) ? $e->getCode() : 500;
    $response = new Response(trace($e), $code);
  }
}


$response->echo();

// Log the request and response
$logger->log($route, $response);
