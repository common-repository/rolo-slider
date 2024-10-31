<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       http://pressfore.com
 * @since      1.0.0
 *
 * @package    Rolo Slider
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

rolo_clean();

function rolo_clean()
{
    $args = array( 
        'post_type' => 'rolo_slider',
        'post_per_page' => -1
    );

    $slides = get_posts($args);
    foreach( $slides as $slide )
    {
        wp_delete_post( $slide->ID, true );
    }
}