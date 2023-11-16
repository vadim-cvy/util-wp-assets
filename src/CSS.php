<?php
namespace Cvy\WP\Assets;

final class CSS extends Asset
{
  protected function on_enqueue_scripts_hook() : void
  {
    wp_enqueue_style( $this->get_handle(), $this->get_url(), $this->deps, $this->get_ver() );
  }

  protected function get_root_dir_rel_path() : string
  {
    return parent::get_root_dir_rel_path() . 'dist/css/';
  }
}
