<?php

class Friendship
{
  private User $actor;
  public function __construct(User $user)
  {
    $this->actor = $user;
  }

  public function setFriend(User $friend): bool
  {
    $this->actor->set('friend', $friend);
    return $this->actor->save();
  }
}
