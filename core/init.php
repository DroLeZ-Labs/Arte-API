<?php

$_ENV = require_once __DIR__ . "/proxy/env.php";

/**
 * Arte Default Autoloader
 */
$autoloader = ArteAutoloader::getLoader();
spl_autoload_register(function ($classname) {
  global $autoloader;
  $autoloader->load($classname);
});

$prod = $_ENV['PROD'] ?? false;
if (isset($_REQUEST['debug']) && !$prod)
  define('DEBUG', true);
else
  define('DEBUG', false);

if (in_array(TIMEZONE, DateTimeZone::listIdentifiers(DateTimeZone::ALL)))
  date_default_timezone_set(TIMEZONE);
else
  ArteLogger::getLogger()->reportError("Couldn't find timezone " . TIMEZONE . " among valid timezones");

/**
 * Loading Plugin Dependencies
 */
$plugins = scandir(__DIR__ . '/plugins');
foreach ($plugins as $plugin)
  if ($plugin != '..' && $plugin != '.' && file_exists(__DIR__ . "/plugins/$plugin/autoload.php"))
    require_once __DIR__ . "/plugins/$plugin/autoload.php";

/**
 * Routing to dev tools
 */
if (!$prod) {
  $requested = trim(explode(API_BASE, parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))[1], '/');
  if ($requested == 'db') {
    require CORE_DIR . "/database/phpliteadmin.php";
    exit;
  }
  if ($requested == 'db-script') {
    require CORE_DIR . "/database/DBScript.php";
    exit;
  }
  if ($requested == 'files') {
    require CORE_DIR . "/files.php";
    exit;
  }
  if ($requested == 'shell') {
    require CORE_DIR . "/shell.php";
    exit;
  }
}
