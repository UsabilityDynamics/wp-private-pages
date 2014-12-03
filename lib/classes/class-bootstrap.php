<?php
/**
 * Bootstrap
 *
 * @since 1.0.0
 */
namespace UsabilityDynamics\WPPP {

  if( !class_exists( 'UsabilityDynamics\WPPP\Bootstrap' ) ) {

    final class Bootstrap extends \UsabilityDynamics\WP\Bootstrap_Plugin {
      
      /**
       * Core
       *
       * @var object \UsabilityDynamics\WPPP\Core
       */
      public $core = NULL;
      
      /**
       * Singleton Instance Reference.
       *
       * @protected
       * @static
       * @property $instance
       * @type UsabilityDynamics\WPPP\Bootstrap object
       */
      protected static $instance = null;
      
      /**
       * Instantaite class.
       */
      public function init() {
        $this->define_settings();
        $this->core = new Core();
      }
      
      /**
       * Plugin Activation
       *
       */
      public function activate() {}
      
      /**
       * Plugin Deactivation
       *
       */
      public function deactivate() {}
      
      /**
       * Return localization's list.
       *
       * Example:
       * If schema contains l10n.{key} values:
       *
       * { 'config': 'l10n.hello_world' }
       *
       * the current function should return something below:
       *
       * return array(
       *   'hello_world' => __( 'Hello World', $this->domain ),
       * );
       *
       * @author peshkov@UD
       * @return array
       */
      public function get_localization() {
        return apply_filters( 'wp_private_pages_localization', array(
          'ppage'               => sprintf( '%s', $this->label() ),
          'ppages'              => sprintf( '%s', $this->label( 'plural' ) ),
          'new_ppage'           => sprintf( __( 'New %s', $this->domain ), $this->label() ),
          'create_ppage'        => sprintf( __( 'Create %s', $this->domain ), $this->label() ),
          'all_ppages'          => sprintf( __( 'All %s', $this->domain ), $this->label( 'plural' ) ),
          'edit_ppage'          => sprintf( __( 'Edit %s', $this->domain ), $this->label() ),
          'no_ppages_found'     => sprintf( __( 'No %s found', $this->domain ), $this->label( 'plural' ) ),
          'no_ppages_in_trash'  => sprintf( __( 'No %s in trash', $this->domain ), $this->label( 'plural' ) ),
          'search_ppages'       => sprintf( __( 'Search %s', $this->domain ), $this->label( 'plural' ) ),
          'update_ppage'        => sprintf( __( 'Update %s', $this->domain ), $this->label() ),
          'view_ppage'          => sprintf( __( 'View %s', $this->domain ), $this->label() ),
          'settings_page_title' => __( 'Settings', $this->domain ),
          'settings'            => __( 'Settings', $this->domain ),
          'white_labels'        => __( 'White Labels', $this->domain ),
          'singular'            => __( 'Singular', $this->domain ),
          'plural'              => __( 'Plural', $this->domain ),
          'general_settings'    => __( 'General Plugin\'s Settings', $this->domain ),
          'rewrite_slug'        => sprintf( '%s', $this->slug() ),
        ) );
      }
      
      /**
       * Returns White Label
       * 
       * @param string $key Values: 'singular', 'plural'. Default is 'singular'.
       * @return string
       */
      public function label( $key = 'singular' ) {
        $result = __( 'Private Page(s)', $this->domain );
        if( in_array( $key, array( 'singular', 'plural' ) ) ) {
          $result = $this->get( "labels.{$key}" );
          if( empty( $result ) ) {
            $result = $key == 'singular' ? __( 'Private Page', $this->domain ) : __( 'Private Pages', $this->domain );
          }
        }
        return $result;
      }
      
      /**
       * Returns Rewrite Slug
       * 
       * @param string $key Values: 'singular', 'plural'. Default is 'singular'.
       * @return string
       */
      public function slug() {
        $result = $this->get( "labels.singular" );
        return empty( $result ) ? 'private_page' : sanitize_key( $result );
      }
      
      /**
       * Returns specific schema from composer.json file.
       *
       * @param string $file Path to file
       * @author peshkov@UD
       * @return mixed array or false
       */
      public function get_schema( $key = '', $force_localization = false ) {
        if( $this->schema === null ) {
          if( !empty( $this->schema_path ) && file_exists( $this->schema_path ) ) {
            $this->schema = json_decode( file_get_contents( $this->schema_path ), true );
          }
        }
        //** Break if composer.json does not exist */
        if( !is_array( $this->schema ) ) {
          return false;
        }
        $result = false;
        //** Resolve dot-notated key. */
        if( strpos( $key, '.' ) ) {
          $current = $this->schema;
          $p = strtok( $key, '.' );
          while( $p !== false ) {
            if( !isset( $current[ $p ] ) ) {
              return false;
            }
            $current = $current[ $p ];
            $p = strtok( '.' );
          }
          $result = $current;
        } 
        //** Get default key */
        else {
          $result = isset( $this->schema[ $key ] ) ? $this->schema[ $key ] : false;
        }
        
        if( $force_localization ) {
          $result = \UsabilityDynamics\Utility::l10n_localize( $result, (array)$this->get_localization() );
        }
        return $result;
      }
      
      /**
       * Define Plugin Settings
       * 
       */
      private function define_settings() {
        $this->settings = new \UsabilityDynamics\Settings( array(
          'key'  => 'wppp_settings',
          'store'  => 'options',
          'data' => array(
            'name' => $this->name,
            'version' => $this->args[ 'version' ],
            'domain' => $this->domain,
            'prefix' => 'wppp_',
          )
        ) );
        /* Probably add default settings */
        $default = $this->get_schema( 'extra.schemas.settings', true );
        if( is_array( $default ) ) {
          $this->set( \UsabilityDynamics\Utility::extend( $default, $this->get() ) );  
        }
      }
      
      /**
       * Determine if Utility class contains missed function
       * in other case, just return NULL to prevent ERRORS
       * 
       * @author peshkov@UD
       */
      public function __call( $name, $arguments ) {
        if( is_callable( array( "\UsabilityDynamics\WPPP\Utility", $name ) ) ) {
          return call_user_func_array( array( "\UsabilityDynamics\WPPP\Utility", $name ), $arguments );
        } else {
          return NULL;
        }
      }

    }

  }

}
