<?php
/**
 * Helper Functions List
 *
 * @class Utility
 */
namespace UsabilityDynamics\WPPP {
  
  if( !class_exists( 'UsabilityDynamics\WPPP\Utility' ) ) {

    class Utility {

      /**
       * Get Assigned Users 
       */
      static public function get_assigned_users( $post_id ) {
        $users = array();
        /* Plugin Singleton object */
        $instance = ud_get_wp_private_pages();
        /* Get IDs */
        $users_ids = get_post_meta( $post_id, $instance->get( 'prefix' ) . 'assigned_users', true );
        if( !empty( $users_ids ) && is_array( $users_ids ) ) {
          $users = get_users( array(
            'include' => $users_ids,
          ) );
        }
        return $users;
      }
      
      /**
       * Add ( assign ) user to Private Page
       */
      static public function assign_user( $user_id, $post_id ) {
        /* Plugin Singleton object */
        $instance = ud_get_wp_private_pages();
        try {
          $post = get_post( $post_id );
          /* Be sure post is 'private_page' */
          if( !$post || $post->post_type !== 'private_page' ) {
            throw new \Exception( __( 'Post can not be found or it does not belong to Private Page', $instance->domain ) );
          }
          $user = get_user_by( 'id', $user_id );
          if( !$user ) {
            throw new \Exception( __( 'User does not exist', $instance->domain ) );
          }
          /* Custom Functionality on Adding User to post */
          $is_approved = apply_filters( 'user_can_be_assigned_to_private_post', true, $user, $post );
          if( is_wp_error( $is_approved ) ) {
            throw new \Exception( $is_approved->get_error_message() );
          }
          $user_ids = get_post_meta( $post_id, $instance->get( 'prefix' ) . 'assigned_users', true );
          $user_ids = !is_array( $user_ids ) ? array() : $user_ids;
          if( in_array( $user_id, $user_ids ) ) {
            throw new \Exception( __( 'The user is already assigned to current post', $instance->domain ) );
          }
          $user_ids[] = $user_id;
          if( !update_post_meta( $post_id, $instance->get( 'prefix' ) . 'assigned_users', $user_ids ) ) {
            throw new \Exception( __( 'There is some error on saving data to DataBase. Please try later.', $instance->domain ) );
          }
        } catch ( \Exception $e ) {
          return new \WP_Error( $e->getMessage() ); 
        }
        return true;
      }
      
      /**
       * Remove ( unassign ) user to Private Page
       */
      static public function unassign_user( $user_id, $post_id ) {
        /* Plugin Singleton object */
        $instance = ud_get_wp_private_pages();
        try {
          $post = get_post( $post_id );
          /* Be sure post is 'private_page' */
          if( !$post || $post->post_type !== 'private_page' ) {
            throw new \Exception( __( 'Post can not be found or it does not belong to Private Page', $instance->domain ) );
          }
          $user = get_user_by( 'id', $user_id );
          if( !$user ) {
            throw new \Exception( __( 'User does not exist', $instance->domain ) );
          }
          /* Custom Functionality on Removing User from post */
          $is_approved = apply_filters( 'user_can_be_unassigned_from_private_post', true, $user, $post );
          if( is_wp_error( $is_approved ) ) {
            throw new \Exception( $is_approved->get_error_message() );
          }
          $user_ids = get_post_meta( $post_id, $instance->get( 'prefix' ) . 'assigned_users', true );
          $user_ids = !is_array( $user_ids ) ? array() : $user_ids;
          if( !in_array( $user_id, $user_ids ) ) {
            throw new \Exception( __( 'The user is not assigned to current post so he can not be unassigned', $instance->domain ) );
          }
          $pos = array_search( $user_id, $user_ids );
          unset( $user_ids[ $pos ] );
          if( !update_post_meta( $post_id, $instance->get( 'prefix' ) . 'assigned_users', $user_ids ) ) {
            throw new \Exception( __( 'There is some error on saving data to DataBase. Please try later.', $instance->domain ) );
          }
        } catch ( \Exception $e ) {
          return new \WP_Error( $e->getMessage() ); 
        }
        return true;
      }
      
      /**
       * Determine is user is assigned to post.
       */
      static public function is_user_assigned( $user_id = false, $post_id = false ) {
        global $post;
        /* Determine post ID */
        if( !$post_id ) {
          if( !is_object( $post ) || empty( $post->ID ) ) {
            return false;
          }
          $post_id = $post->ID;
        }
        /* Determine user  */
        if( !$user_id ) {
          $user_id = get_current_user_id();
          if( !$user_id ) {
            return false;
          }
        }
        /* Plugin Singleton object */
        $instance = ud_get_wp_private_pages();
        /* Get list of all assigned users */
        $user_ids = get_post_meta( $post_id, $instance->get( 'prefix' ) . 'assigned_users', true );
        return in_array( $user_id, $user_ids ) ? true : false;
      }
      
    }

  }
  
}