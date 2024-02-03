<?php

$creations = [
  'users' => [
    'id INT AUTO_INCREMENT PRIMARY KEY',
    'username VARCHAR(255) UNIQUE NOT NULL',
    'password VARCHAR(255) NOT NULL',
    'email VARCHAR(255) NOT NULL',
    'friend INT NULL',
    'FOREIGN KEY (friend) REFERENCES users(id)'
  ]
];


/**
 * @var array<string,array>
 */
$insertions = [];
