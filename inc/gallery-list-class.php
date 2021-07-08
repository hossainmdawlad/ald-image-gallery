<?php
/**
 * Create a new table class that will extend the WP_List_Table
 */
class Gallery_List_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );

        $perPage = 5;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'g_name'          => 'Gallery Name',
            'g_shortcode'       => 'Shortcode',
            'g_description' => 'Gallery Description',
            'g_images'        => 'Gallery Images',
            'g_delete'    => 'Delete'
        );

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('g_name' => array('g_name', false));
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        $data = array();
		global $wpdb;
		global $ald_gallery_path;
		global $ald_gallery_table;
		global $ald_image_table;
		$ald_gallery_images_path = admin_url('admin.php?page=ald-gallery-images', 'http' );
		$results = $wpdb->get_results( "SELECT * FROM $ald_gallery_table order by gallery_id desc");

		foreach ( $results as $gallery )
		{
      $shortcode = addslashes('[aldgallery gallery=&quot;'.esc_html($gallery->gallery_id) .'&quot; width=&quot;150px&quot; height=&quot;120px&quot; limit=&quot;10&quot; slideshow=&quot;1&quot;]');;
			$data[] = array(
				'g_name'        => esc_html($gallery->gallery_name),
				'g_shortcode'   => '<textarea onfocus="this.select();" readonly="readonly" class="large-text code" id="s-'.esc_html($gallery->gallery_name).'">'.$shortcode.'</textarea>',
				'g_description' => esc_textarea($gallery->gallery_text),
				'g_images'      => '<a href="'. esc_html($ald_gallery_images_path).'&gallery='.esc_html($gallery->gallery_id).'">Add/ Edit</a>',
				'g_delete'      => '<a href="'. admin_url( 'admin.php?action=delete_gallery&data='.esc_html($gallery->gallery_id), 'http' ) .'">Delete</a>'
			);
		}
        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'g_name':
            case 'g_shortcode':
            case 'g_description':
            case 'g_images':
            case 'g_delete':
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'g_name';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }


        $result = strcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }

        return -$result;
    }
}
