<?php
namespace Cvy\WP\Assets;

final class JS extends Asset
{
  private $localized_data;

  private $in_footer;

  public function __construct( string $rel_path, array $deps = [], bool $in_footer = true )
  {
    parent::__construct( $rel_path, $deps );

    $this->in_footer = $in_footer;
  }

  protected function on_enqueue_scripts_hook() : void
  {
    wp_enqueue_script( $this->get_handle(), $this->get_url(), $this->deps, $this->get_ver(), $this->in_footer );

    $this->maybe_localize_data();
  }

  private function maybe_localize_data() : void
  {
    if ( empty( $this->localized_data ) )
    {
      return;
    }

    $js_object_name = '';

    $sanitized_handle = str_replace( '-', '_', $this->get_handle() );
    $handle_parts = explode( '_', $sanitized_handle );

    foreach ( $handle_parts as $i => $handle_part )
    {
      if ( $i !== 0 )
      {
        $handle_part = ucfirst( $handle_part );
      }

      $js_object_name .= $handle_part;
    }

    $js_object_name .= 'Data';

    wp_localize_script( $this->get_handle(), $js_object_name, $this->localized_data );
  }

  protected function get_root_dir_rel_path() : string
  {
    return parent::get_root_dir_rel_path() . 'dist/js/';
  }

  public function enqueue( array $localized_data = [] ) : void
  {
    if ( ! empty( $localized_data ) )
    {
      $this->localized_data = $localized_data;
    }

    parent::enqueue();
  }
}
