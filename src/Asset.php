<?php
namespace Cvy\WP\Assets;

abstract class Asset
{
  protected $rel_path;

  protected $deps;

  public function __construct( string $rel_path, array $deps = [] )
  {
    $this->rel_path = $rel_path;
    $this->deps = $deps;
  }

  public function enqueue() : void
  {
    $hook_name = is_admin() ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts';

    if ( ! did_action( $hook_name ) && current_action() !== $hook_name )
    {
      add_action( $hook_name, [ $this, '_on_enqueue_scripts_hook' ] );
    }
    else
    {
      $this->_on_enqueue_scripts_hook();
    }
  }

  abstract public function _on_enqueue_scripts_hook() : void;

  protected function get_root_dir_rel_path() : string
  {
    return 'assets/';
  }

  protected function get_path() : string
  {
    return get_theme_file_path( $this->get_root_dir_rel_path() . $this->rel_path );
  }

  protected function get_url() : string
  {
    return get_theme_file_uri( $this->get_root_dir_rel_path() . $this->rel_path );
  }

  protected function get_handle() : string
  {
    $rel_path = $this->rel_path;

    $file_extension_pattern = '~\..*~';
    $rel_path = preg_replace( $file_extension_pattern, '', $rel_path );

    $prefix = strtolower( Main::get_app_root_namespace() );

    return $prefix . '_' . str_replace( '/', '_', $rel_path );
  }

  protected function get_ver() : string
  {
    return filemtime( $this->get_path() );
  }
}
