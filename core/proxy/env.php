<?php

$content = file_get_contents(__DIR__ . '/../.env');
$lines = explode("\n", $content);
$env = [];
foreach ($lines as $line) {
    $line = trim($line);
    if ($line && strpos($line, '=') !== false) {
        list($key, $value) = explode('=', $line, 2);
        $env[$key] = $value;
    }
}

return $env;