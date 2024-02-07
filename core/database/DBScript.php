<?php

/**
 * This file needs some work to work well with both mysql and sqlite
 */

require __DIR__ . "/../../autoload.php";

require __DIR__ . "/../../database.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = DB::getDB();

// Dropping
if (isset($_GET['drop'])) {
  echo "<br>Dropping Tables...<br>";
  if ($_GET['drop'] == 'all')
    $tables = array_keys($creations);
  else
    $tables = explode(',', $_GET['drop']);

  $successes = 0;
  $failures = 0;
  foreach ($tables as $table) {
    if ($db->dropTable($table))
      $successes++;
    else
      $failures++;
  }
  echo "<br>$successes Successes and $failures Failures<br>";
}

// Creating
echo "<br>Creating Tables...<br>";

$successes = 0;
$failures = 0;
foreach ($creations as $tablename => $columns) {
  if ($db->createTable($tablename, $columns))
    $successes++;
  else
    $failures++;
}

echo "<br>$successes Successes and $failures Failures<br>";

// Inserting
echo "<br>Checking and Applying Insertions... <br>";

$successes = 0;
$failures = 0;
foreach ($insertions as $table => $table_insertions) {
  foreach ($table_insertions as $insertion) {
    if (count($db->select('*', $table, $insertion))) {
      $successes++;
      continue;
    }
    
    if($db->insert($table, $insertion))
      $successes++;
    else
      $failures++;
  }
}

echo "$successes Successes and $failures Failures<br>";