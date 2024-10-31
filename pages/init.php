<?php
/*
 Custom dashboard that will be opened on install, or update
*/

class Rolo_Pages {
 
	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/
 
	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() { 

		add_action('admin_menu', array( $this,'rolo_register_menu') );
 
	} // end constructor
 
	
	function rolo_register_menu() {
		add_submenu_page( 'edit.php?post_type=rolo_slider', __( 'About', 'rolo-slider' ), __( 'About', 'rolo-slider' ), 'read', 'about', array( $this,'about') );
		add_submenu_page( 'edit.php?post_type=rolo_slider', __( 'Addons', 'rolo-slider' ), __( 'Addons', 'rolo-slider' ), 'read', 'addons', array( $this,'addons') );
		add_submenu_page( 'edit.php?post_type=rolo_slider', __( 'Demo Data', 'rolo-slider' ), __( 'Demo Data', 'rolo-slider' ), 'read', 'demo-data', array( $this,'demo') );
		add_submenu_page( 'edit.php?post_type=rolo_slider', __( 'Import', 'rolo-slider' ), __( 'Import', 'rolo-slider' ), 'read', 'import', array( $this,'import') );
		add_submenu_page( 'edit.php?post_type=rolo_slider', __( 'Export', 'rolo-slider' ), __( 'Export', 'rolo-slider' ), 'read', 'export', array( $this,'export') );
	}
	
	function about() {
		include_once( 'dashboard.php'  );
	}

	function addons() {
		include_once( 'addons.php'  );
	}

	function demo() {
		include_once( 'demo-data.php'  );
		new DemoData();
	}

	function import() {
		include_once( 'import-page.php'  );
		new ImportPage();
	}
    
    function export() {
		include_once( 'export-page.php'  );
		new RoloExportPage();
	}
}