<?php
/**
 * File which contains initialization
 * calls for exporter
 */

require_once 'export-hooks.php';
require_once 'export-xml.php';
require_once 'exporter.php';

use \PressforeExporter\Exporter as Exporter;
use \PressforeExporter\functions as hooks;

# Init main hooks
new hooks;

# Define args for Exporter classs
$post_type = 'rolo_slider';
$prefix    = 'rolo';

# Init Exporter Class
$exporter  = new Exporter($prefix);

$exporter->post_type = $post_type;

/**
 * Wrapper function for page html markup
 */
function rolo_export_page_output() {
    global $exporter;

    $exporter->export_page_output();
}

