<?php 
add_action('admin_menu', 'wpl_admin_menu');
function wpl_admin_menu(){
	$parent_slug = 'woocommerce';
    $capability = 'manage_options';
//     add_menu_page( __('WPL Woo Location Filter', 'wpl'), esc_html('WPL Woo Location Filter', 'wpl'), $capability, 'wpl-woo-location-filter', 'wpl_plugin_settings');
    add_submenu_page( $parent_slug, __('WPL Woo Location Filter', 'wpl'), esc_html('WPL Woo Location Filter', 'wpl'), $capability, 'wpl-woo-location-filter', 'wpl_plugin_settings');
    
}

function wpl_plugin_settings()
{
?>
<form method="POST" action="options.php">
<?php
    settings_fields('wpl_plugin_opt');
    do_settings_sections('wpl_plugin_opt');

    submit_button();
    ?>
</form>
<?php 
}

/***  */
add_action( 'admin_init', 'wpl_settings_init' );
function wpl_settings_init(  ) { 
    register_setting( 'wpl_plugin_opt', 'wpl_settings' );

    add_settings_section(
        'wpl_plugin_opt_section', 
        __( 'WPL Woo Location Filter Settings', 'wpl' ), 
        'wpl_settings_section_callback', 
        'wpl_plugin_opt'
    );
	
	add_settings_field( 
		'wpl_show_popup_option', 
		__( 'Enable location filter automatic popup', 'wpl' ), 
		'wpl_show_popup_option_render', 
		'wpl_plugin_opt', 
		'wpl_plugin_opt_section'
	);

	add_settings_field( 
		'wpl_show_close_btn_option', 
		__( 'Enable popup close button', 'wpl' ), 
		'wpl_show_close_btn_option_render', 
		'wpl_plugin_opt', 
		'wpl_plugin_opt_section'
	);
	add_settings_field( 
		'wpl_new_location_permission_option', 
		__( 'Add new location permission frontend', 'wpl' ), 
		'wpl_new_location_permission_option_render', 
		'wpl_plugin_opt', 
		'wpl_plugin_opt_section'
	);
	
}

function wpl_settings_section_callback(){
	echo wp_kses( __('Shortcode: ', 'wpl') . '<strong> [woo_location_filter] </strong>', 'post');
}

function wpl_show_popup_option_render(){
	$options = get_option( 'wpl_settings' );
	?>

	<select name="wpl_settings[wpl_show_popup_option]" id="show_popup" class="regular-text">
		<option value=""><?php esc_html_e('Select option', 'wpl');?></option>
		<option value="1" <?php isset($options['wpl_show_popup_option']) ? selected($options['wpl_show_popup_option'], '1', true ) : ''; ?>><?php esc_html_e('Yes', 'wpl');?></option>
		<option value="0" <?php isset($options['wpl_show_popup_option']) ? selected($options['wpl_show_popup_option'], '0', true ) : ''; ?>><?php esc_html_e('No', 'wpl');?></option>
	</select>

	<?php
}

function wpl_show_close_btn_option_render(){
	$options = get_option( 'wpl_settings' );
	?>

	<select name="wpl_settings[wpl_show_close_btn_option]" id="show_popup_close_btn" class="regular-text">
		<option value=""><?php esc_html_e('Select option', 'wpl');?></option>
		<option value="1" <?php isset($options['wpl_show_close_btn_option']) ? selected($options['wpl_show_close_btn_option'], '1', true ) : ''; ?>><?php esc_html_e('Yes', 'wpl');?></option>
		<option value="0" <?php isset($options['wpl_show_close_btn_option']) ? selected($options['wpl_show_close_btn_option'], '0', true ) : ''; ?>><?php esc_html_e('No', 'wpl');?></option>
	</select>

	<?php
}

function wpl_new_location_permission_option_render(){
	$options = get_option( 'wpl_settings' );
	?>

	<select name="wpl_settings[wpl_new_location_permission_option]" id="wpl_new_location_permission_option" class="regular-text">
		<option value=""><?php esc_html_e('Select option', 'wpl');?></option>

		<option value="1" <?php isset($options['wpl_new_location_permission_option']) ? selected($options['wpl_new_location_permission_option'], '1', true ) : ''; ?>><?php esc_html_e('Yes', 'wpl');?></option>

		<option value="0" <?php isset($options['wpl_new_location_permission_option']) ? selected($options['wpl_new_location_permission_option'], '0', true ) : ''; ?>><?php esc_html_e('No', 'wpl');?></option>
	</select>

	<?php
}


