<?php
add_action( 'admin_action_add_image', 'add_image_admin_action' );
function add_image_admin_action()
{
	$image_name = sanitize_text_field( trim($_POST['imagename'] ));
	$image_url = sanitize_text_field( trim($_POST['imageurl'] ));
	$image_text = sanitize_text_field( trim($_POST['imagetext'] ));
	$gallery_id = intval( trim($_POST['gallery'] ));
	if( (strlen( $image_name) > 0) && (strlen( $image_url) > 0) )
	{
		global $wpdb;
		global $ald_image_table;
		$wpdb->insert(
		$ald_image_table,
			array(
				'img_name' => $image_name,
				'img_url' => $image_url,
				'img_text' => $image_text,
				'gallery_id' => $gallery_id,
			)
		);
		$msg = 'true';
	}
	else{
		$msg = 'false';
	}
	$path = add_query_arg('message', $msg, $_SERVER['HTTP_REFERER']);
	wp_redirect( $path, $status = 302 );
	exit();
}
