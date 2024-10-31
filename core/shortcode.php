<?php

if( !class_exists( 'Rolo_Shortcode' ) )
{
	class Rolo_Shortcode
	{

		// global $style that will hold css
		public $style;

		function __construct()
		{
			add_shortcode( 'rolo_slider', array( $this, 'rolo_shortcode' ) );
	        add_action('wp_footer', array($this, 'print_styles') );
		}

		public function rolo_shortcode( $atts )
		{
        /**
          * Call post by name extracting the $name
          * from the shortcode previously created
          * in custom post column
          */
          extract( shortcode_atts( array(
          		 'name'	=> ''
          	), $atts )
          );
          
          $args = array('post_type' => 'rolo_slider', 'name' => $name);
          $slides = get_posts( $args );
          $html = '';
	      $n = 0;
          $ID = $name;

          foreach( $slides as $slide ) {
          	setup_postdata($slide);

	        //collect option values 
	        $layout = get_post_meta( $slide->ID, '_rl_layout', true );
			$forcew = get_post_meta( $slide->ID, '_rl_force_width', true );
	        $iconStyle = get_post_meta( $slide->ID, '_rl_icstyle', true );
	        $slide_data = get_post_meta( $slide->ID, '_rl_slide', true );
            $imgCheck = 0;

			$rolo_id = 'rolo_'. $slide->ID. '_'. rand(0,10);
			$wrapper_unique = rand(0,10);

            if( !isset( $layout[0] ) ) $layout[0] = 'default';

			$data = $this->slider_data($slide);
        	$css = $this->slider_css($slide->ID, $rolo_id, $wrapper_unique);
			$addon_style = $this->addon_styles($rolo_id, $slide);
			$css .= $addon_style;

	        $this->style .= $css; 
			
			$wrapperCls = apply_filters( 'rolo_wrapper_classes', array('rolo_wrapper', $layout[0]), $slide->ID );
			$wrapperCls[] = 'rolo_wrapper'.$wrapper_unique;
			if( '' == $forcew || 'yes' == $forcew ) $wrapperCls[] = 'force-width';
			$wrapperCls = implode( ' ', $wrapperCls );

			$html .= '<div class="'.$wrapperCls.'"><div id="'. $rolo_id .'" class="owl-carousel rolo_slider clearfix '.$iconStyle.'" '.$data.'>';

	        /**
	        * Check for layout, and then call appropriate markup
	        *
	        */
	        foreach( $slide_data as $key => $value ) {
              $image = $this->value_check( $value,'_rl_screen' );
			  $class = array();
			  $css = '';

			  $data  = $this->slide_data($value);

			  if( 'default' === $layout[0] ) {
                  $class[] = 'item';
                  $class   = apply_filters('rolo_slide_class', $class, $value, $layout);
				  $css     = apply_filters('rolo_slide_additional_css', '', $rolo_id, $value, $layout);

				  $img = '<div class="slide-img" style="background-image: url('.$image.')" '.$data.'></div>';

				  $meta = $this->slide_meta($value);

				  // output
				  $html .= '<div class="'.implode(' ', $class).'">';
				  $html .= $img;
				  $html .= $meta;
				  $html .= '</div>';
			  } else if( 'images' === $layout[0] && 0 == $imgCheck ) {
			  	$images = get_post_meta( $slide->ID, '_rl_responsive', true );
			  	$imgCheck++;

			  	foreach( (array) $images as $id => $url ) {
					$class[] = 'item';
					$class = apply_filters('rolo_slide_class', $class, $id, $layout);
					$css   = apply_filters('rolo_slide_additional_css', '', $rolo_id, $id, $layout);
		        	$alt   = get_post_meta( $id, '_wp_attachment_image_alt', true);

		          	$html .= '<div class="'.implode(' ', $class).'">';
	                $html .=  '<img alt="'.$alt.'" class="slide-img" src="'.esc_url($url).'"/>';
	                $html .= '</div>';
		        }//foreach()
			  }

			  $this->style .= $css;
			}

			ob_start();
			do_action('rolo_slider_after_markup', $slide->ID, $layout);
			$html .= ob_get_clean();

			$this->style .= apply_filters('rolo_slider_after_loop_custom_css', '', $rolo_id, $slide->ID, $layout);

			$html .= '</div></div>';

	      }
          
          return $html;
        }

        /**
         * This function returns slider data
         *
         * @since 1.0.0
         */
        function slider_data($slide)
        {
			$animation = get_post_meta( $slide->ID, '_rl_animation', true );
			$autoplay = get_post_meta( $slide->ID, '_rl_autoplay', true );
			$autDelay = get_post_meta( $slide->ID, '_rl_delay', true );
			$slideSpeed = get_post_meta( $slide->ID, '_rl_speed', true );
			$bar = get_post_meta( $slide->ID, '_rl_bar', true );
			$scroll = get_post_meta( $slide->ID, '_rl_scr', true );
			$atps = get_post_meta( $slide->ID, '_rl_autst', true );
			$forcew = get_post_meta( $slide->ID, '_rl_force_width', true );
			$sliderHeight = get_post_meta( $slide->ID, '_rl_height', true );
			$bullets = get_post_meta( $slide->ID, '_rl_bullets', true );

			$data = 'data-anm="'.$animation.'"';
			$data .= ' data-hgh="'.$sliderHeight.'"';
			$data .= ' data-slsp="'.$slideSpeed.'"';
			$data .= ' data-autp="'.$autoplay.'"';
			$data .= ' data-autps="'.$autDelay.'"';
			$data .= ' data-hsb="'.$bar.'"';
			$data .= ' data-scr="'.$scroll.'"';
			$data .= ' data-bul="'.$bullets.'"';
			$data .= ' data-autst="'.$atps.'"';
			$data .= ' data-fw="'.$forcew.'"';

			$data .= apply_filters('rolo_slider_slider_data', '', $slide->ID);

        	return $data;
        }

		/**
		 * This function returns slider css
		 *
		 * @since 1.0.0
		 */
		function slider_css($id, $rolo_id, $wrapper_unique)
		{
			$iconColor = get_post_meta( $id, '_rl_iclr', true );
			$iconBck = get_post_meta( $id, '_rl_ibck', true );
			$iconAC = get_post_meta( $id, '_rl_iconac', true );
			$iconAB = get_post_meta( $id, '_rl_iconab', true );
			$bulletsC = get_post_meta( $id, '_rl_bulletsc', true );
			$bulletsAC = get_post_meta( $id, '_rl_bulletsac', true );
			$arrows = get_post_meta( $id, '_rl_arrows', true );
			$hgh_vals = get_post_meta( $id, '_rl_highlight', true );
			$mouse = get_post_meta( $id, '_rl_mousec', true );
			$captions_bg = get_post_meta( $id, '_rl_captions_bg', true );
			$captions_txt = get_post_meta( $id, '_rl_captions_txt', true );
			$btn_1_txt = get_post_meta( $id, '_rl_btn_1_txt', true );
			$btn_1_bg = get_post_meta( $id, '_rl_btn_1_bg', true );
			$btn_1_htxt = get_post_meta( $id, '_rl_btn_1_htxt', true );
			$btn_1_hbg = get_post_meta( $id, '_rl_btn_1_hbg', true );
			$btn_2_txt = get_post_meta( $id, '_rl_btn_2_txt', true );
			$btn_2_bg = get_post_meta( $id, '_rl_btn_2_bg', true );
			$btn_2_htxt = get_post_meta( $id, '_rl_btn_2_htxt', true );
			$btn_2_hbg = get_post_meta( $id, '_rl_btn_2_hbg', true );
			$sliderHeight = get_post_meta( $id, '_rl_height', true );

			$css  = '#'.$rolo_id.'.rolo_slider .slider-arrow span:after {border-color: '.$iconColor.'}';
			$css .= '#'.$rolo_id.'.rolo_slider .slider-arrow {background-color: '.$iconBck.'}';
			$css .= '#'.$rolo_id.'.rolo_slider .slider-arrow:hover span:after {border-color: '.$iconAC.'}';
			$css .= '#'.$rolo_id.'.rolo_slider .slider-arrow:hover {background-color: '.$iconAB.'}';
			$css .= '#'.$rolo_id.'.rolo_slider .owl-controls .owl-page span {background-color: '.$bulletsC.'}';
			$css .= '#'.$rolo_id.'.owl-theme .owl-controls .owl-page.active span, .owl-theme .owl-controls.clickable .owl-page:hover span {background-color: '.$bulletsAC.'}';
			$css .= '.rolo_wrapper'.$wrapper_unique.'.rolo_wrapper:not(.images) {height: '.$sliderHeight.'px}';
			$css .= '.rolo_wrapper'.$wrapper_unique.'.rolo_wrapper:not(.images) #'.$rolo_id.'.owl-carousel .slide-img{height: '.$sliderHeight.'px}';
			$css .= '#'.$rolo_id.'.slider-scrolling {border-color: '.$mouse.'} .slider-scrolling::after{background: '.$mouse.'}';
			$css .= '#'.$rolo_id.' .has-caption .slider-meta-wrap h3, #'.$rolo_id.' .has-caption .slider-meta-wrap h4, #'.$rolo_id.' .has-caption .slider-meta-wrap .slide-desc {background-color: '.$captions_bg.'}';
			$css .= '#'.$rolo_id.' .has-caption .slider-meta-wrap h3, #'.$rolo_id.' .has-caption .slider-meta-wrap h4, #'.$rolo_id.' .has-caption .slider-meta-wrap .slide-desc {color: '.$captions_txt.'}';
			$css .= '#'.$rolo_id.' .slider-meta-wrap .slider-buttons a:last-child {background-color: '.$btn_2_bg.'}';
			$css .= '#'.$rolo_id.' .slider-meta-wrap .slider-buttons a:last-child {color: '.$btn_2_txt.'}';
			$css .= '#'.$rolo_id.' .slider-meta-wrap .slider-buttons a:first-child {background-color: '.$btn_1_bg.'}';
			$css .= '#'.$rolo_id.' .slider-meta-wrap .slider-buttons a:first-child {color: '.$btn_1_txt.'}';
			$css .= '#'.$rolo_id.' .slider-meta-wrap .slider-buttons a:first-child:hover {color: '.$btn_1_htxt.'}';
			$css .= '#'.$rolo_id.' .slider-meta-wrap .slider-buttons a:first-child:hover {background-color: '.$btn_1_hbg.'}';
			$css .= '#'.$rolo_id.' .slider-meta-wrap .slider-buttons a:last-child:hover {color: '.$btn_2_htxt.'}';
			$css .= '#'.$rolo_id.' .slider-meta-wrap .slider-buttons a:last-child:hover {background-color: '.$btn_2_hbg.'}';

			$css .= apply_filters('rolo_slider_css', '', $rolo_id);

			return $css;
		}

		/**
		 * Additional Addon styles
		 *
		 * @since 1.0.0
		 */
		public function addon_styles($rolo_id, $slide)
		{
			$addon_style = apply_filters( 'slider_additional_styles', '' );
			$css = '';

			if( $addon_style ) {
				foreach( $addon_style as $style ) {
                    $css .= $this->organize_styles($style, $rolo_id, $slide);
				}
			}

			return $css;
		}

        /**
         * Style organizer
         *
         * @since 1.0.0
         */
        function organize_styles($style, $rolo_id, $slide)
        {
            $selector = $style['selector'];
            $values = $style['values'];
            $css = '#'.$rolo_id.' '.$selector.'{';
            foreach( $values as $value ) {
                $val = get_post_meta( $slide->ID, '_rl_'.$value['id'], true );
                $prop = $value['property'];
                $css .= $prop.':'.$val.';';
            }
            $css .= '}';

            return $css;
        }

		/**
		 * This function returns slider data
		 *
		 * @since 1.0.0
		 */
		function slide_data($value)
		{
			/**** animations ***/
			$heading_anm = $this->value_check( $value,'_rl_title_anm' );
			$heading_anm_dur = $this->value_check( $value,'_rl_title_anm_dur' );
			$heading_anm_del = $this->value_check( $value,'_rl_title_anm_del' );
			$heading_anm_val = $this->value_check( $value,'_rl_title_anm_val' );
			$subtitle_anm = $this->value_check( $value,'_rl_subtitle_anm' );
			$subtitle_anm_dur = $this->value_check( $value,'_rl_subtitle_anm_dur' );
			$subtitle_anm_del = $this->value_check( $value,'_rl_subtitle_anm_del' );
			$subtitle_anm_val = $this->value_check( $value,'_rl_subtitle_anm_val' );
			$desc_anm = $this->value_check( $value,'_rl_desc_anm' );
			$desc_anm_dur = $this->value_check( $value,'_rl_desc_anm_dur' );
			$desc_anm_del = $this->value_check( $value,'_rl_desc_anm_del' );
			$desc_anm_val = $this->value_check( $value,'_rl_desc_anm_val' );
			$buttons_anm = $this->value_check( $value,'_rl_buttons_anm' );
			$buttons_anm_dur = $this->value_check( $value,'_rl_buttons_anm_dur' );
			$buttons_anm_del = $this->value_check( $value,'_rl_buttons_anm_del' );
			$buttons_anm_val = $this->value_check( $value,'_rl_buttons_anm_val' );

			$data  = 'data-tanm="'.$heading_anm.'"';
			$data .= ' data-tanmdur="'.$heading_anm_dur.'"';
			$data .= ' data-tanmdel="'.$heading_anm_del.'"';
			$data .= ' data-tanmv="'.$heading_anm_val.'"';
			$data .= ' data-stanm="'.$subtitle_anm.'"';
			$data .= ' data-stanmdur="'.$subtitle_anm_dur.'"';
			$data .= ' data-stanmdel="'.$subtitle_anm_del.'"';
			$data .= ' data-stanmv="'.$subtitle_anm_val.'"';
			$data .= ' data-danm="'.$desc_anm.'"';
			$data .= ' data-danmdur="'.$desc_anm_dur.'"';
			$data .= ' data-danmdel="'.$desc_anm_del.'"';
			$data .= ' data-danmv="'.$desc_anm_val.'"';
			$data .= ' data-banm="'.$buttons_anm.'"';
			$data .= ' data-banmdur="'.$buttons_anm_dur.'"';
			$data .= ' data-banmdel="'.$buttons_anm_del.'"';
			$data .= ' data-banmv="'.$buttons_anm_val.'"';

			$data .= apply_filters('rolo_slider_slide_data', '', $value);

			return $data;
		}

		/**
		 * This function returns slide meta
		 *
		 * @since 1.0.0
		 */
		function slide_meta($value)
		{
			$thumb_id = $this->value_check( $value,'_rl_screen_id' );
			$heading = $this->value_check( $value,'_rl_title' );
			$subtitle = $this->value_check( $value,'_rl_subtitle' );
			$content = $this->value_check( $value, '_rl_desc' );
			$button1 = $this->value_check( $value,'_rl_button1' );
			$button1url = $this->value_check( $value,'_rl_button_url1' );
			$button2 = $this->value_check( $value,'_rl_button2' );
			$button2url = $this->value_check( $value,'_rl_button_url2' );
			$captions = $this->value_check($value, '_rl_captions');
			$hor_pos = $this->value_check( $value, '_rl_hor_pos' );
			$ver_pos = $this->value_check( $value, '_rl_ver_pos' );
			$pos = $hor_pos . ' ' . $ver_pos;

			$metaClass = array('slider-meta-outer-wrap', $pos, 'has-caption');
			if( $captions ) $metaClass = array_diff($metaClass, array('has-caption'));

			$meta = '';
			$hasMeta = false;

			if($heading || $subtitle || $content || $button1 || $button2) $hasMeta = true;
			if( $hasMeta ) $meta .= "<div class='".implode(' ', $metaClass)."'><div class='slider-meta-wrap'>";
			if($heading) $meta .= "<div class='slide-layer'><h3 class='slider-title'>".$heading.'</h3></div>';
			if($subtitle) $meta .= "<div class='slide-layer'><h4 class='slider-subtitle'>".$subtitle.'</h4></div>';
			if($content) $meta .= "<div class='slide-layer'><p class='slide-desc'>".$content.'</p></div>';
			if($button1 || $button2) $meta .= "<div class='slider-buttons'>";
			if($button1) $meta .= "<a href='".$button1url."' class='slider-button slider-button-1'>".$button1.'</a>';
			if($button2) $meta .= "<a href='".$button2url."' class='slider-button slider-button-2'>".$button2.'</a>';
			if($button1 || $button2) $meta .= "</div>";
			if( $hasMeta ) $meta .= "</div></div>";

			return $meta;
		}

		/**
		 * This function will check
		 * if there is value assigned to the
		 * meta box, and return the output
		 *
		 * @since 1.0.0
		 */
		public function value_check($data, $value, $is_array = false)
		{
			$output = false;
			if( isset($data[$value]) && '' != $data[$value] ) $output = $data[$value];

			return $output;
		}

         /**
         * Print the dynamically 
         * generated css
         *
         * @since 1.0.0
         */
        public function print_styles() 
        {
        	$data = $this->style;
        	$style = $data;
        	$css = '<style>';
        	$css .= $style;
        	$css .= '</style>';

        	echo $css;
        }

    }//class ends
}//if !class_exists
