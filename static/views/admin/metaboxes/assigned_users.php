<?php
/**
 * Metabox Assigned Users
 * 
 * 
 */

/* Plugin Singleton object */
$instance = ud_get_wp_private_pages();
/* Get users */
$users = $instance->get_assigned_users( $post->ID );

?>
<ul>
  <?php if( empty( $users ) ) : ?>
    <?php foreach( $users as $user ) : ?>
      <li class="user-item"><a href="<?php  ?>"><?php echo $user->display_name; ?></></li>
    <?php endforeach; ?>
  <?php else : ?>
      <li class="no-users-found"><?php _e( 'No Users Found.', $instance->domain ); ?></li>
  <?php endif; ?>
</ul>