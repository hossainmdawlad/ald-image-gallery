<?php
add_action( 'admin_action_delete_image', 'delete_image_admin_action' );
function delete_image_admin_action()
{
  global $ald_gallery_db_version;
	global $wpdb;
	global $ald_gallery_table;
	global $ald_image_table;
	$image = intval( trim($_GET['data'] ));
	if(isset($image))
	{
		$wpdb->delete( $ald_image_table, array( 'img_id' => $image ) );
		$msg = 'deleted';
	}
	$path = add_query_arg('message', $msg, $_SERVER['HTTP_REFERER']);
	wp_redirect( $path, $status = 302 );
	exit();
}
