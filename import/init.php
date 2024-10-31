<?php
/**
 * Init The Importer
 */

# Load Importer API
require_once ABSPATH . 'wp-admin/includes/import.php';

if ( ! class_exists( 'WP_Importer' ) ) {
    $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
    if ( file_exists( $class_wp_importer ) )
        require $class_wp_importer;
}

# Load parsers
require_once 'import-hooks.php';
require_once 'parsers.php';
require_once 'importer.php';

use \PressForeImporter\Importer as Importer;
use \PressForeImporter\functions as hooks;

new hooks();

$importer = new Importer();
$importer->prefix = 'rolo';

function pf_importer_get_instance()
{
    global $importer;

    return $importer;
}

function rolo_import_page_output()
{
    global $importer;

    $importer->page_output();
}