<?php

class Create extends Endpoint
{
  public function __construct()
  {
    $this->init([
      'username' => [true, Regex::LOGIN],
      'password' => [true, Regex::LOGIN],
      'email' => [true, Regex::EMAIL],
      'friend' => [false, Regex::ID]
    ], $_POST);
  }

  public function handle(): Response
  {
    try{
      $user = UserMapper::create($this->request);
      return new Response($user->getData());
    }
    catch(UniquenessViolated $e) {
      return new Response('Username Already Exists', 409);
    }

    return new Response('', 500);
  }
}