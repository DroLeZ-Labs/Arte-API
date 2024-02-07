<?php

header('Access-Control-Allow-Origin: *');

ini_set("precision", 3);

// Locations
const ENDPOINTS_DIR =  __DIR__ . "/../app/endpoints";
const ROUTES_JSON = __DIR__ . "/../routes.json";

const PLUGINS_DIR = __DIR__ . "/../plugins";
const LOG_DIR =  __DIR__ . "/../logs";
const MEDIA_DIR =  __DIR__ . "/../media";

if (isset($_REQUEST['debug']))
  define('DEBUG', true);
else
  define('DEBUG', false);

const MYSQL = 1;
const SQLITE = 2;

if (!function_exists('str_contains')) {
  function str_contains(string $haystack, string $needle): bool
  {
    return '' === $needle || false !== strpos($haystack, $needle);
  }
}

require __DIR__ . "/functions.php";