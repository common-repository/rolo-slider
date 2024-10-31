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
    <a href="http://docs.pressfore.com/rolo-slider/" target="_blank"><i class="dashicons dashicons-book"></i> <?php esc_html_e('Documentation', 'rolo-slider'); ?></a>
    <a href="http://pressfore.com/item-category/addons/" target="_blank"><i class="dashicons dashicons-cart"></i> <?php esc_html_e('Addons', 'rolo-slider'); ?></a>
    <a href="http://pressfore.com/feature-proposal/" target="_blank"><i class="dashicons dashicons-welcome-add-page"></i> <?php esc_html_e(' Propose a feature', 'rolo-slider'); ?></a>
</div>

<div class="changelog">
<h2><?php esc_html_e( 'What\'s New In 1.0.0', 'rolo-slider' ); ?></h2>
<p><?php esc_html_e('This is major and finally stable release of Rolo Slider plugin, which also brings some major improvements. Beside refactored slider code, slider options are now organized in tabs which greatly improves user experience, and also we added tooltips for explaining key options.', 'rolo-slider'); ?></p>
<ul class="new">
    <li>
    <h3><?php  esc_html_e('Import / Export option', 'rolo-slider'); ?></h3>
        <img src="<?php echo ROLO_DIR.'assets/images/01-rolo-new-feature-export.jpg'; ?>"/>
        <p><?php esc_html_e('With newly added Import and Export options, now you will be able to export sliders from your existing projects and to import them on some other WordPress installation in just few clicks. Time and effort needed to start with new project is now brought down to a bare minimum!', 'rolo-slider'); ?></p>
    </li>
    <li>
        <h3><?php  esc_html_e('Demo Import', 'rolo-slider'); ?></h3>
        <img src="<?php echo ROLO_DIR.'assets/images/02-rolo-new-feature-demo.jpg'; ?>"/>
        <p><?php esc_html_e('You are just starting with Rolo Slider, or you just want to start with slider right away? No problem, with new Demo Data import page you can now import demo sliders which are available on our demo page with a single click of a mouse.', 'rolo-slider'); ?></p>
    </li>
    <li>
        <h3><?php  esc_html_e('Buttons hovers', 'rolo-slider'); ?></h3>
        <img src="<?php echo ROLO_DIR.'assets/images/03-rolo-new-feature-b-hovers.jpg'; ?>"/>
        <p><?php esc_html_e('This release also brings probably long awaited options for setting text and background color for slider buttons. Pretty neat, right.', 'rolo-slider'); ?></p>
    </li>
    <li>
        <h3><?php  esc_html_e('Extended Ken Burns Support', 'rolo-slider'); ?></h3>
        <img src="<?php echo ROLO_DIR.'assets/images/04-rolo-new-feature-code-responsive-images.jpg'; ?>"/>
        <p><?php esc_html_e('Ken Burns Now Works With Responsive Images Layout!', 'rolo-slider'); ?></p>
    </li>
    <li>
        <h3><?php  esc_html_e('Improved Options UI', 'rolo-slider'); ?></h3>
        <img src="<?php echo ROLO_DIR.'assets/images/05-rolo-new-feature-ui.png'; ?>"/>
        <p><?php esc_html_e('Slider options are organized into tabs and options UI is improved.', 'rolo-slider'); ?></p>
    </li>
    <li>
        <h3><?php  esc_html_e('Optimized And Refactored Code', 'rolo-slider'); ?></h3>
        <img src="<?php echo ROLO_DIR.'assets/images/06-rolo-new-feature-code.png'; ?>"/>
        <p><?php esc_html_e('In this update we have basically rewritten some parts of the code from scratch, and refactored the rest of the code for better performance.', 'rolo-slider'); ?></p>
    </li>
</ul>

<div class="feature-section images-stagger-right">
    <h3 class="big"><?php esc_html_e( 'Features', 'rolo-slider' ); ?></h3>
    <h4><?php _e( 'Easy To Use' ); ?></h4>
    <p><?php _e( 'Rolo is very easy to use, with it\'s native user interface you can manage to create advanced slider without hassle or trouble. Rolo is working out of the box, and with minimal effort you can achieve maximal results.', 'rolo-slider' ); ?></p>

    <h4><?php esc_html_e( 'Customization', 'rolo-slider' ); ?></h4>
    <p><?php esc_html_e( 'You can modify slider settings easily with just a few clicks of the mouse you can have eye catching slider. Plenty of options and customization are available.', 'rolo-slider' ); ?></p>
</div>

<div>
    <h4 id="adn" style="color: green"><?php _e( 'Addons' ); ?></h4>
    <p><?php esc_html_e('If you want even more controll and options, you can extend Rolo Slider with available addons.', 'rolo-slider') ?></p>
    <a href="edit.php?post_type=rolo_slider&page=addons" class="learn-more">Learn More</a>
</div>
</div>

</div>