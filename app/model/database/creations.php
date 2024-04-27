<?php

return [
  'users' => [
    'id INT AUTO_INCREMENT PRIMARY KEY',
    'username VARCHAR(255) UNIQUE NOT NULL',
    'password VARCHAR(255) NOT NULL',
    'email VARCHAR(255) NOT NULL',
    'friend INT NULL',
    'FOREIGN KEY (friend) REFERENCES users(id)'
  ],
  'rooms' => [
    'id INT AUTO_INCREMENT PRIMARY KEY',
    'name VARCHAR(255) NOT NULL',
  ],
  'connections' => [
    'id INT AUTO_INCREMENT PRIMARY KEY',
    'user_id INT NOT NULL',
    'room_id INT NOT NULL',
    'FOREIGN KEY (user_id) REFERENCES users(id)',
    'FOREIGN KEY (room_id) REFERENCES rooms(id)'
  ]
];
