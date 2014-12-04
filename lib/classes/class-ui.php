<?php
/**
 * UI
 *
 * @author UsabilityDynamics, inc
 */
namespace UsabilityDynamics\WPPP {

  if( !class_exists( 'UsabilityDynamics\WPPP\UI' ) ) {

    /**
     *
     *
     * @author UsabilityDynamics, inc
     */
    class UI extends Scaffold {
      
      /**
       * 
       * @var object \UsabilityDynamics\UI\Settings
       */
      public $ui;
      
      /**
       * Constructor
       *
       * @author peshkov@UD
       */
      public function __construct() {
        parent::__construct();
        
        /* Handle Scripts and Styles */
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        
        /* Add metaboxes hook */
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        
        /** 
         * Setup Admin Settings Interface 
         */
        $this->ui = new \UsabilityDynamics\UI\Settings( $this->instance->settings, $this->instance->get_schema( 'extra.schemas.ui', true ) );
        
        /**
         * @see vendor/usabilitydynamics/lib-ui/static/templates/admin/main.php
         */
        add_action( 'ud:ui:settings:view:main:top', array( $this, 'custom_ui' ) );
        // Adds Tab Item ( Link )
        add_action( 'ud:ui:settings:view:tab_link', array( $this, 'custom_ui' ) );
        // Adds Panel for Tab
        add_action( 'ud:ui:settings:view:tab_container', array( $this, 'custom_ui' ) );
        add_action( 'ud:ui:settings:view:main:bottom', array( $this, 'custom_ui' ) );
        // Adds Custom Actions ( e.g. Synchronize with Maestro Conference )
        add_action( 'ud:ui:settings:view:main:actions', array( $this, 'custom_ui' ) );
        
        /**
         * @see vendor/usabilitydynamics/lib-ui/static/templates/admin/tab.php
         */
        //
        add_action( 'ud:ui:settings:view:tab:api:top', array( $this, 'custom_ui' ) );
        add_action( 'ud:ui:settings:view:tab:api:bottom', array( $this, 'custom_ui' ) );
        
        /**
         * @see vendor/usabilitydynamics/lib-ui/static/templates/admin/section.php
         */
        add_action( 'ud:ui:settings:view:section:credentials:top', array( $this, 'custom_ui' ) );
        add_action( 'ud:ui:settings:view:section:sync:top', array( $this, 'custom_ui' ) );
        add_action( 'ud:ui:settings:view:section:credentials:bottom', array( $this, 'custom_ui' ) );
        add_action( 'ud:ui:settings:view:section:sync:bottom', array( $this, 'custom_ui' ) );
        
      }
      
      /**
       * Custom UI on Settings page
       */
      public function custom_ui() {
        
        $hook = current_filter();
        
        switch( $hook ) {
          
          case 'ud:ui:settings:view:main:top':
            // Add custom content here
            break;
          case 'ud:ui:settings:view:tab_link':
            // Add custom content here
            break;
          case 'ud:ui:settings:view:tab_container':
            // Add custom content here
            break;
          case 'ud:ui:settings:view:main:bottom':
            // Add custom content here
            break;
          case 'ud:ui:settings:view:main:actions':
            // Add custom content here
            break;
          case 'ud:ui:settings:view:tab:api:top':
            // Add custom content here
            break;
          case 'ud:ui:settings:view:tab:api:bottom':
            // Add custom content here
            break;
          case 'ud:ui:settings:view:section:credentials:top':
            // Add custom content here
            break;
          case 'ud:ui:settings:view:section:sync:top':
            // Add custom content here
            break;
          case 'ud:ui:settings:view:section:credentials:bottom':
            // Add custom content here
            break;
          case 'ud:ui:settings:view:section:sync:bottom':
            // Add custom content here
            break;
        }
        
      }
      
      /**
       * Register admin Scripts and Styles.
       *
       */
      public function admin_enqueue_scripts() {
        $screen = get_current_screen();
        
        switch( $screen->id ) {
          
          case 'private_page':
            wp_enqueue_style( 'wppp-edit-post', $this->instance->path( 'static/styles/admin/edit-post' . ( WP_DEBUG ? '.dev' : '' ) . '.css' ) );
            break;
          
        }
        
      }
      
      /**
       * Register metaboxes.
       *
       * @global type $post
       * @global type $wpdb
       */
      public function add_meta_boxes() {
        add_meta_box( 'private_page_assigned_users', __( 'Assigned Users', $this->get( 'domain' ) ), array( $this, 'render_metabox' ), 'private_page', 'side', 'high' );
      }
      
      /**
       * 
       */
      public function render_metabox( $post, $metabox ) {
        switch( $metabox[ 'id' ] ) {
          case 'private_page_assigned_users':
            $this->get_template_part( 'admin/metaboxes/assigned_users', array( 'post' => $post ) );
            break;
        }
      }
      
      
    }
  
  }

}
