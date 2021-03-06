<?php
/**
 * Scaffold class
 *
 * @author UsabilityDynamics, Inc
 */
namespace UsabilityDynamics\WPPP {

  if( !class_exists( 'UsabilityDynamics\WPPP\Scaffold' ) ) {

    /**
     * Scaffold
     *
     */
    abstract class Scaffold {
      
      /**
       * Additional properties are stored here.
       * It is using __get and __set methods
       */
      private $properties;
      
      /**
       * Bootstrap Singleton object
       *
       */
      public $instance = NULL;
      
      /**
       * Constructor
       *
       * @author peshkov@UD
       */
      public function __construct() {
        //** Get our Bootstrap Singleton object */
        $this->instance = ud_get_wp_private_pages();
      }
      
      /**
       * Renders template part.
       * 
       */
      public function get_template_part( $name, $data = array() ) {
        if( is_array( $data ) ) {
          extract( $data );
        }
        $path = $this->instance->path( '/static/views/' . $name . '.php', 'dir' );
        if( file_exists( $path ) ) {
          include( $path );
        }
      }
      
      /**
       * @param string $key
       * @param mixed $value
       *
       * @return \UsabilityDynamics\Settings
       */
      public function set( $key = null, $value = null ) {
        return $this->instance->set( $key, $value );
      }

      /**
       * @param string $key
       * @param mixed $default
       *
       * @return \UsabilityDynamics\type
       */
      public function get( $key = null, $default = null ) {
        return $this->instance->get( $key, $default );
      }
      
      /**
       * Store all custom properties in $this->properties
       *
       * @author peshkov@UD
       */
      public function __set( $name, $value ) {
        $this->properties[ $name ] = $value; 
      }
      
      /**
       * Get custom properties
       *
       * @author peshkov@UD
       */
      public function __get($name)  { 
        return isset ( $this->properties[ $name ] ) ? $this->properties[ $name ] : NULL; 
      }

    }
  
  }

}
