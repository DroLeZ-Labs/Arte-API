<?php

require LOCAL . '/vendor/autoload.php';

const ENTITIES_DIR = LOCAL . '/app/model/entities';
const MAPPERS_DIR = LOCAL . '/app/model/mappers';
const SERVICES_DIR = LOCAL . '/app/model/services';

// TODO: move system helpers to /core and define user helpers inside /app
const SYSTEM_HELPERS_DIR = LOCAL . '/core/helpers';
const USER_HELPERS_DIR = LOCAL . '/app/helpers';

const SYSTEM_BASES_DIR = LOCAL . '/core/bases';
const USER_BASES_DIR = LOCAL . '/app/bases';

const SYSTEM_EXCEPTIONS_DIR = LOCAL . '/core/exceptions';
const USER_EXCEPTIONS_DIR = LOCAL . '/app/exceptions';

const USER_LIBS_DIR = LOCAL . '/app/lib';

const DB_DIR = LOCAL . '/core/database';

/**
 * The autoload function is responsible for automatically loading classes when they are referenced.
 * It utilizes the predefined file paths for different class categories.
 *
 * @param string $class_name The name of the class to be loaded.
 * @throws Fail If the class is not found.
 */
spl_autoload_register(function ($class_name) {
  $temp = explode('\\', $class_name);
  $file_name = end($temp);

  if (file_exists(DB_DIR . "/$file_name.php"))
    require DB_DIR . "/$file_name.php";

  else if (file_exists(MAPPERS_DIR . "/$file_name.php"))
    require MAPPERS_DIR . "/$file_name.php";
  else if (file_exists(ENTITIES_DIR . "/$file_name.php"))
    require ENTITIES_DIR . "/$file_name.php";
  else if (file_exists(SERVICES_DIR . "/$file_name.php"))
    require SERVICES_DIR . "/$file_name.php";

  else if (file_exists(USER_HELPERS_DIR . "/$file_name.php"))
    require USER_HELPERS_DIR . "/$file_name.php";
  else if (file_exists(SYSTEM_HELPERS_DIR . "/$file_name.php"))
    require SYSTEM_HELPERS_DIR . "/$file_name.php";

  else if (file_exists(USER_BASES_DIR . "/$file_name.php"))
    require USER_BASES_DIR . "/$file_name.php";
  else if (file_exists(SYSTEM_BASES_DIR . "/$file_name.php"))
    require SYSTEM_BASES_DIR . "/$file_name.php";

  else if (file_exists(USER_EXCEPTIONS_DIR . "/$file_name.php"))
    require USER_EXCEPTIONS_DIR . "/$file_name.php";
  else if (file_exists(SYSTEM_EXCEPTIONS_DIR . "/$file_name.php"))
    require SYSTEM_EXCEPTIONS_DIR . "/$file_name.php";

  else if (file_exists(USER_LIBS_DIR . "/$file_name.php"))
    require USER_EXCEPTIONS_DIR . "/$file_name.php";
});
