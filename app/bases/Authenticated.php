<?php

abstract class Authenticated extends Endpoint
{
  protected User $user;

  protected function init(array $expect, array $request): void
  {
    parent::init(
      [...$expect, 'Authorization' => [true, Regex::JWT_BEARER]],
      [...$request, 'Authorization' => getallheaders()['Authorization'] ?? '']
    );

    $this->prehandles[] = function () {
      $token = explode('Bearer ', $this->request['Authorization'])[1];
      if (!$data = Authenticator::decode($token))
        return new Response('Unauthorized', 401);

      $this->user = new User($data->id);

      unset($this->request['Authorization']);
    };
  }
}
