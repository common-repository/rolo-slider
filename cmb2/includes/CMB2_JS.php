<?php
/**
 * Handles the dependencies and enqueueing of the CMB2 JS scripts
 *
 * @category  WordPress_Plugin
 * @package   CMB2
 * @author    WebDevStudios
 * @license   GPL-2.0+
 * @link      http://webdevstudios.com
 */
class CMB2_JS {

	/**
	 * The CMB2 JS handle
	 * @var   string
	 * @since 2.0.7
	 */
	protected static $handle = 'cmb2-scripts';

	/**
	 * The CMB2 JS variable name
	 * @var   string
	 * @since 2.0.7
	 */
	protected static $js_variable = 'cmb2_l10';

	/**
	 * Array of CMB2 JS dependencies
	 * @var   array
	 * @since 2.0.7
	 */
	protected static $dependencies = array( 'jquery' => 'jquery' );

	/**
	 * Add a dependency to the array of CMB2 JS dependencies
	 * @since 2.0.7
	 * @param array|string  $dependencies Array (or string) of dependencies to add
	 */
	public static function add_dependencies( $dependencies ) {
		foreach ( (array) $dependencies as $dependency ) {
			self::$dependencies[ $dependency ] = $dependency;
		}
	}

	/**
	 * Enqueue the CMB2 JS
	 * @since  2.0.7
	 */
	public static function enqueue() {
		// Filter required script dependencies
		$dependencies = apply_filters( 'cmb2_script_dependencies', self::$dependencies );

		// if colorpicker
		if ( ! is_admin() && isset( $dependencies['wp-color-picker'] ) ) {
			self::colorpicker_frontend();
		}

		// if file/file_list
		if ( isset( $dependencies['media-editor'] ) ) {
			wp_enqueue_media();
		}

		// if timepicker
		if ( isset( $dependencies['jquery-ui-datetimepicker'] ) ) {
			wp_register_script( 'jquery-ui-datetimepicker', cmb2_utils()->url( 'js/jquery-ui-timepicker-addon.min.js' ), array( 'jquery-ui-slider' ), CMB2_VERSION );
		}

		// Only use minified files if SCRIPT_DEBUG is off
		$debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		$min   = $debug ? '' : '.min';

		// Register cmb JS
		wp_enqueue_script( self::$handle, cmb2_utils()->url( "js/cmb2{$min}.js" ), $dependencies, CMB2_VERSION, true );

		self::localize( $debug );
	}

	/**
	 * We need to register colorpicker on the front-end
	 * @since  2.0.7
	 */
	protected static function colorpicker_frontend() {
		wp_register_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), CMB2_VERSION );
		wp_register_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), array( 'iris' ), CMB2_VERSION );
		wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', array(
			'clear'         => __( 'Clear', 'rolo-slider' ),
			'defaultString' => __( 'Default', 'rolo-slider' ),
			'pick'          => __( 'Select Color', 'rolo-slider' ),
			'current'       => __( 'Current Color', 'rolo-slider' ),
		) );
	}

	/**
	 * Localize the php variables for CMB2 JS
	 * @since  2.0.7
	 */
	protected static function localize( $debug ) {
		$l10n = array(
			'ajax_nonce'       => wp_create_nonce( 'ajax_nonce' ),
			'ajaxurl'          => admin_url( '/admin-ajax.php' ),
			'script_debug'     => $debug,
			'up_arrow_class'   => 'dashicons dashicons-arrow-up-alt2',
			'down_arrow_class' => 'dashicons dashicons-arrow-down-alt2',
			'defaults'         => array(
				'color_picker' => false,
				'date_picker'  => array(
					'changeMonth'     => true,
					'changeYear'      => true,
					'dateFormat'      => _x( 'mm/dd/yy', 'Valid formatDate string for jquery-ui datepicker', 'rolo-slider' ),
					'dayNames'        => explode( ',', __( 'Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday', 'rolo-slider' ) ),
					'dayNamesMin'     => explode( ',', __( 'Su, Mo, Tu, We, Th, Fr, Sa', 'rolo-slider' ) ),
					'dayNamesShort'   => explode( ',', __( 'Sun, Mon, Tue, Wed, Thu, Fri, Sat', 'rolo-slider' ) ),
					'monthNames'      => explode( ',', __( 'January, February, March, April, May, June, July, August, September, October, November, December', 'rolo-slider' ) ),
					'monthNamesShort' => explode( ',', __( 'Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Dec', 'rolo-slider' ) ),
					'nextText'        => __( 'Next', 'rolo-slider' ),
					'prevText'        => __( 'Prev', 'rolo-slider' ),
					'currentText'     => __( 'Today', 'rolo-slider' ),
					'closeText'       => __( 'Done', 'rolo-slider' ),
					'clearText'       => __( 'Clear', 'rolo-slider' ),
				),
				'time_picker'  => array(
					'timeOnlyTitle' => __( 'Choose Time', 'rolo-slider' ),
					'timeText'      => __( 'Time', 'rolo-slider' ),
					'hourText'      => __( 'Hour', 'rolo-slider' ),
					'minuteText'    => __( 'Minute', 'rolo-slider' ),
					'secondText'    => __( 'Second', 'rolo-slider' ),
					'currentText'   => __( 'Now', 'rolo-slider' ),
					'closeText'     => __( 'Done', 'rolo-slider' ),
					'timeFormat'    => _x( 'hh:mm TT', 'Valid formatting string, as per http://trentrichardson.com/examples/timepicker/', 'rolo-slider' ),
					'controlType'   => 'select',
					'stepMinute'    => 5,
				),
			),
			'strings' => array(
				'upload_file'  => __( 'Use this file', 'rolo-slider' ),
				'remove_image' => __( 'Remove Image', 'rolo-slider' ),
				'remove_file'  => __( 'Remove', 'rolo-slider' ),
				'file'         => __( 'File:', 'rolo-slider' ),
				'download'     => __( 'Download', 'rolo-slider' ),
				'check_toggle' => __( 'Select / Deselect All', 'rolo-slider' ),
			),
		);

		wp_localize_script( self::$handle, self::$js_variable, apply_filters( 'cmb2_localized_data', $l10n ) );
	}

}
