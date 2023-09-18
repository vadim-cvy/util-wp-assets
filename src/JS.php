<?php
namespace Cvy\WP\Assets;

class JS extends Asset
{
  const VUE_HANDLE = 'cvy-vue';
  const SWAL2_HANDLE = 'cvy-swal2';
  const FAS_HANDLE = 'cvy-font-awesome';

  protected $localized_data;

  public function __construct( string $rel_path, array $deps = [], bool $in_footer = true )
  {
    parent::__construct( $rel_path, $deps );

    $this->in_footer = $in_footer;
  }

  public function _on_enqueue_scripts_hook() : void
  {
    $handle = $this->get_handle();

    $this->maybe_enqueue_predefined_deps();

    wp_enqueue_script( $handle, $this->get_url(), $this->deps, $this->get_ver(), $this->in_footer );

    $this->maybe_localize_data();
  }

  protected function maybe_enqueue_predefined_deps() : void
  {
    $predefined_deps = [
      static::VUE_HANDLE =>
        /**
         * This is a plugin I use in all of my projects.
         * todo: implement \Cvy\WP\Env\Env package instead
         */
        \Jev\Env::is_prod() ?
        'https://cdn.jsdelivr.net/npm/vue@3.3.4/dist/vue.global.prod.min.js' :
        'https://cdn.jsdelivr.net/npm/vue@3.3.4/dist/vue.global.min.js',

      static::SWAL2_HANDLE => 'https://cdn.jsdelivr.net/npm/sweetalert2@11.7.22/dist/sweetalert2.all.min.js',

      static::FAS_HANDLE => 'https://kit.fontawesome.com/8bd418a558.js',
    ];

    foreach ( $this->deps as $dep_handle )
    {
      $dep_src = $predefined_deps[ $dep_handle ] ?? null;

      if ( $dep_src )
      {
        wp_enqueue_script( $dep_handle, $dep_src, [], null, true );
      }
    }
  }

  protected function maybe_localize_data() : void
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
    return parent::get_root_dir_rel_path() . 'js/build/';
  }

  public function enqueue( array $localized_data = [] ) : void
  {
    if ( ! empty( $localized_data ) )
    {
      $this->localized_data = $localized_data;
    }

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
}
