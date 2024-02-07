<?php

class InstallPlugin extends RootOnly
{
  public function __construct()
  {
    $this->init([
      'name' => [true, Regex::generic(1, 200)]
    ], $_POST);
  }

  public function handle(): Response
  {
    $ch = curl_init('https://arte-store.drolez-apps.cloud');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if ($errno = curl_errno($ch))
      return new Response("Error [$errno] Curling Arte Store Failed: " . curl_error($ch));

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Check for cURL errors
    switch ($httpCode) {
      case 200:
        $plugin_dir = PLUGINS_DIR . '/' . $this->request['name'];
        $plugin_zip = $plugin_dir . '/.zip';
        file_put_contents($plugin_zip, $response);
        mkdir($plugin_dir);

        $zip = new ZipArchive();
        if ($zip->open($plugin_zip) === true) {
          // Extract the contents
          $zip->extractTo($plugin_dir);
          $zip->close();
          require $plugin_dir . '/install.php';
          unlink($plugin_zip);
          return new Response;
        } else {
          return new Response("Failed to open the downloaded zip file", 500);
        }

      case 404:
        return new Response('Plugin Not Published On Store', 404);

      default:
        return new Response("Unexpected Error Occured", 500);
    }
  }
}
