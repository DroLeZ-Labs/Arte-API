<?php

class UserMapper extends Mapper {
  public static string $table = 'users';
  public static array $required = ["username", "password", "email"];
  public static array $optional = ["friend"];
}