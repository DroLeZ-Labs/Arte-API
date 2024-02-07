<?php

require_once "core/config.php";
require_once "config.php";

require LOCAL . '/vendor/autoload.php';

class Autoloader
{
  const ENTITIES_DIR = LOCAL . '/app/model/entities';
  const MAPPERS_DIR = LOCAL . '/app/model/mappers';
  const SERVICES_DIR = LOCAL . '/app/model/services';

  const SYSTEM_HELPERS_DIR = LOCAL . '/core/helpers';
  const USER_HELPERS_DIR = LOCAL . '/app/helpers';

  const SYSTEM_BASES_DIR = LOCAL . '/core/bases';
  const USER_BASES_DIR = LOCAL . '/app/bases';

  const SYSTEM_EXCEPTIONS_DIR = LOCAL . '/core/exceptions';
  const USER_EXCEPTIONS_DIR = LOCAL . '/app/exceptions';

  const USER_LIBS_DIR = LOCAL . '/app/lib';

  const DB_DIR = LOCAL . '/core/database';

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
    if (isset($this->dynamic_dependencies[$classname]))
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
  public function push_dependencies(string $plugin, array $dependencies)
  {
    foreach ($dependencies as $dependency => $location)
      $this->dynamic_dependencies[$dependency] = LOCAL . "/plugins/$plugin/src/$location";
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
$plugins = scandir(LOCAL . '/plugins');
foreach ($plugins as $plugin)
  if ($plugin != '..' && $plugin != '.' && file_exists(LOCAL . "/plugins/$plugin/autoload.php"))
    require_once LOCAL . "/plugins/$plugin/autoload.php";
