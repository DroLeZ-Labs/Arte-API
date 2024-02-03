<?php

class GenerateToken extends Endpoint
{
  public function __construct()
  {
    $this->init([
      'id' => [true, Regex::ID]
    ], $_GET);
  }

  public function handle(): Response
  {
    return new Response(Authenticator::encode($this->request));
  }
}
