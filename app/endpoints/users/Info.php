<?php

class info extends Endpoint
{
  public function __construct()
  {
    $this->init([
      'user_id' => [true, Regex::ID]
    ], $_GET);
  }

  public function handle(): Response
  {
    return new Response(UserMapper::get($this->request['user_id']));
  }
}