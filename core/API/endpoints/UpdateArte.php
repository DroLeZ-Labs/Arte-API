<?php

class UpdateArte extends RootOnly
{
  public function __construct()
  {
    $this->init([], $_POST);
  }

  public function handle(): Response
  {
    $ch = curl_init('https://arte-store.drolez-apps.cloud/downloads/arte-core');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if ($errno = curl_errno($ch))
      return new Response("Error [$errno] Curling Arte Store Failed: " . curl_error($ch));

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Check for cURL errors
    switch ($httpCode) {
      case 200:
        // Creating backup
        if (is_dir(CORE_DIR . '-old') && !deleteDirectory(CORE_DIR . '-old'))
          return new Response('Couldn\'t create backup before update. Might be permission error', 500);

        if (!rename(CORE_DIR, CORE_DIR . '-old'))
          return new Response('Couldn\'t create backup before update. Might be permission error', 500);

        if(!mkdir(CORE_DIR) || !chmod(CORE_DIR, 0777)){
          rename(CORE_DIR . '-old', CORE_DIR);
          return new Response('Couldn\'t create core directory with 777 permission');
        }

        $file = CORE_DIR . '/arte-core.zip';
        file_put_contents($file, $response);
        chmod($file, 0777);

        $zip = new ZipArchive();
        if ($zip->open($file) === true) {
          // Extract the contents
          if ($zip->extractTo(CORE_DIR));
          $zip->close();
          chmodRecursive(CORE_DIR, 0777);
          rename(CORE_DIR . '/.htaccess', ROOT_DIR . '/.htaccess');
          rename(CORE_DIR . '/autoload.php', ROOT_DIR . '/autoload.php');
          rename(CORE_DIR . '/index.php', ROOT_DIR . '/index.php');
          unlink($file);

          // deleteDirectory(CORE_DIR . '-old');
          return new Response;
        } else {
          return new Response("Failed to open the downloaded zip file", 500);
        }
      default:
        return new Response("Unexpected Error Occured", 500);
    }
  }
}
