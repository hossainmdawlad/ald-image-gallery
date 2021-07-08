<?php
// Delete Gallery
add_action( 'admin_action_delete_gallery', 'delete_gallery_admin_action' );
function delete_gallery_admin_action()
{
	global $wpdb;
	global $ald_gallery_table;
	global $ald_image_table;
	$gallery = intval( trim($_GET['data']) );
	if(strlen( $gallery ) > 0)
	{
		$wpdb->delete( $ald_gallery_table, array( 'gallery_id' => $gallery ) );
		$wpdb->delete( $ald_image_table, array( 'gallery_id' => $gallery ) );
		$msg = 'true';
	}
	$path = add_query_arg('message', $msg, $_SERVER['HTTP_REFERER']);
	wp_redirect( $path, $status = 302 );
	exit();
}
