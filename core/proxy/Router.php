<?php

class Router
{
  private Route $route;
  private array $routes;

  public function __construct()
  {
    $this->route = new Route;

    $requestUri = $_SERVER['REQUEST_URI'];
    if (($pos = strpos($requestUri, API_BASE)) !== false)
      $requestUri = substr($requestUri, $pos + strlen(API_BASE));

    $this->route->uri = explode('?', ltrim(parse_url($requestUri, PHP_URL_PATH), '/'))[0];
    $this->route->test = (strpos($this->route->uri, 'test/') === 0);
    $this->route->uri = preg_replace('/^test\//', '', $this->route->uri);

    $this->routes = json_decode(file_get_contents(ROUTES_JSON), true);
  }

  public function route(): Route
  {
    foreach ($this->routes as $pattern => $endpoint) {
      $pattern = trim($pattern, '/');
      $patternSections = explode('/', $pattern);

      if (count($patternSections) === count(explode('/', $this->route->uri))) {
        $params = [];

        $match = true;
        foreach ($patternSections as $index => $patternSection) {
          if (
            $patternSection !== explode('/', $this->route->uri)[$index] &&
            !preg_match('/\{(.+?)\}/', $patternSection)
          ) {
            $match = false;
            break;
          }

          if (preg_match('/\{(.+?)\}/', $patternSection, $matches))
            $params[$matches[1]] = explode('/', $this->route->uri)[$index];
        }

        if ($match) {
          $path = $endpoint;
          require ENDPOINTS_DIR . "/$path";
          $endpoint_name = pathinfo($endpoint, PATHINFO_FILENAME);
          $this->route->endpoint = new $endpoint_name;
          $this->route->endpoint->request = [...$this->route->endpoint->request, ...$params];
          break;
        }
      }
    }

    return $this->route;
  }
}

class Route
{
  public string $uri;
  public Endpoint $endpoint;
  public bool $test = false;
}
