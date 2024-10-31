<?php
/**
 * Custom about page
 */

?>
<div class="wrap about-wrap">

	<h1><?php esc_html_e( 'Rolo Slider', 'rolo-slider'); ?></h1>
	
	<div class="about-text">
		<?php esc_html_e('Rolo Slider is Best Responsive Slider for creating stunning slides with ken burns effect and animated layers. Add images, heading ,subheading, description and buttons inside each slide. Select the entrance/exit animation for each layer, and edit slides and options inside simple to use native user interface.', 'rolo-slider' ); ?>
	</div>

	 <div class="about-links">
		 <a href="https://wordpress.org/support/plugin/rolo-slider" target="_blank"><i class="dashicons dashicons-sos"></i> <?php esc_html_e('Support', 'rolo-slider'); ?> </a>
         <a href="http://pressfore.com/documentation/rolo-slider/" target="_blank"><i class="dashicons dashicons-book"></i> <?php esc_html_e('Documentation', 'rolo-slider'); ?></a>
         <a href="http://pressfore.com/feature-proposal/" target="_blank"><i class="dashicons dashicons-welcome-add-page"></i> <?php esc_html_e(' Propose a feature', 'rolo-slider'); ?></a>
	</div>

	<div class="changelog">
		<h2><?php esc_html_e( 'What\'s New In 0.4', 'rolo-slider' ); ?></h2>
		<ul class="new">
			<li>
				<h3><?php  esc_html_e('New Caption options are here:', 'rolo-slider'); ?></h3>
				<img src="<?php echo ROLO_DIR.'assets/images/v_0.4_captions.png'; ?>"/>
				<p><?php esc_html_e('You can now use the global caption options to set background color for catpions - slide layers like title, subtitle and description.', 'rolo-slider'); ?></p>
			</li>
			<li>
				<h3><?php  esc_html_e('Transparent Captions Per Slide:', 'rolo-slider'); ?></h3>
				<img src="<?php echo ROLO_DIR.'assets/images/v_0.4_trans_captions.png'; ?>"/>
				<p><?php esc_html_e('You can also choose to show the transparent background for captions on the current slide. This can be applied per slide, and it will affect the background color of each layer like title, subtitle and description. If this option is checked it will ignore the background color, leaving only the text color above the image.', 'rolo-slider'); ?></p>
			</li>
			<li>
				<h3><?php  esc_html_e('Buttons Style:', 'rolo-slider'); ?></h3>
				<img src="<?php echo ROLO_DIR.'assets/images/v_04_btns.png'; ?>"/>
				<p><?php esc_html_e('You can now select the background color and text color for both buttons on global level.', 'rolo-slider'); ?></p>
			</li>
		</ul>
			
		<div class="feature-section images-stagger-right">
			<h3 class="big"><?php esc_html_e( 'Features', 'rolo-slider' ); ?></h3>
			<h4><?php _e( 'Easy To Use' ); ?></h4>
			<p><?php _e( 'Rolo is very easy to use, with it\'s native user interface you can manage to create advanced slider without hassle or trouble. Rolo is working out of the box, and with minimal effort you can achieve maximal results.', 'rolo-slider' ); ?></p>
	
			<h4><?php esc_html_e( 'Customization', 'rolo-slider' ); ?></h4>
			<p><?php esc_html_e( 'You can modify slider settings easily with just a few clicks of the mouse you can have eye catching slider. Plenty of options and customization are available.', 'rolo-slider' ); ?></p>
	
		</div>
	</div>

</div>