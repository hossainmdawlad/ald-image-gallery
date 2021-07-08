<?php
function aldgallery_func( $atts ){
  global $ald_gallery_db_version;
	global $wpdb;
	global $ald_gallery_table;
	global $ald_image_table;
	$a = shortcode_atts( array(
		'limit' => '0,1000',
		'width' => '150px',
		'height' => '120px',
		'gallery' => '1',
		'slideshow' => '0',
	), $atts );
	$limit = $a['limit'];
	$width = $a['width'];
	$height = $a['height'];
	$gallery = $a['gallery'];
	$slideshow = $a['slideshow'];
	global $wpdb;
	global $ald_image_table;
	$results = $wpdb->get_results( "SELECT * FROM $ald_image_table where gallery_id = $gallery order by img_id desc limit $limit ");
	ob_start();
	?>
				<ul style="list-style-type: none; margin: 0; padding: 0; overflow: hidden; display: flex;">
				<?php foreach ( $results as $data )
					{
						$img_name = $data->img_name;
						$img_text = $data->img_text;
						$img_url = $data->img_url;
					?>
						<li style="float: left;">
							<a class="lightbox" data-rel="lightcase:gallery-<?php echo esc_attr($gallery); ?><?php if($slideshow=='1'){echo ':slideshow';} ?>" href="<?php echo esc_url($img_url); ?>" title="<?php echo esc_attr($img_name); ?>">
								<img class="img-responsive" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_text); ?>" width="<?php echo esc_attr($width); ?>" height="<?php echo esc_attr($height); ?>">
							</a>
						</li>
				<?php
					}
				?>
				</ul>
	<?php
	$output = ob_get_clean();
	return $output;
}
add_shortcode( 'aldgallery', 'aldgallery_func' );
