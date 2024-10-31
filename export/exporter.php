<?php
namespace PressforeExporter;

/**
 * Main Exporter Class
 */
class Exporter
{
    # plugin class prefix
    public $prefix;

    # post type slug
    public $post_type;

    /**
     * main constructor method
     *
     * @since 1.0.0
     */
    function __construct($prefix)
    {
        $this->hooks($prefix);
    }

    /**
     * Register hooks
     *
     * @since 1.0.0
     *
     */
    public function hooks($prefix) {
        $this->prefix = $prefix;

        add_action( "admin_footer", array( $this, 'js_functions' ) );
        add_action( "wp_ajax_{$prefix}_export_callback", array( $this, 'ajax_callback' ) );
        add_action( "wp_ajax_nopriv_{$prefix}_export_callback", array( $this, 'ajax_callback' ) );
    }

    /**
     * Html output of the export page
     *
     * @since 1.0.0
     */
    public function page_html()
    {
        $prefix = $this->prefix;
        $action = "{$prefix}_export_callback";

        $buttons = $this->export_buttons_output($action);
        ?>
            <div class="<?php echo $prefix ?>-export pf-export-page">
                <?php $posts = $this::get_posts();  ?>

                <?php
                    /**
                     * Hook for creating the main export page markup.
                     *
                     * @param $posts
                     * @since 1.0.0
                     */
                    do_action('pf_exporter_content_selection', $posts);
                ?>
            </div>

            <div class="rolo-export-buttons">
                <?php
                    foreach( $buttons as $button ) {
                        echo $button;
                    }
                ?>
            </div>
        <?php
    }

    /**
     * Format main query args
     *
     * @param $post_type
     * @return array
     * @since 1.0.0
     */
    function get_args($post_type)
    {
        $args = array(
            'post_type'      => $post_type,
            'posts_per_page' => -1,
            'post_status'    => 'publish'
        );

        /**
         * Filter for the main query arguments
         */
        $args = apply_filters('pf_exporter_post_args', $args);

        return $args;
    }

    /**
     * Get all posts from selected post type.
     *
     * @param void
     * @return array
     * @since 1.0.0
     */
    function get_posts()
    {
        $post_type = $this->post_type;
        $args      = $this::get_args($post_type);

        $posts = get_posts($args);

        return $posts;
    }

    /**
     * Build export button
     *
     * @since 1.0.0
     */
    public function export_buttons_output($action)
    {
        $prefix = $this->prefix;
        $query_string = array(
            'action'   => esc_attr($action),
            'security' => wp_create_nonce("{$prefix}_exporter_nonce")
        );

        $url  = admin_url( 'admin-ajax.php' );
        $url .= '?' . build_query( $query_string );

        $buttons = array(
           0 => get_submit_button( __('Export Slider', 'rolo-slider'), $type = 'primary', $name = 'submit', $wrap = false, $other_attributes = array(  'data-action' => 'export-slide', 'data-href' => $url, 'disabled' => 'disabled' ) ),
           1 => get_submit_button( __('Export All Sliders', 'rolo-slider'), $type = 'primary', $name = 'submit', $wrap = false, $other_attributes = array( 'data-action' => 'export-all', 'data-href' => $url.'&selector=all' ) )
        );

        return apply_filters('pf_exporter_export_buttons_output', $buttons);
    }


    /**
     * Extract slider data into xml tags
     *
     * @since 1.0.0
     */
    public function ajax_callback()
    {
        # Noonce security check
        $prefix = $this->prefix;
        check_ajax_referer("{$prefix}_exporter_nonce", 'security');

       /**
        * Main ajax hook which is handling
        * the ajax callback function
        */
       do_action('pf_exporter_ajax_callback');

       # Close the ajax call
       die();
    }

    /**
     * Javascript ajax export functions
     *
     * @since 1.0.0
     */
    public function js_functions()
    {
        ?>
            <script type="text/javascript">

                function exportSlider(link) {
                    window.location.href = link;
                }

                function sliderSelect() {
                    var $this = jQuery(this);

                    if( ! $this.hasClass('active') ) {
                        $this.addClass('active').siblings().removeClass('active');
                    }

                    jQuery('input[data-action="export-slide"]').removeAttr('disabled');
                }

                function exportAction() {
                    var selector = 'all';
                    var $this = jQuery(this);
                    var link = $this.data('href');

                    if (jQuery(".rolo-slider.active").length <= 0 && $this.data('action') == 'export-slide') {
                        alert(<?php echo json_encode( __( 'Please select the slider to be exported', 'rolo-slider' ) );  ?>);

                        return false;
                    }

                    if( $this.data('action') == 'export-slide' ) {
                        var slider = jQuery('.rolo-slider.active').find('.slider-sel');
                        selector = slider.data('name');

                        link += '&selector='+selector;
                    }

                    exportSlider(link);

                    return false;
                }

                jQuery( function() {

                    jQuery(document).ready(function() {
                        jQuery(".rolo-export-buttons").find('.button').on( 'click', exportAction );
                        jQuery('.rolo-export-sliders').find('.rolo-slider').on( 'click', sliderSelect );
                    });

                });
            </script>
        <?php
    }

    /**
     * Wrapper function for export page markup
     *
     * This function will call the main
     * export page html
     *
     * @since 1.0.0
     */
    public function export_page_output()
    {
        $this->page_html();
    }
}
