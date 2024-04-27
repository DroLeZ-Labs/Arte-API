<?php

class Delete extends Endpoint
{
  public function __construct()
  {
    $this->init([
      'room_id' => [true, Regex::ID]
    ], $_POST);
  }

  public function handle(): Response
  {
    if((new Room($this->request['room_id']))->delete())
      return new Response;
    
    return new Response('', 500);
  }
}