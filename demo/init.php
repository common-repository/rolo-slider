<?php
/**
 * File which contains initialization
 * calls for exporter
 */

require_once 'demo-data.php';

use \PressforeExporter\DemoData as DemoContent;

# Init main hooks
$demo = new DemoContent;

/**
 * Wrapper function for page html markup
 */
function rolo_demo_page_output() {
    global $demo;

    $demo->page_html();
}

