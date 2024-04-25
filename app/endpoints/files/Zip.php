<?php

class Zip extends Endpoint
{
  public function __construct()
  {
    $this->init([], $_GET);
  }

  public function handle(): Response
  {
    return new Response(new ResponseFile(MEDIA_DIR . '/bucket.zip'));
  }
}