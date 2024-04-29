<?php

require __DIR__ . "/../../autoload.php";

echo Authenticator::encode(['root' => 'root']) . "\n";