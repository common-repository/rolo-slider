<?php
/**
  * Plugin Name: Rolo Slider
  * Plugin URI: http://pressfore.com
  * Version: 1.0.9
  * Author: PressFore
  * Author URI: http://pressfore.com
  * Description: Very Simple to use images slider for WordPress
  * Text Domain: rolo-slider
  * Domain Path: /languages
  * License: GPL
  */
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	if ( ! defined( 'ROLO_DIR' ) ) {
		define( 'ROLO_DIR', plugin_dir_url( __FILE__ ) );
	}

	if ( ! defined( 'ROLO_ASSETS_URL' ) ) {
		define( 'ROLO_ASSETS_URL', plugin_dir_url( __FILE__ ).'assets/' );
	}

	if ( ! defined('ROLO_VERSION') ){
		define( 'ROLO_VERSION', '1.0.9' );
	}

	if( !defined('PF_STORE_URL') ) {
		define('PF_STORE_URL', 'http://pressfore.com');
	}

	if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
		require_once dirname( __FILE__ ) . '/cmb2/init.php';
	} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
		require_once dirname( __FILE__ ) . '/CMB2/init.php';
	}

	//include core files
	require_once 'options/init.php';
	require_once 'export/init.php';
	require_once 'import/init.php';
	require_once 'demo/init.php';
	require_once 'pages/init.php';
	require_once 'core/color-field.php';
	require_once 'core/setup.php';
	require_once 'core/metaboxes.php';
	require_once 'core/interface.php';
	require_once 'core/shortcode.php';
	require_once 'core/license.php';

	//initialize Rolo Slider
	Rolo_Slider::init();
	Rolo_Metaboxes::init();
	Rolo_Interface::init();

	if( is_admin() ) {
		$pages = new Rolo_Pages;
	}

	$shortcode = new Rolo_Shortcode;
