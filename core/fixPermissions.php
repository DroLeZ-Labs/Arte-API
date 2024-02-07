<?php

const LOCAL = __DIR__ . '/..';

require __DIR__ . '/functions.php';
chmodRecursive(LOCAL . '/plguins', 0777);
chmodRecursive(LOCAL . '/media', 0777);
chmod(LOCAL . '/composer.json', 0777);
chmod(LOCAL . '/composer.lock', 0777);
chmodRecursive(LOCAL . '/vendor', 0777);
