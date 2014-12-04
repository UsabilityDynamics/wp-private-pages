<?php
/**
 * Metabox Assigned Users
 * 
 * @todo: implement users management here ( Right now, there is no UI for it by default ). peshkov@UD
 */

/* Plugin Singleton object */
$instance = ud_get_wp_private_pages();
/* Get users */
$users = $instance->get_assigned_users( $post->ID );

?>
<div class="wp-private-page-users">
  <ul class="list">
    <?php if( !empty( $users ) ) : ?>
      <?php foreach( $users as $user ) : ?>
        <li class="user-item"><?php echo $user->display_name; ?> ( <a href="<?php echo get_edit_user_link( $user->ID ); ?>"><?php echo $user->user_login; ?></a> )</li>
      <?php endforeach; ?>
    <?php else : ?>
        <li class="no-users-found"><?php _e( 'No Users Found.', $instance->domain ); ?></li>
    <?php endif; ?>
  </ul>
</div>