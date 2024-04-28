<?php

class Connection extends Entity {
  protected string $user_id;
  protected string $room_id;
  
  public static array $foreigns = [
    'friend' => 'User'
  ];
}