<?php

class Create extends Endpoint implements Testable
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
    try {
      $user = UserMapper::create($this->request);
      return new Response($user->getData());
    } catch (UniquenessViolated $e) {
      return new Response('Username Already Exists', 409);
    } catch (ForeignKeyViolated $e) {
      return new Response('Friend Not Found', 404);
    }

    return new Response('', 500);
  }

  public function arrange(): array
  {
    $with_friend_scenario = new Scenario('with_friend', function () {
      return [
        'username' => Regex::generateLogin(),
        'password' => Regex::generateLogin(),
        'email' => Regex::generateEmail(),
        'friend' => Regex::generateID('users'),
      ];
    });
    $with_friend_scenario->addAssertion(new Assertion('user_created', function (array $input, Response $output) {
      return (bool) UserMapper::get($input) && $output->getCode() == 200;
    }));

    $without_friend_scenario = new Scenario('without_friend', function () {
      return [
        'username' => Regex::generateLogin(),
        'password' => Regex::generateLogin(),
        'email' => Regex::generateEmail(),
      ];
    });
    $with_friend_scenario->addAssertion(new Assertion('user_created', function (array $input, Response $output) {
      return (bool) UserMapper::get($input) && $output->getCode() == 200;
    }));

    $with_wrong_friend_scenario = new Scenario('with_wrong_friend', function () {
      return [
        'username' => Regex::generateLogin(),
        'password' => Regex::generateLogin(),
        'email' => Regex::generateEmail(),
        'friend' => Regex::generateID('users', false),
      ];
    });
    $with_friend_scenario->addAssertion(new Assertion('friend_not_found', function (array $input, Response $output) {
      return $output->getCode() == 404 && (bool) !UserMapper::get($input) ;
    }));

    return [$with_friend_scenario, $without_friend_scenario, $with_wrong_friend_scenario];
  }
}
