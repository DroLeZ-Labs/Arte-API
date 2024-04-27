<?php

// Locations
const APP_DIR = __DIR__ . '/app';
const CORE_DIR = __DIR__ . '/core';
const ROOT_DIR = __DIR__;

const ENDPOINTS_DIR =  APP_DIR . "/endpoints";
const ROUTES_JSON = ROOT_DIR . "/routes.json";

const PLUGINS_DIR = ROOT_DIR . "/plugins";
const LOG_DIR =  ROOT_DIR . "/logs";
const MEDIA_DIR =  ROOT_DIR . "/media";

require_once ROOT_DIR . "/config.php";

require_once CORE_DIR . "/init.php";
require_once CORE_DIR . "/proxy/Logger.php";
require_once CORE_DIR . "/proxy/Response.php";

require_once ROOT_DIR . '/vendor/autoload.php';
require_once CORE_DIR . '/proxy/Router.php';
require_once CORE_DIR . "/functions.php";


class ArteAutoloader
{
  const ENTITIES_DIR = APP_DIR . '/model/entities';
  const MAPPERS_DIR = APP_DIR . '/model/mappers';
  const SERVICES_DIR = APP_DIR . '/model/services';

  const SYSTEM_HELPERS_DIR = CORE_DIR . '/helpers';
  const USER_HELPERS_DIR = APP_DIR . '/helpers';

  const SYSTEM_BASES_DIR = CORE_DIR . '/bases';
  const USER_BASES_DIR = APP_DIR . '/bases';

  const SYSTEM_EXCEPTIONS_DIR = CORE_DIR . '/exceptions';
  const USER_EXCEPTIONS_DIR = APP_DIR . '/exceptions';

  const USER_LIBS_DIR = APP_DIR . '/lib';

  const DB_CORE = CORE_DIR . '/database';

  private static ArteAutoloader $inst;
  private array $dynamic_dependencies = [];

  public static function getLoader(): ArteAutoloader
  {
    if (!isset(self::$inst))
      self::$inst = new ArteAutoloader;

    return self::$inst;
  }

  public function __construct()
  {
  }

  public function load(string $classname)
  {
    if (isset($this->dynamic_dependencies[$classname]) && file_exists($this->dynamic_dependencies[$classname]))
      require $this->dynamic_dependencies[$classname];

    else if (file_exists(self::DB_CORE . "/$classname.php"))
      require self::DB_CORE . "/$classname.php";

    else if (file_exists(self::MAPPERS_DIR . "/$classname.php"))
      require self::MAPPERS_DIR . "/$classname.php";
    else if (file_exists(self::ENTITIES_DIR . "/$classname.php"))
      require self::ENTITIES_DIR . "/$classname.php";
    else if (file_exists(self::SERVICES_DIR . "/$classname.php"))
      require self::SERVICES_DIR . "/$classname.php";

    else if (file_exists(self::USER_HELPERS_DIR . "/$classname.php"))
      require self::USER_HELPERS_DIR . "/$classname.php";
    else if (file_exists(self::SYSTEM_HELPERS_DIR . "/$classname.php"))
      require self::SYSTEM_HELPERS_DIR . "/$classname.php";

    else if (file_exists(self::USER_BASES_DIR . "/$classname.php"))
      require self::USER_BASES_DIR . "/$classname.php";
    else if (file_exists(self::SYSTEM_BASES_DIR . "/$classname.php"))
      require self::SYSTEM_BASES_DIR . "/$classname.php";

    else if (file_exists(self::USER_EXCEPTIONS_DIR . "/$classname.php"))
      require self::USER_EXCEPTIONS_DIR . "/$classname.php";
    else if (file_exists(self::SYSTEM_EXCEPTIONS_DIR . "/$classname.php"))
      require self::SYSTEM_EXCEPTIONS_DIR . "/$classname.php";

    else if (file_exists(self::USER_LIBS_DIR . "/$classname.php"))
      require self::USER_EXCEPTIONS_DIR . "/$classname.php";
  }

  /**
   * @param array associative array associates every class with its definition location
   */
  public function push_dependencies(array $dependencies, string $base = ROOT_DIR)
  {
    foreach ($dependencies as $dependency => $location)
      $this->dynamic_dependencies[$dependency] = "$base/$location";
  }
}