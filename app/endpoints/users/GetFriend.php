<?php

class GetFriend extends Endpoint
{
  public function __construct()
  {
    $this->init([
      'user_id' => [true, Regex::ID]
    ], $_GET);
  }

  public function handle(): Response
  {
    if(!$user = UserMapper::get($this->request['user_id']))
      return new Response("User Not Found", 404);

    return new Response($user->get('friend'));
  }
}