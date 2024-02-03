<?php

class Delete extends Endpoint
{
  public function __construct()
  {
    $this->init([
      'user_id' => [true, Regex::ID]
    ], $_POST);
  }

  public function handle(): Response
  {
    if((new User($this->request['user_id']))->delete())
      return new Response;
    
    return new Response('', 500);
  }
}