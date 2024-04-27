<?php

class ConnectionMapper extends Mapper {
  public static string $table = 'connections';
  public static array $required = ["user_id", "room_id"];
}