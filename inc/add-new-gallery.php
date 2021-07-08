<?php
function add_new_ald_gallery() {
  global $ald_gallery_db_version;
	global $wpdb;
	global $ald_gallery_table;
	global $ald_image_table;
	?>
		<h1>Add New Gallery</h1>
		<?php
			if(isset($_GET['message']) ){
				if( $_GET['message'] = 'true'){
					?>
						<div id="message" class="updated notice notice-success is-dismissible"><p>Gallery added successfully.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
					<?php
				}
				else if( $_GET['message']= 'false'){
					?>
						<div id="message" class="updated notice notice-error is-dismissible"><p>Sorry. gallery didn't added. Please try again later.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
					<?php
				}
			}
			?>
		<a href="<?php echo admin_url( 'admin.php?page=ald-gallery', 'http' ) ?>" class="button button-secondary">Back to Gallery List</a>
		<form action="<?php echo admin_url( 'admin.php' ); ?>" method="post">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">Gallery Name</th>
						<td><input type="text" name="galleryname" placeholder="Gallery Name" class="regular-text" required /></td>
					</tr>
					<tr>
						<th scope="row">Gallery Description</th>
						<td><textarea name="gallerytext" placeholder="Gallery Description" rows="5" class="large-text code"></textarea></td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" name="action" value="add_gallery" />
			<input type="submit" value="Add it!" class="button button-primary" />
		</form>
	<?php
}
// Add Gallery
add_action( 'admin_action_add_gallery', 'add_gallery_admin_action' );
function add_gallery_admin_action()
{
	$gallery_name = sanitize_text_field( trim($_POST['galleryname']) );
	$gallery_text = sanitize_text_field( trim($_POST['gallerytext'] ));
	if( strlen( $gallery_name ) > 0)
	{
		global $wpdb;
		global $ald_gallery_table;
		$wpdb->insert(
		$ald_gallery_table,
			array(
				'gallery_name' => $gallery_name,
				'gallery_text' => $gallery_text,
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
