<?php

header('Access-Control-Allow-Origin: *');

ini_set("precision", 3);

// Locations
const LOCAL = '.';

const ENDPOINTS_DIR =  LOCAL . "/app/endpoints";
const ROUTES_JSON = LOCAL . '/routes.json';

const PLUGINS_DIR = LOCAL . '/plugins';
const LOG_DIR =  LOCAL . "/logs";
const MEDIA_DIR =  LOCAL . "/media";

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

require LOCAL . '/core/functions.php';