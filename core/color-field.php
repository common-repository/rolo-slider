<?php

/**
 * Class Rolo_Color_Field
 */
class Rolo_Color_Field {

	/**
	 * Current version number
	 */
	const VERSION = '1.0.3';

	/**
	 * Initialize the plugin by hooking into CMB2
	 */
	public function __construct() {
		add_action( 'cmb2_render_color_alpha', array( $this, 'render' ), 10, 5 );
		add_action( 'cmb2_sanitize_color_alpha', array( $this, 'sanitize' ), 10, 2 );
	}

	/**
	 * Render field
	 */
	public function render( $field, $value, $object_id, $object_type, $field_type ) {
		$field_name = $field->_name();
		
		echo $field_type->input( array( 
			'type' 			=> 'text',
			'name' 			=> $field_name,
			'id'			=> $field_name,
			'class'			=> 'color',
			'value' 		=> $value,
			'desc'			=> false
		) );

		$field_type->_desc( true, true );
	}

	/**
	 * Optionally save the latitude/longitude values into two custom fields
	 */
	public function sanitize( $override_value, $value) {
		return $value;
	}
}
new Rolo_Color_Field();
