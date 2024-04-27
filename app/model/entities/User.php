<?php

class User extends Entity {
  protected string $username;
  protected string $email;
  protected string $password;
  protected ?User $friend;

  public static array $protected = ['password'];

  public static array $foreigns = [
    'friend' => 'User'
  ];
}