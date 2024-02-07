<?php

class UninstallPlugin extends RootOnly
{
  public function __construct()
  {
    $this->init([
      'name' => [true, Regex::generic(1, 200)]
    ], $_POST);
  }

  public function handle(): Response
  {
    $plugin_path = PLUGINS_DIR . '/' . $this->request['name'];
    if (!is_dir($plugin_path))
      return new Response('Plugin Not Installed', 404);

    require "$plugin_path/uninstall.php";
    $this->deleteDirectory($plugin_path);

    return new Response;
  }

  private function deleteDirectory(string $dir)
  {
    if (!is_dir($dir)) {
      return false;
    }

    $files = array_diff(scandir($dir), array('.', '..'));

    foreach ($files as $file) {
      $path = $dir . '/' . $file;

      if (is_dir($path)) {
        $this->deleteDirectory($path);
      } else {
        unlink($path);
      }
    }

    return rmdir($dir);
  }
}
