<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if this file is accessed directly

if( !class_exists( 'Rolo_Slider' ) )
{
    class Rolo_Slider
    {
    	static $version = ROLO_VERSION;

    	/**
    	* Rolo Slider Hooks()
    	*/
    	protected static function hooks()
    	{
    		//enqueue front-end scripts and styles	
    		add_action( 'wp_enqueue_scripts', array( 'Rolo_Slider', 'enqueue_scripts' ) );
    		//enqueue back-end scripts and styles
    		add_action( 'admin_enqueue_scripts', array( 'Rolo_Slider', 'admin_enqueue_scripts' ) );
    		//	Activation
			register_activation_hook( __FILE__,	array( 'Rolo_Slider', 'rolo_activate' ) );
			//	Deactivation
			register_deactivation_hook( __FILE__, array( 'Rolo_Slider', 'rolo_deactivate' ) );
			// unistall
			register_uninstall_hook( __FILE__, array( 'Rolo_Slider', 'rolo_unistall' ) ); 
			//register zenith_slider post_type
			add_action( 'init', array( 'Rolo_Slider', 'rolo_register' ) );
			add_filter( 'mce_buttons', array( 'Rolo_Slider', 'register_button' ) );
			add_filter( 'mce_external_plugins', array( 'Rolo_Slider', 'add_button_js' ) );
			add_action( 'admin_head', array( 'Rolo_Slider', 'collect_sliders' ) );
    	}

   		/**
	    * Enqueue scripts and styles
	    *
	    */
	    public static function enqueue_scripts()
	    {
	    	wp_enqueue_style( 'rolo', ROLO_DIR . 'assets/css/rolo.css', self::$version );
	    	wp_enqueue_script( 'owl-carousel', ROLO_DIR . 'assets/js/owl.carousel.min.js', array(), self::$version, true );
	    	wp_enqueue_script( 'rolo', ROLO_DIR . 'assets/js/rolo.js', array(), self::$version, true );
	    }

	    /**
	    * Flush Rewrite rules in activation/deactivation hooks
	    *
	    */	
	    public static function rolo_activate(){
	    	flush_rewrite_rules();
	 	}
		public static function rolo_deactivate(){
		    flush_rewrite_rules();
		}
	    public static function rolo_unistall(){ }

	    /**
	    * Enqueue admin scripts and styles
	    *
	    */
	    public static function admin_enqueue_scripts()
	    {
	       $screen = get_current_screen();
	       if( 'rolo_slider' === $screen->post_type )	
	       {
	       	   wp_enqueue_style('wp-color-picker');
		       wp_enqueue_style( 'rolo-css-admin', ROLO_DIR . 'assets/css/admin.css', self::$version );
			   wp_enqueue_script( 'rolo-vendor-js', ROLO_DIR . 'assets/js/vendor.js', array('jquery'), self::$version, true );
		       wp_enqueue_script( 'rolo-admin', ROLO_DIR . 'assets/js/admin.js', array('jquery', 'wp-color-picker'), self::$version, true );
		       $url = array(
					'base' => ROLO_DIR
				);
		       wp_localize_script( 'rolo-admin', 'obj', $url );
		   }
	    }

	   /**
      * Register Rolo Slider button
      * to tinyMCE buttons
      *
      * @since   1.4.5
      */
      public static function register_button($buttons)
      { 
        global $current_screen;

        if( is_admin() )
          array_push( $buttons, 'rl_slider' );

        return $buttons;
      }


     /**
      * Add script callback to Rolo Slider
      * shortcode button in tinyMCE editor
      *
      * @since   1.4.5
      */
      public static function add_button_js($plugins)
      { 
        if( is_admin() ) {
	        $plugins['rl_slider'] = ROLO_DIR . 'assets/js/shortcode.js';
        }

        return $plugins;
      }


     /**
      * Collect Stats for inclusion
      * into shortcode selection.
      *
      * @since   1.4.5
      */
      public static function collect_sliders(){
        $args = array(
          'post_type' => 'rolo_slider',
          'posts_per_page' => -1
        );
         $sliders = get_posts($args);
         ?>
         <script type="text/javascript">
           var names = {}; 
           <?php foreach( $sliders as $slider ): ?>
           names['<?php echo $slider->post_name; ?>'] = ['<?php echo $slider->post_name; ?>'];
           <?php endforeach; ?>  
         </script>
         <?php 
       } 

	    /**
	    *
	    * register rolo_slider custom post type
	    *
	    */
	    public static function rolo_register()
	    {
	  		$labels = array(
	  			'name'	 => 'Rolo Slider',
	  			'singular_name' => __( 'Slide', 'rolo-slider' ),
	  			'plural_name'  => __( 'Slides', 'rolo-slider' ),
	  			'add_new'     => __('Add Slider', 'rolo-slider'),
	            'add_new_item'    => __('Add Slide', 'rolo-slider'),
	            'new_item'      => __('New Slide', 'rolo-slider'),
	            'edit_item'     => __('Edit Slider', 'rolo-slider'),
	            'all_items'     => __('All Sliders', 'rolo-slider'),
	            'view_item'     => __('View Slider', 'rolo-slider'),
	            'not_found'     => __('No Slider found'),
	            'not_found_in_trash'  => __('No Slider found in trash', 'rolo-slider'),
	  		);

	  		register_post_type( 
	  			'rolo_slider', array( 
	  				'labels' => $labels,
	  				'public'  => false,
	  				'supports' => array('title'),
	  				'rewrite' => false,
	  				'publicly_queriable' => true, 
					'show_ui' => true, 
					'exclude_from_search' => true,  
					'show_in_nav_menus' => false,  
					'has_archive' => false,
	  				'menu_icon' => 'dashicons-slides',
	  				'menu_position'  => 65
	  			)
	  		);	
	    }

	    public static function init()
	    {
	    	self::hooks();
	    }

    }//end Rolo_Slider class
}//if !class_exists
