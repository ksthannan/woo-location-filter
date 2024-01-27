<?php 
add_shortcode('woo_location_filter', 'wpl_woo_location_filter');
function wpl_woo_location_filter($atts, $content = null){
    $a = shortcode_atts( array(
		'location' => 'product'
	), $atts );
    ob_start();
    $options = get_option( 'wpl_settings' );
    ?>
    <div class="header-location site-location">
        <a href="#">
            <span class="location-description"><?php esc_html_e('Your Location','wpl'); ?></span>
            <?php if(wpl_location() == 'all'){ ?>
                <div class="current-location"><?php esc_html_e('Select a Location','wpl'); ?></div>
            <?php } else { ?>
                <div class="current-location activated"><?php echo esc_html(wpl_location()); ?></div>
            <?php } ?>
        </a>
    </div>
    <?php 
    $contents = ob_get_clean();
    return $contents;
}






















