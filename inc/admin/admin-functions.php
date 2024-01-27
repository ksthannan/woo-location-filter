<?php

add_filter('plugin_action_links', 'wpl_additional_link', 10, 2);

function wpl_additional_link($links, $file) {
    if ($file == plugin_basename( WPL_FILE )) {
        $settings_link = '<a href="'.admin_url("/admin.php?page=wpl-woo-location-filter").'">' . __('Settings', 'wpl') . '</a>';
        array_push($links, $settings_link);
    }
    return $links;
}

add_filter('plugin_row_meta', 'wpl_additional_link_next', 10, 2);

function wpl_additional_link_next($links, $file) {
    if ($file == plugin_basename( WPL_FILE )) {
        $settings_link = '<a href="#">' . __('Visit Website', 'wpl') . '</a>';
        array_push($links, $settings_link);
    }
    return $links;
}
