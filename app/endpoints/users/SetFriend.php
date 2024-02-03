<?php

class SetFriend extends Endpoint
{
  public function __construct()
  {
    $this->init([
      'user_id' => [true, Regex::ID],
      'friend_id' => [true, Regex::ID]
    ], $_POST);
  }

  public function handle(): Response
  {
    if (!$user = UserMapper::get($this->request['user_id']))
      return new Response('User Not Found', 404);

    if (!$friend = UserMapper::get($this->request['friend_id']))
      return new Response('Friend Not Found', 404);

    $friendship = new Friendship($user);
    
    if ($friendship->setFriend($friend))
      return new Response;
    else
      return new Response('', 500);
  }
}
