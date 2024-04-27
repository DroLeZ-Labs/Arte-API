<?php

class GetUsers extends Endpoint
{
  public function __construct()
  {
    $this->init([
      'user_id' => [false, Regex::ID]
    ], $_GET);
  }

  public function handle(): Response
  {
    $mapper = new CustomMapper("UserMapper");
    $mapper->join("ConnectionMapper");
    $mapper->join("RoomMapper");

    if (isset($this->request['user_id']))
      $result = $mapper->get(['users.user_id' => $this->request['user_id']]);
    else
      $result = $mapper->getAll();
    return new Response($result);
  }
}
