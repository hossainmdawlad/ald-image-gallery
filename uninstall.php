<?php // uninstall remove options
if(!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) exit();
$option_name = 'ald_gallery_db_version';
delete_option( $option_name );
// delete custom tables
global $wpdb;
$ald_gallery_table = $wpdb->prefix . 'ald_gallery';
$ald_image_table = $wpdb->prefix . 'ald_images';
$charset_collate = $wpdb->get_charset_collate();
	$ald_gallery = "DROP TABLE IF EXISTS {$ald_gallery_table}
	) $charset_collate;";
$wpdb->query($ald_gallery);
$charset_collate = $wpdb->get_charset_collate();
	$ald_image = "DROP TABLE IF EXISTS {$ald_image_table}
	) $charset_collate;";
$wpdb->query($ald_image);
?>