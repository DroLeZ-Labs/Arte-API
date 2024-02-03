<?php

/* The above class is an abstract class in PHP that serves as a base for creating endpoints, handling requests, and performing tests on those endpoints. */
abstract class Endpoint
{
  public array $request = [];
  private Validator $validator;
  private int $start_time;
  private int $duration;
  protected array $expect;
  protected array $prehandles = [];

  // Endpoint Testing Belonigngs
  protected array $failures = [];

  /**
   * The `init` function initializes the properties of an object and performs validation on the request
   * parameters.
   * 
   * @param array expect An array containing the expected parameters for the request.
   * @param array request An array containing the request parameters.
   * 
   * @return void In the code snippet provided, a Response object is being returned if the 
   * variable is not empty. The Response object is created with the message "Invalid parameter: "
   * and a status code of 400.
   */
  protected function init(array $expect, array $request): void
  {
    $this->expect = $expect;
    $this->start_time = floor(microtime(true) * 1000);
    $this->request = [...$this->request, ...$request];
    $this->validator = new Validator($expect, static::class);
    $this->duration = 0;

    $this->prehandles[] = function () {
      if ($invalid = $this->validator->validate($this->request))
        return new Response("Invalid parameter: $invalid", 400);

      $this->request = $this->validator->getFiltered();
    };
  }

  /**
   * The getDuration function returns the duration of an object.
   * 
   * @return int Endpoint running time.
   */
  public function getDuration(): int
  {
    return $this->duration;
  }

  /**
   * The prehandle function iterates through a list of callbacks and returns the response from the first callback that returns a non-null value, or null if none of the callbacks return a response.
   * 
   * @return ?Response a nullable Response object.
   */
  public function prehandle(): ?Response
  {
    foreach ($this->prehandles as $callback)
      if ($response = $callback())
        return $response;

    return null;
  }

  /**
   * Abstract method to perform the main handling process
   * @return Response The response generated by the endpoint
   */
  abstract protected function handle(): Response;

  /**
   * Runs the endpoint and returns the response
   * @return Response The response generated by the endpoint
   */
  public function run(): Response
  {
    if ($response = $this->prehandle())
      return $response;

    $response = $this->handle();

    $this->duration = floor(microtime(true) * 1000) - $this->start_time;

    return $response;
  }

  public function safeRun(): Response
  {
    try {
      return $this->run();
    } catch (Exception $e) {
      return new Response($e->getMessage(), $e->getCode());
    }
  }

  /**
   * Checks if a parameter is present in the request
   * @param string $param The name of the parameter
   * @return bool True if the parameter is present, else false
   */
  public function expects($param): bool
  {
    return isset($this->request[$param]);
  }

  /**
   * Method executed after the main handling process
   */
  public function postHandle(): void
  {
  }

  public function test(): Response
  {
    // Validate test request
    $validator = new Validator(
      [
        'no_trials' => [false, Regex::INT]
      ],
      static::class
    );

    if (!$this instanceof Testable)
      return new Response("Non-Testable Endpoint: " . static::class, 400);

    if ($invalid = $validator->validate($this->request))
      return new Response("Invalid parameter: $invalid", 400);

    $no_trials = $_REQUEST['no_trials'] ?? 10;

    // firing testruns
    $failure_details = [];
    $successes = 0;
    $failures = 0;

    // Trials
    for ($i = 0; $i < $no_trials; $i++) {
      $trial_i = $i + 1;

      // Arrange
      try {
        $scenarios = $this->arrange();
      } catch (Exception $e) {
        $failures++;
        $failure_details["trial $trial_i"] = [
          'error' => "Error Arranging scenarios: " . trace($e)
        ];
        continue;
      }

      // Act and Assert
      $failed_cases = [];
      foreach ($scenarios as $scenario) {
        try {
          $this->request = $scenario->generateInput();
        } catch (Exception $e) {
          $failed_cases["Scenario: " . $scenario->name] = [
            'error' => "Error generating input: " . trace($e)
          ];
          continue;
        }

        $start = floor(microtime(true) * 1000);
        $output = $this->safeRun();
        $duration = floor(microtime(true) * 1000) - $start;

        $assert_result = $scenario->assertAll($this->request, $output);
        if (count($assert_result))
          $failed_cases["Scenario: " . $scenario->name] = [
            'duration' => $duration,
            ...$assert_result,
            'payload' => $this->request,
            'code' => $output->getCode(),
            'body' => $output->getBody()
          ];
      }

      // Add to failure details only if at least one case failed
      if (!count($failed_cases))
        $successes++;
      else {
        $failures++;
        $failure_details["trial $trial_i"] = $failed_cases;
      }
    }

    return new Response(json_encode([
      'successes' => $successes,
      'failures' => $failures,
      'failure_details' => $failure_details
    ], JSON_PRETTY_PRINT));
  }
}
