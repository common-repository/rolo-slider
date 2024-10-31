<?php
namespace RoloOptions;

Class Init
{
	/*
	* The field prefix
	* that will be added to settings 
	* field slug
	*/
	private $pre;

	/*
	* The options name
	*/
	private $temp_ops;

	/*
	* The sections array
	*/
	private $sections;

	/*
	* The field instance array
	*/
	private $field;

	public function __construct($name)
	{
		$this->opname = $name;
		$this->pre = $this->get_prefix($name);
		$this->sections = array();
		$this->field = new \RoloOptions\Fields();

		$this->hooks();
	}

	/**
	* Register hooks
	*
	* @since 1.0.0
	*
	*/
	public function hooks() 
	{
		add_action( 'add_meta_boxes', array( $this, 'sections_init' ) );
		add_action( 'save_post',  array( $this, 'rolo_save_meta' ) );
        add_action( 'save_post',  array( $this, 'options_refresh' ) );
	}

	/**
	 * custom option and settings
	 */
	public function sections_init()
	{
		# Sections
		$sections = $this->sections();

		# Add sections
		foreach( $sections as $section ) {
			$name 		   = $section->slug;
			$section_title = $this->section_title($section);

			add_meta_box(
				$this->pre.$name,
				$section_title,
				array( $section, 'callback' ),
				'rolo_slider',
				'side',
				'high'
			);

            add_action("rolo_{$name}_cb", array($this, 'settings_init'), 10, 1);
		}
	}
	 
	/**
	 * custom option and settings
	 */
	public function settings_init($section)
	{
		# Options / Setting fields
		$options = $this->options();

        foreach( $options as $option ) {

            if( isset($option['section']) && $option['section'] === $section ) {
				if( !isset( $option['done'] ) ) {
					$option['done'] = 1;

					$this->add_field($option);
				}
            }
        }
	}

	/**
	 * Generates section markup
	 */
	public function section_title($section)
	{
		$title = $section->section();

		return $title;
	}

	/**
	 * Extract the sections from options
	 * and collect them into single array
	 */
	public function sections()
	{
	    $pre = $this->pre;
	    $sections = array();

		/**
		* Sections filter, that is 
		* used for hooking the option sections 
		*
		* @since 1.0.0
		*
		*/
		$sections = apply_filters( $pre.'options_sections', $sections );

		return $sections;
	}

	/**
	 * Collect the options into array
	 * for the callback in settings
	 */
	public function options()
	{
		# data
		$data = array();
		$pre = $this->pre;
        $stored = get_site_transient('rolo_temp_ops');

        if( $stored === false ) {
            /**
             * Setings fields filter, that is
             * used for hooking the plugin options
             *
             * @since 1.0.0
             *
             */
            $data = apply_filters( $pre.'options_data', $data );

            $this->temp_ops = $data;

            set_site_transient('rolo_temp_ops', $data, 60*60*7);
        } else {
            $data = $stored;
        }

		return $data;
	}

    /**
     * Clear transient
     */
    public function options_refresh()
    {
        delete_site_transient('rolo_temp_ops');
    }

	/**
	 * Add settings field cb function
	 */
	public function add_field($args)
	{
		$fields = $this->field;

		if( ! isset($args['type']) || empty($args['type']) ) {
			$args['type'] = 'text';
		}

		$field = $args['type'];

		$html = $fields->$field($args);

		echo $html;
	}

	/**
	 * Get prefix from the options
	 */
	public function get_prefix($name)
	{
		$pre = $name.'_';

		return $pre;
	}

	/**
	 * Saving the metaboxes
	 */
	function rolo_save_meta($post_id)
	{
		if(defined('DOING_AUTOSAVE') && 'DOING_AUTOSAVE') {
			return $post_id;
		}
		global $post;
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		$options = $this->options();
        if( empty($options) ) {
            $options = $this->temp_ops;
        }

		foreach( $options as $option ) {
			$key = $option['name'];

			if( isset( $_POST[$key] ) ) {
				$value = $_POST[$key];
				$current_value = get_post_meta($post_id, $key, true);

				if( $value && '' == $current_value ){
					add_post_meta( $post_id, $key, $value, true );
				}
				elseif( $value && '' != $current_value ){
					update_post_meta( $post_id, $key, $value );
				}
				elseif ( '' == $value &&  $current_value ){
					delete_post_meta( $post_id, $key, $current_value );
				}
			}
		}
	}
}