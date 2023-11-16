<?php
namespace Cvy\WP\Assets;

use \Exception;

class Main
{
  static private $app_namespace;

  static public function set_app_namespace( string $app_namespace ) : void
  {
    static::$app_namespace = $app_namespace;
  }

  static public function get_app_namespace() : string
  {
    if ( ! static::$app_namespace )
    {
      throw new Exception(sprintf(
        'App namespace is not set! Use %s::set_app_namespace().',
        get_called_class()
      ));
    }

    return static::$app_namespace;
  }
}
