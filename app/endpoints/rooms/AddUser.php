<?php

class AddUser extends Authenticated
{
  public function __construct()
  {
    $this->init([
      'room_id' => [true, Regex::ID]
    ], $_POST);
  }

  public function handle(): Response
  {
    $room = new Room($this->request['room_id']);

    try {
      ConnectionMapper::create([
        'user_id' => $this->user->get('id'),
        'room_id' => $room->get('id')
      ]);

      return new Response;
    } catch (ForeignKeyViolated $e) {
      return new Response('Room not found', 404);
    } catch(Exception | Error $e) {
      return new Response('', 500);
    }
  }
}
