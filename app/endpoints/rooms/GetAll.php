<?php

class GetAll extends Authenticated
{
  public function __construct()
  {
    $this->init([], $_GET);
  }

  public function handle(): Response
  {
    return new Response(RoomMapper::getAll());
  }
}