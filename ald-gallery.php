<?php
/*
Plugin Name: ALD Image Gallery
Plugin URI:http://www.technoviable.com/
Description: This plugin will add a Simple image gallery. ALD Gallery is a wordpress image gallery plugin. You can create multiple image gallery with multiple image url. And show the gallery in wordpress frontend supported by Lightcase. Supports in all themes with jquery.
Author: Hossain Md. Awlad
Author URI:http://www.technoviable.com/
Version: 2.0
License: GPLv2

{Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.
*/

define( 'ald_gallery_db_version', '2.0' );
// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
// Backwards compatibility for older than PHP 5.3.0
if ( !defined( '__DIR__' ) ) {
    define( '__DIR__', dirname( __FILE__ ) );
}

global $wpdb;
$ald_gallery_table = $wpdb->prefix .'ald_gallery';
$ald_image_table = $wpdb->prefix .'ald_images';
$ald_gallery_path = admin_url('admin.php?page=ald-gallery', 'http' );
$ald_gallery_db_version = '2.0';
function install_ald_gallery(){
	global $ald_gallery_db_version;
	global $wpdb;
	global $ald_gallery_table;
	global $ald_image_table;
	$charset_collate1 = $wpdb->get_charset_collate();
	$charset_collate2 = $wpdb->get_charset_collate();

	$gallery_table = "CREATE TABLE IF NOT EXISTS $ald_gallery_table(
		gallery_id mediumint(9) NOT NULL AUTO_INCREMENT,
		gallery_name tinytext NOT NULL,
		gallery_text text NOT NULL,
		PRIMARY KEY (gallery_id)
	)$charset_collate1;";

	$img_table = "CREATE TABLE IF NOT EXISTS $ald_image_table(
		img_id mediumint(9) NOT NULL AUTO_INCREMENT,
		img_name tinytext NOT NULL,
		img_text text NOT NULL,
		img_url varchar(100) DEFAULT '' NOT NULL,
		gallery_id mediumint(9) NOT NULL,
		PRIMARY KEY (img_id)
	)$charset_collate2;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $gallery_table );
	dbDelta( $img_table );
	add_option( 'ald_gallery_db_version', $ald_gallery_db_version );

}
if (isset($_GET['activate']) && $_GET['activate'] == 'true')
	add_action('init', 'install_ald_gallery');

add_action('admin_menu', 'ald_gallry_plugin_menu');
function ald_gallry_plugin_menu() {
	add_menu_page(
			__( 'ALD Gallery', 'ald-gallery' ),
			'ALD Gallery',
			'manage_options',
			'ald-gallery',
			'ald_gallery_index',
			plugins_url( 'ald-image-gallery/img/image-gallery.svg' ),
			6
		);
}

add_action('admin_menu', 'ald_register_add_new_ald_gallery_page');
function ald_register_add_new_ald_gallery_page() {
	add_submenu_page(
		'ald-gallery',
		'Add New Gallery',
		'Add New Gallery',
		'manage_options',
		'add-new-ald-gallery',
		'add_new_ald_gallery' );
}

function admin_scripts_js() {
  wp_enqueue_style( 'jquery-ui-style', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
  wp_enqueue_style( 'ald-image-gallery-admin-style', plugins_url('css/admin-panel-style.css', __FILE__) );
	wp_enqueue_media();
	wp_enqueue_script( 'ald-image-gallery-admin-script', plugins_url('js/ald-image-gallery.admin.js', __FILE__), array('jquery','jquery-ui-dialog'), ald_gallery_db_version, true );
}

function external_css() {
	wp_enqueue_style( 'ald-style', plugins_url('css/ald-style.css', __FILE__) );
	wp_enqueue_style( 'lightcase-style', plugins_url('lightcase/src/css/lightcase.css', __FILE__) );
}
function external_js() {
	wp_enqueue_script( 'ald-script', plugins_url('js/ald-script.js', __FILE__), array('jquery'), ald_gallery_db_version, true );
	wp_enqueue_script( 'lightcase-script', plugins_url('lightcase/src/js/lightcase.js', __FILE__), array('jquery'), null, true );
	wp_enqueue_script( 'lightcase-touch-script', plugins_url('lightcase/vendor/jQuery/jquery.events.touch.js', __FILE__), array('jquery'), null, true );
}

add_action( 'admin_enqueue_scripts', 'admin_scripts_js' );
add_action( 'wp_enqueue_scripts', 'external_css' );
add_action( 'wp_enqueue_scripts', 'external_js' );

// Add New Gallery
require_once __DIR__ . '/inc/add-new-gallery.php';

require_once __DIR__ . '/inc/gallery-list-class.php';
function ald_gallery_index(){
	$current_user = wp_get_current_user();
	global $ald_gallery_path;
	global $ald_gallery_table;
	global $ald_image_table;
	?>
	<div class="wrap">
	<span class="alignleft"><h1>Gallery Informations <a href="<?php echo admin_url( 'admin.php?page=add-new-ald-gallery', 'http' ) ?>" class="page-title-action">Add a Gallery</a></h1></span>
  <span class="alignright"><h2>Welcome, <?php echo $current_user->display_name; ?> </h2></span>
  <?php
		if(isset($_GET['message']) ){
			if( $_GET['message'] = 'true'){
				?>
					<div id="message" class="updated notice notice-success is-dismissible"><p>Gallery deleted successfully.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
				<?php
			}
			else if( $_GET['message'] = 'false'){
				?>
					<div id="message" class="updated notice notice-error is-dismissible"><p>Sorry. gallery didn't deleted. Please try again later.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
				<?php
			}
		}
		?>
	<br>
	<?php if ( current_user_can('manage_options')){

		$galleryListTable = new Gallery_List_Table();
        $galleryListTable->prepare_items();
		$galleryListTable->display();
	?>
	</div>
	<?php
	}
}

// Delete Gallery
require_once __DIR__ . '/inc/delete-gallery.php';

add_action('admin_menu', 'register_ald_gallery_images_page');
function register_ald_gallery_images_page(){
	add_submenu_page(
    'ald-gallery',
    'Gallery Images',
    null,
    'manage_options',
    'ald-gallery-images',
    'gallery_images'
  );
}
function gallery_images()
{
	global $wpdb;
	global $ald_image_table;
	$gallery = intval( trim($_GET['gallery']) );
	if(strlen( $gallery ) > 0)
	{
		?>
			<div class="wrap">
			<h1>Gallery Images</h1>
				<?php
				if(isset($_GET['message'])){
					if( $_GET['message'] = 'true'){
						?>
							<div id="" class="updated notice notice-success is-dismissible"><p>Image added successfully.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
						<?php
					}
					else if( $_GET['message'] = 'false'){
						?>
							<div id="" class="updated notice notice-error is-dismissible"><p>Sorry. image didn't added. Please try again later.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
						<?php
					}
					else if( $_GET['message'] = 'deleted'){
						?>
							<div id="" class="updated notice notice-success is-dismissible"><p>Image deleted successfully.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
						<?php
					}
				}
				?>
			<br>
			<a href="<?php echo admin_url( 'admin.php?page=ald-gallery', 'http' ) ?>" class="button button-secondary">Back to Gallery List</a>
			<form action="<?php echo admin_url( 'admin.php' ); ?>" method="post">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">Image Name</th>
							<td><input type="text" name="imagename" placeholder="Image Name" class="regular-text" required /></td>
						</tr>
						<tr>
							<th scope="row">Images Url</th>
							<td>
								<input type="text" name="imageurl" class="regular-text" id="gallery-image" />
								<input type="button" class="button button-secondary" id="image-upload" value="Upload" required />
								<div id="gallery-image-preview">

								</div>
							</td>
						</tr>
						<tr>
							<th scope="row">Image Caption</th>
							<td><textarea name="imagetext" placeholder="Image Caption" rows="3" class="large-text code"></textarea></td>
						</tr>
					</tbody>
				</table>
				<input type="hidden" name="gallery" value="<?php echo esc_html($gallery); ?>" />
				<input type="hidden" name="action" value="add_image" />
				<input type="submit" value="Add it!" class="button button-primary" />
			</form>
			<?php if ( current_user_can('manage_options')){
				global $wpdb;
				global $ald_image_table;
				$results = $wpdb->get_results( "SELECT * FROM $ald_image_table where gallery_id=$gallery order by img_id desc");
				?>
			<table class="wp-list-table widefat fixed posts" style="margin-top: 5%; margin-bottom: 3%;">
				<thead>
					<tr>
						<th>ID</th>
						<th>Image Name</th>
						<th>Image Caption</th>
						<th>Images</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$ald_gallery_images_path = admin_url('admin.php?page=ald-gallery-images', 'http' );
				foreach ( $results as $data )
				{
					?>
					<tr>
						<td><?php echo esc_html($data->img_id); ?></td>
						<td><?php echo esc_html($data->img_name);?></td>
						<td><?php echo esc_html($data->img_text);?></td>
						<td><img src="<?php echo esc_url($data->img_url);?>" width="80px" height="50px"/></td>
						<td><a href="<?php echo admin_url( 'admin.php?action=delete_image&data='.esc_html($data->img_id), 'http' ) ?>" class="button button-primary delete">Delete</a></td>
					</tr>
				<?php
				}
			?>
				</tbody>
			</table>
			</div>
			<?php
			}
	}
}

// Add Image
require_once __DIR__ . '/inc/image-add.php';

// Delete Image
require_once __DIR__ . '/inc/image-delete.php';

// Shortcode
require_once __DIR__ . '/inc/shortcode.php';
