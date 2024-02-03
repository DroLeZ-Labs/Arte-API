<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Authenticator
{
  public static function encode($data)
  {
    return JWT::encode($data, SECRET, 'HS256');
  }

  public static function decode(string $cypher)
  {
    try {
      return JWT::decode($cypher, new Key(SECRET, 'HS256'));
    } catch (DomainException $e) {
      return false;
    }
  }
}