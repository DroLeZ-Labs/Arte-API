<?php

class Create extends Endpoint
{
  public function __construct()
  {
    $this->init([
      'name' => [true, Regex::generic(1, 100)],
    ], $_POST);
  }

  public function handle(): Response
  {
    if ($room = RoomMapper::create($this->request))
      return new Response($room->getData());

    return new Response('', 500);
  }
}
