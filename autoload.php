<?php

require_once __DIR__ . "/core/config.php";
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/core/proxy/Logger.php";
require_once __DIR__ . "/core/proxy/Response.php";

require __DIR__ . '/vendor/autoload.php';

class Autoloader
{
  const ENTITIES_DIR = __DIR__ . '/app/model/entities';
  const MAPPERS_DIR = __DIR__ . '/app/model/mappers';
  const SERVICES_DIR = __DIR__ . '/app/model/services';

  const SYSTEM_HELPERS_DIR = __DIR__ . '/core/helpers';
  const USER_HELPERS_DIR = __DIR__ . '/app/helpers';

  const SYSTEM_BASES_DIR = __DIR__ . '/core/bases';
  const USER_BASES_DIR = __DIR__ . '/app/bases';

  const SYSTEM_EXCEPTIONS_DIR = __DIR__ . '/core/exceptions';
  const USER_EXCEPTIONS_DIR = __DIR__ . '/app/exceptions';

  const USER_LIBS_DIR = __DIR__ . '/app/lib';

  const DB_DIR = __DIR__ . '/core/database';

  private static Autoloader $inst;
  private array $dynamic_dependencies = [];

  public static function getLoader(): Autoloader
  {
    if (!isset(self::$inst))
      self::$inst = new Autoloader;

    return self::$inst;
  }

  public function __construct()
  {
  }

  public function load(string $classname)
  {
    if (isset($this->dynamic_dependencies[$classname]) && file_exists($this->dynamic_dependencies[$classname]))
      require $this->dynamic_dependencies[$classname];

    else if (file_exists(self::DB_DIR . "/$classname.php"))
      require self::DB_DIR . "/$classname.php";

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
  public function push_dependencies(array $dependencies, string $base = __DIR__)
  {
    foreach ($dependencies as $dependency => $location)
      $this->dynamic_dependencies[$dependency] = "$base/$location";
  }
}

/**
 * Arte Default Autoloader
 */
$autoloader = Autoloader::getLoader();
spl_autoload_register(function ($classname) {
  global $autoloader;
  $autoloader->load($classname);
});

/**
 * Arte Core Routes
 */
require_once __DIR__ . '/core/proxy/Router.php';
Router::getInst()->addRoutes([
  'arte/installPlugin' => __DIR__ . '/core/API/endpoints/InstallPlugin.php',
  'arte/uninstallPlugin' => __DIR__ . '/core/API/endpoints/UninstallPlugin.php',
  'arte/reinstallPlugin' => __DIR__ . '/core/API/endpoints/ReinstallPlugin.php'
]);

/**
 * Loading Plugin Dependencies
 */
$plugins = scandir(__DIR__ . '/plugins');
foreach ($plugins as $plugin)
  if ($plugin != '..' && $plugin != '.' && file_exists(__DIR__ . "/plugins/$plugin/autoload.php"))
    require_once __DIR__ . "/plugins/$plugin/autoload.php";
