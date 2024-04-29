<?php

require __DIR__ . "/../../autoload.php";

$temp_dir = CORE_DIR .'/scripts/temp';
mkdir($temp_dir, 0777);
$zip_path = __DIR__ . '/arte-core.zip';
unlink($zip_path);

copyDirectory(CORE_DIR, $temp_dir);
copy(ROOT_DIR . '/.htaccess', $temp_dir . '/.htaccess');
copy(ROOT_DIR . '/index.php', $temp_dir . '/index.php');
copy(ROOT_DIR . '/autoload.php', $temp_dir . '/autoload.php');

zipDirectory($temp_dir, $zip_path);
deleteDirectory($temp_dir);
echo "zip created at $zip_path";