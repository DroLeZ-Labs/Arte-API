<?php

/**
 * The Response class handles HTTP responses.
 * @todo this response class needs to be updated (allow content_type in the constructor and background processing not working here)
 */
class Response
{
  /**
   * @var int The HTTP response code.
   */
  private int $code;

  /**
   * @var mixed The response body.
   */
  private $body;

  /**
   * @var string The hidden buffer for debug mode.
   */
  public string $hidden_buffer;

  /**
   * Response constructor.
   *
   * @param mixed $body The response body (optional).
   * @param int $code The HTTP response code (default: 200).
   */
  public function __construct($body = '', $code = 200)
  {
    $this->code = $code;
    $this->body = $body ?? '';
  }

  /**
   * Formats the response as an array.
   *
   * @return array The formatted response.
   */
  public function format()
  {
    return [
      'code' => $this->code,
      'body' => $this->body
    ];
  }

  /**
   * Retrieves the HTTP response code.
   *
   * @return int The HTTP response code.
   */
  public function getCode(): int
  {
    return $this->code;
  }

  /**
   * Retrieves the response body.
   *
   * @return mixed The response body.
   */
  public function getBody()
  {
    return $this->body;
  }

  /**
   * Outputs the response.
   * @todo what the hell is that File class, That's not a core thing bro, write it in app not here !!
   */
  public function echo(): void
  {
    header('Content-Type: text/plain', true);
    header('Connection: close');
    ignore_user_abort(true);

    $this->hidden_buffer = ob_get_clean();

    $body = '';
    if (is_iterable($this->body) || $this->body instanceof JsonSerializable)
      $body = json_encode($this->body, JSON_UNESCAPED_UNICODE);
    else if (is_string($this->body))
      $body = $this->body;

    if(DEBUG){
      header('Content-Length: ' . strlen($body) + strlen($this->hidden_buffer));
      echo $this->hidden_buffer;
    }
    else
      header('Content-Length: ' . strlen($body));


    echo $body;
    http_response_code($this->code);

    // Closing Connection
    while (ob_get_level() > 0)
      ob_end_flush();

    flush();
  }
}
