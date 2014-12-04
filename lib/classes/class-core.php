<?php
/**
 * Bootstrap
 *
 * @since 1.0.0
 */
namespace UsabilityDynamics\WPPP {

  if( !class_exists( 'UsabilityDynamics\WPPP\Core' ) ) {

    final class Core extends Scaffold {
      
      /**
       * 
       */
      public function __construct() {
        parent::__construct();
        
        /** 
         * Register Custom Post Types, meta and set their taxonomies 
         */
        $schema = $this->instance->get_schema( 'extra.schemas.model', true );
        if( !empty( $schema ) && is_array( $schema ) ) {
          \UsabilityDynamics\Model::define( $schema );
        }
        
        /** 
         * Setup setting for plugin on admin panel 
         */
        $this->ui = new UI();
        
        /**
         * Take care about showing Private Pages on Front End
         */
        add_action( 'template_redirect', array( $this, 'template_redirect' ) );
        add_action( 'the_content', array( $this, 'the_content' ) );
      }
      
      /**
       * 
       */
      public function template_redirect() {
        global $wp_query;
        
        /* Determine if current page is 'private_page' */
        if( 
          ( isset( $wp_query->query[ 'post_type' ] ) && $wp_query->query[ 'post_type' ] == 'private_page' ) ||
          ( isset( $wp_query->query_vars[ 'post_type' ] ) && $wp_query->query_vars[ 'post_type' ] == 'private_page' )
        ) {
          /* Make sure user is logged in. In other case we will redirect user to Home page */
          if( !is_user_logged_in() ) {
            wp_redirect( home_url() );
            exit();
          }
        }
      }
      
      /**
       * Determine what content we should show for WP Private Page on front end.
       */
      public function the_content( $content ) {
        global $post;
        
        if( $post->post_type == 'private_page' ) {
          
          /** 
           * It should not happen for single post page. But it can on using the_content in other places. 
           * So just return blank by default 
           */
          if( !is_user_logged_in() ) {
            return apply_filters( 'wppp_the_content_for_not_logged_in_user', '', $content );
          }
          
          /* Detemine if user can view content */
          if( !current_user_can( 'manage_options' ) && !Utility::is_user_assigned() ) {
            $c = get_template_part( 'wppp', 'blocked-info' );
            $content = apply_filters( 'wppp_the_content_blocked_info', $c, $content );
          }
          
        }
        
        return $content;
      }

    }

  }

}
