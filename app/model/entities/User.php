<?php

class User extends Entity {
  protected string $username;
  protected string $email;
  protected string $password;
  protected ?User $friend;

  protected static array $foreigns = [
    'friend' => 'User'
  ];
}