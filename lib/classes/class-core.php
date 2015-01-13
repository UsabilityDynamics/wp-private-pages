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
          /* Make sure user is logged in. In other case we maybe redirect user to Home page */
          if( !is_user_logged_in() ) {
            if( apply_filters( 'wppp_redirect_from_prohibited_page', false ) ) {
              wp_redirect( home_url() );
              exit();  
            }
          }
          /** 
           * If Hierarchical Access is enabled, 
           * we try to determine if user is assigned to parent post.
           * If not, we do redirection to parent post. 
           */
          if( 
            !current_user_can( 'manage_options' ) && 
            $this->instance->get( 'access.hierarchical' ) === 'true' && 
            is_object( $wp_query->post ) && 
            $wp_query->post->post_parent > 0 
          ) {
            $post = get_post( $wp_query->post->post_parent );
            if( !is_object( $post ) || !isset( $post->ID ) || $post->post_type !== 'private_page' ) {
              /* WTF? */
              wp_redirect( home_url() );
              exit();
            }
            if( !Utility::is_user_assigned( get_current_user_id(), $post->ID ) ) {
              /* Redirect user to parent page */
              wp_redirect( get_permalink( $post->ID ) );
              exit();
            }
          }
          
        }
      }
      
      /**
       * Determine what content we should show for WP Private Page on front end.
       */
      public function the_content( $content ) {
        global $post;
        
        if( $post->post_type == 'private_page' ) {
          
          /* Detemine if user can view content */
          if( !current_user_can( 'manage_options' ) && !Utility::is_user_assigned() ) {
            $c = $this->get_template_part( 'page-prohibited' );
            $content = apply_filters( 'wppp_the_content_blocked_info', $c, $content );
          }
          
        }
        
        return $content;
      }

    }

  }

}
