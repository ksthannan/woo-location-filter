<?php
add_filter( 'body_class', 'wpl_custom_class' );
function wpl_custom_class( $classes ) {
    $classes[] = 'wpl_location_filter';
	return $classes;
}

/*************************************************
## Register Location Taxonomy
*************************************************/ 

function wpl_custom_taxonomy_location()  {
    $labels = array(
        'name'                       => __('Locations', 'wpl'),
        'singular_name'              => __('Location', 'wpl'),
        'menu_name'                  => __('Locations', 'wpl'),
        'all_items'                  => __('All Locations', 'wpl'),
        'parent_item'                => __('Parent Item', 'wpl'),
        'parent_item_colon'          => __('Parent Item:', 'wpl'),
        'new_item_name'              => __('New Item Name', 'wpl'),
        'add_new_item'               => __('Add New Location', 'wpl'),
        'edit_item'                  => __('Edit Item', 'wpl'),
        'update_item'                => __('Update Item', 'wpl'),
        'separate_items_with_commas' => __('Separate Item with commas', 'wpl'),
        'search_items'               => __('Search Items', 'wpl'),
        'add_or_remove_items'        => __('Add or remove Items', 'wpl'),
        'choose_from_most_used'      => __('Choose from the most used Items', 'wpl'),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    register_taxonomy( 'location', array( 'product','shop_coupon' ), $args );
    register_taxonomy_for_object_type( 'location', array( 'product','shop_coupon' ) );


    }
    add_action( 'init', 'wpl_custom_taxonomy_location' );
    
    add_action( 'admin_head-edit-tags.php', 'wpl__remove_parent_category' );

    function wpl__remove_parent_category()
    {
        if ( 'location' != $_GET['taxonomy'] )
            return;

        $parent = 'parent()';

        if ( isset( $_GET['action'] ) )
            $parent = 'parent().parent()';

        ?>
            <script type="text/javascript">
                jQuery(document).ready(function($)
                {     
                    $('label[for=parent]').<?php echo $parent; ?>.remove();       
                });
            </script>
        <?php
    }
    
    
    /*************************************************
    ## wpl Query Vars
    *************************************************/ 
    function wpl_query_vars( $query_vars ){
        $query_vars[] = 'klb_special_query';
        return $query_vars;
    }
    add_filter( 'query_vars', 'wpl_query_vars' );
    
    /*************************************************
    ## wpl Product Query for Shortcodes
    *************************************************/ 
    function wpl_location_product_query( $query ){
        if( isset( $query->query_vars['klb_special_query'] ) && wpl_location() != 'all'){
            $tax_query[] = array(
                'taxonomy' => 'location',
                'field'    => 'slug',
                'terms'    => wpl_location(),
            );
    
            $query->set( 'tax_query', $tax_query );
        }
    }
    add_action( 'pre_get_posts', 'wpl_location_product_query' );
    
    /*************************************************
    ## wpl Location
    *************************************************/ 
    function wpl_location(){	
        $location  = isset( $_COOKIE['locationselect'] ) ? $_COOKIE['locationselect'] : 'all';
        if($location){
            return $location;
        }
    }
    

    /*************************************************
    ## wpl Location Output
    *************************************************/
    add_action('wp_footer', 'wpl_location_output'); 
    function wpl_location_output(){

        $options = get_option( 'wpl_settings' );
        
        wp_enqueue_script( 'jquery-cookie');

        wp_localize_script( 'wpl-script', 'locationfilter', array(
            'popup' => isset($options['wpl_show_popup_option']) ? $options['wpl_show_popup_option'] : '0',
            'add_location_permission' => isset($options['wpl_new_location_permission_option']) ? $options['wpl_new_location_permission_option'] : '0',
            'location_btn_str' => __('Add new location', 'wpl'),
            'choose_an_opt_str' => __('Select an option', 'wpl'),
            'choose_btn_str' => __('Choose existing location', 'wpl'),
        ));
    
        $terms = get_terms( array(
            'taxonomy' => 'location',
            'hide_empty' => false,
            'parent'    => 0,
        ) );

        $auto_popup = isset($options['wpl_show_popup_option']) && $options['wpl_show_popup_option'] == '1' ? '1' : '0';
        $btn_hide = isset($options['wpl_show_close_btn_option']) && $options['wpl_show_close_btn_option'] == '0' && $auto_popup == '1' ? 'btn_hide' : '';
    
        $output = '';
        
        $output .= '<div class="select-location">';
        $output .= '<div class="select-location-wrapper">';
        $output .= '<h6 class="entry-title">'.esc_html__('Choose your Delivery Location','wpl').'</h6>';
        $output .= '<div class="entry-description">'.esc_html__('Enter your address and we will specify the offer for your area.','wpl').'</div>';
        $output .= '<div class="close-popup '.$btn_hide.'">';
        $output .= 'X';
        $output .= '</div><!-- close-popup -->';
        $output .= '<div class="search-location">';
        $output .= '<select size="8" name="site-area" class="site-area" id="site-area" data-placeholder="'.esc_attr__('Search your area','wpl').'">';
        $output .= '<option value="all" data-min="'.esc_attr__('Clear All','wpl').'">'.esc_html__('Select a Location','wpl').'</option>';
    
        foreach ( $terms as $term ) {
            if($term->slug == wpl_location()){
                $select = 'selected';
            } else {
                $select = '';
            }
        $output .= '<option value="'.esc_attr($term->slug).'" data-min="'.esc_attr($term->description).'" '.esc_attr($select).'>'.esc_html($term->name).'</option>';
        }
        $output .= '</select>';
        $output .= '</div><!-- search-location -->';
        $output .= '</div><!-- select-location-wrapper -->';
        $output .= '<div class="location-overlay"></div>';
        $output .= '</div><!-- select-location -->';
        
        echo $output;
    }


/*************************************************
## wpl Product Tax Query
*************************************************/ 
function wpl_woocommerce_product_query_tax_query( $tax_query, $instance ) {
	
	if(taxonomy_exists('location')){
		if(wpl_location() != 'all'){
			$tax_query[] = array(
				'taxonomy' => 'location',
				'field' 	=> 'slug',
				'terms' 	=> wpl_location(),
			);
		}
	}
	
	if(isset($_GET['filter_cat'])){
		if(!empty($_GET['filter_cat'])){
			$tax_query[] = array(
				'taxonomy' => 'product_cat',
				'field' 	=> 'id',
				'terms' 	=> explode(',',$_GET['filter_cat']),
			);
		}
	}
	
    return $tax_query; 
}; 
add_filter( 'woocommerce_product_query_tax_query', 'wpl_woocommerce_product_query_tax_query', 10, 2 );


##################################################
## Edit location form into dokan edit product page.
##################################################
function wpl_doakn_product_location_edit() {
    global $post;
    $taxonomy = 'location';
    $selected_terms = wp_get_post_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
    $terms = get_terms( $taxonomy, array( 'hide_empty' => false ) );
    $options = get_option( 'wpl_settings' );
    ?>
    <div class="dokan-form-group">
        <label for="product_location"><?php esc_html_e( 'Location', 'dokan-lite' ); ?></label>

        <?php if(isset($options['wpl_new_location_permission_option']) && $options['wpl_new_location_permission_option'] == '1'): ?>
        <div id="wpl_tax_location" class="wpl_tax_location">
            <span class="wpl_btn button" id="wpl_new_location"><?php esc_html_e('Add new location', 'wpl');?></span>
        </div>
        <?php endif; ?>

        <select id="product_location" name="doakn_product_location" class="dokan-form-control">
        <option value=""><?php esc_html_e( 'Select Location', 'wpl' ); ?></option>
            <?php foreach ( $terms as $term ) : ?>
                <option value="<?php echo esc_attr( $term->term_id ); ?>" <?php selected( in_array( $term->term_id, $selected_terms ), true ); ?>><?php echo esc_html( $term->name ); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php
}
add_action( 'dokan_product_edit_after_product_tags', 'wpl_doakn_product_location_edit' );

##################################################
## Add location form into dokan add product page.
##################################################
add_action('dokan_new_product_after_product_tags', 'wpl_dokan_add_new_product_location');
function wpl_dokan_add_new_product_location(){
    $terms = get_terms( array(
        'taxonomy' => 'location',
        'hide_empty' => false,
    ) );
    $options = get_option( 'wpl_settings' );
    ?>
    <div class="dokan-form-group dokan-new-location-ui-title">
        <label for="product_location" class="form-label"><?php esc_html_e( 'Location', 'wpl' ); ?></label>

        <?php if(isset($options['wpl_new_location_permission_option']) && $options['wpl_new_location_permission_option'] == '1'): ?>
        <div id="wpl_tax_location" class="wpl_tax_location">
            <span class="wpl_btn button" id="wpl_new_location"><?php esc_html_e('Add new location', 'wpl');?></span>
        </div>
        <?php endif; ?>

        <select name="doakn_product_location" id="product_location" class="dokan-form-control">
            <option value=""><?php esc_html_e( 'Select Location', 'wpl' ); ?></option>
            <?php 
            foreach ( $terms as $term ) {
                echo '<option value="'.$term->term_id.'">'.$term->name.'</option>';
            }
             ?>
        </select>
    </div>
    <?php 
}

##################################################
## Add location into dokan store's new product.
##################################################
add_action( 'dokan_new_product_added','wpl_dokan_update_location', 10, 2 );
add_action( 'dokan_product_updated', 'wpl_dokan_update_location', 10, 2 );
function wpl_dokan_update_location($product_id, $postdata){

    if(isset($postdata['doakn_product_location']) && $postdata['doakn_product_location'] !== ''){
        $location_id = intval($postdata['doakn_product_location']);
        wp_set_object_terms( $product_id, $location_id, 'location', false );
    }

    if(isset($postdata['wpl_tax_location_field']) && $postdata['wpl_tax_location_field'] !== ''){
        $location_id = $postdata['wpl_tax_location_field'];
        wp_set_object_terms( $product_id, $location_id, 'location', false );
    }

}

##################################################
## WCFM new location store's new product.
##################################################
add_action( 'after_wcfm_products_manage_meta_save','wpl_wcfm_update_location', 10, 2 );
function wpl_wcfm_update_location($product_id, $wcfm_products_manage_form_data ){

    if(isset($wcfm_products_manage_form_data['wpl_tax_location_field']) && $wcfm_products_manage_form_data['wpl_tax_location_field'] !== ''){
        $location_id = $wcfm_products_manage_form_data['wpl_tax_location_field'];
        wp_set_object_terms( $product_id, $location_id, 'location', false );
    }
    

}













