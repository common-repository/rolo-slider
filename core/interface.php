<?php

if( !class_exists( 'Rolo_Interface' ) )
{
    class Rolo_Interface
    {

    	protected static function hooks()
    	{
    		add_action( 'admin_menu', array( 'Rolo_Interface', 'zt_replace_submit_box' ) );
			add_filter( "manage_edit-rolo_slider_columns", array( 'Rolo_Interface', "rolo_post_columns" ) );
			add_action( "manage_rolo_slider_posts_custom_column", array( 'Rolo_Interface', "rolo_custom_columns" ), 10, 2 );
            add_filter('enter_title_here', array( 'Rolo_Interface','change_rolo_title' ) );
            add_filter('plugin_row_meta',  array( 'Rolo_Interface','links' ), 10, 2);
    	}

    	/**
    	* Replacing Default Submit Meta Box
    	*
    	*/
    	public static function zt_replace_submit_box()
    	{
    		remove_meta_box( 'submitdiv', 'rolo_slider', 'core' );
    		add_meta_box( 'submitdiv', 'Save Slider', array( 'Rolo_Interface', 'rolo_submit' ), 'rolo_slider', 'side', 'low' );
            add_meta_box( 'upper_box', 'Info', array( 'Rolo_Interface', 'upper_box' ), 'rolo_slider', 'side', 'high' );
    	}

    	/**
    	* custom edit of default wordpress publish box (callback function) 
		* code edited from wordpress/includes/metaboxes.php
		*
		* @global $action, $post
		* @since 1.0
		*/
    	public static function rolo_submit()
    	{
    	   global $action, $post;
	 
		   $post_type = $post->post_type;
		   $post_type_object = get_post_type_object($post_type);
		   $can_publish = current_user_can($post_type_object->cap->publish_posts);
		   ?>
		   <div class="submitbox" id="submitpost">
		   <div id="major-publishing-actions">
		   <?php
		   do_action( 'post_submitbox_start' );
		   ?>
		   <div id="delete-action">
		   <?php
		   if ( current_user_can( "delete_post", $post->ID ) ) {
		     if ( !EMPTY_TRASH_DAYS )
		          $delete_text = __('Delete Permanently');
		     else
		          $delete_text = __('Move to Trash');
		   ?>
		   <a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php _e('Delete Slider'); ?></a><?php
		   } //if ?>
		   </div>
		   <div id="publishing-action">
		   <span class="spinner"></span>
		   <?php
		   if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) {
		        if ( $can_publish ) : ?>
		          <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Save Slider') ?>" />
		          <?php submit_button( __( 'Save Slider' ), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ) ); ?>
		   <?php   
		        endif; 
		   } else { ?>
		          <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Update Slider') ?>" />
		          <input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php esc_attr_e('Update Slider') ?>" />
		   <?php
		   } //if ?>
		   </div>
		   <div class="clear"></div>
		   </div>
		   </div>
		  <?php
    	}

        /**
        * Add documentation link
        *
        * @since 1.0
        */
        public static function upper_box()
        {
            $html = '<a class="upper-docs upper-info" target="_BLANK" href="http://docs.pressfore.com/rolo-slider/"><i class="dashicons dashicons-book"></i> Online Documentation</a>';
			$html .= '<a class="upper-docs upper-info" target="_BLANK" href="http://pressfore.com/item-category/addons/"><i class="dashicons dashicons-cart"></i> Addons</a>';
            $html .= '<a class="upper-prop upper-info" target="_BLANK" href="http://pressfore.com/feature-proposal/"><i class="dashicons dashicons-welcome-add-page"></i> Propose a feature</a>';

            echo $html;
        }

    	/**
    	* Add Custom Columns to Rolo Slider
    	* post edit screen
    	*
    	*/
    	public static function rolo_post_columns($cols)
    	{
    		$cols = array(
    			'cb' => '<input type="checkbox" />',
    			'title' => __('Slider Name', 'rolo-slider'),
    			'layout' => __('Layout', 'rolo-slider'),
    			'shortcode' => __('Shortcode', 'rolo-slider')
    		);
    		return $cols;
    	}

    	//custom columns callback
    	public static function rolo_custom_columns( $column, $post_id )
    	{
    		switch( $column )
    		{
    			case 'layout':
    			  $layout = get_post_meta( $post_id, 'rolo_layout', true );	
    			  echo isset($layout[0]) ? $layout[0] : esc_html('Slider', 'rolo-slider');
    			break;
    			case 'shortcode':
    			  global $post;
				  $name = $post->post_name;
			      $shortcode = '<span style="border: solid 2px cornflowerblue; background:#fafafa; padding:2px 7px 5px; font-size:17px; line-height:40px;">[rolo_slider name="'.$name.'"]</strong>';
				  echo $shortcode; 
    			break;
    		}
    	}


        /**
        * Add custom placeholder to the post title
        * field at post edit screen
        *
        * @param $title
        * @return string
        * @since 1.0
        */
        public static function change_rolo_title( $title )
        {
            $screen = get_current_screen();
            if( $screen->post_type == 'rolo_slider'){
                return 'Enter the slider name';
            }
        }

        /**
        * Add links in the plugin screen
        *
        * @param $links, $file
        * @return array
        * @since 1.0
        */
        public static function links($links, $file) 
        {
         $base = dirname(ROLO_DIR).'/'; 
         $base = str_replace($base, '',ROLO_DIR).'init.php';
         if ($file == $base) {
               $links[] = '<a href="http://docs.pressfore.com/rolo-slider/">' .'<i class="dashicons dashicons-book"></i>'. __('Documentation') . '</a>';
         }

         return $links;
        }

       
    	public static function init()
    	{
    		self::hooks();
    	}

    }//end Rolo_Slider class
}//if !class_exists
