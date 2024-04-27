<?php

class info extends Endpoint
{
  public function __construct()
  {
    $this->init([
      'room_id' => [true, Regex::ID]
    ], $_GET);
  }

  public function handle(): Response
  {
    return new Response(RoomMapper::get($this->request['room_id']));
  }
}