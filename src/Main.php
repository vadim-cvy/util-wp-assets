<?php
namespace Cvy\WP\Assets;

use \Exception;

class Main
{
  static protected $app_root_namespace;

  static public function set_app_root_namespace( string $app_root_namespace ) : void
  {
    static::$app_root_namespace = $app_root_namespace;
  }

  static public function get_app_root_namespace() : string
  {
    if ( ! static::$app_root_namespace )
    {
      throw new Exception( 'App root namespace is not set!' );
    }

    return static::$app_root_namespace;
  }
}
