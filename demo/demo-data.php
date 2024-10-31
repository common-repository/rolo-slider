<?php
namespace PressforeExporter;

/**
 * Main Exporter Class
 */
class DemoData
{

    /**
     * main constructor method
     *
     * @since 1.0.0
     */
    function __construct()
    {
        $this->hooks();
    }

    /**
     * Register hooks
     *
     * @since 1.0.0
     *
     */
    public function hooks() {
        add_action( "admin_footer", array( $this, 'js_functions' ) );
        add_action( "wp_ajax_rolo_import_data", array( $this, 'ajax_callback' ) );
        add_action( "wp_ajax_nopriv_rolo_import_data", array( $this, 'ajax_callback' ) );
    }

    /**
     * Html output of the export page
     *
     * @since 1.0.0
     */
    public function page_html()
    {
        $button = $this->demo_button_output();
        ?>
        <div class="rolo-demo-data">
            <h2><?php esc_html_e('Import Rolo Demo Sliders' , 'rolo-slider'); ?></h2>
            <h4><?php esc_html_e('Please note, this can take few minutes, so you might need to wait for a minute, or two before import process is done.', 'rolo-slider'); ?></h4>
            <?php $data = $this->get_demos(); ?>
            <?php echo $data; ?>

            <?php
            /**
             * Hook for adding content to demo data page.
             *
             * @param $posts
             * @since 1.0.0
             */
            do_action('pf_importer_demo_data_content');
            ?>

            <div id="demo-msg"></div>

            <p class="rolo-demo-button">
                <?php echo $button;  ?>
            </p>

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
            'posts_per_page' => -1
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
    function get_demos()
    {
        $data  = $this->fetch_demos();
        $demos = $this->sort_demos($data);

        return $demos;
    }

    /**
     * Fetch demo data.
     *
     * @param void
     * @return array
     * @since 1.0.0
     */
    function fetch_demos()
    {
        $url          = 'http://pressfore.com/demo-import/index.php';
        $query_params = array(
            'demo_item' => 'rolo'
        );
        $query_params = apply_filters('rolo_import_demo_query', $query_params);
        $args = array( 'headers' => array("Accept" => "application/json"));

        $url = add_query_arg( $query_params, $url );

        $data = wp_safe_remote_get($url, $args);

        if( !empty($data) ) {
            $data = wp_remote_retrieve_body($data);

            $data = json_decode($data, ARRAY_A);
        } else {
            return false;
        }

        return $data;
    }

    /**
     * Sort demos.
     *
     * @param void
     * @return array
     * @since 1.0.0
     */
    function sort_demos($demos)
    {
        $html = '';

        foreach( $demos as $demo ) {
            $preview = $demo['preview'];
            $title   = $demo['title'];
            $url     = $demo['url'];

            $preview = $this->get_preview($preview);
            $title   = $this->get_title($title);
            $url     = $this->get_url($url);

            $html .= $this->get_markup($title, $preview, $url);
        }

        return $html;
    }

    /**
     * Get Preview image.
     *
     * @param void
     * @return array
     * @since 1.0.0
     */
    function get_preview($url)
    {
        $img = '<img src="'.$url.'" />';

        return $img;
    }

    /**
     * Get Preview title.
     *
     * @param void
     * @return array
     * @since 1.0.0
     */
    function get_title($title)
    {
        $data = "<h4>{$title}</h4>";

        return $data;
    }

    /**
     * Get Preview url.
     *
     * @param void
     * @return array
     * @since 1.0.0
     */
    function get_url($url)
    {
        $data = '<div><p><input type="hidden" class="url" value="'.$url.'" /></p></div>';

        return $data;
    }

    /**
     * Get html markup.
     *
     * @param void
     * @return array
     * @since 1.0.0
     */
    function get_markup($title, $preview, $url)
    {
        $html = '<div class="rolo-demo"><div>';
        $html .= '<div class="rolo-slider-img-wrap">'.$preview.'</div>';
        $html .= $title;
        $html .= $url;
        $html .= '</div></div>';

        return $html;
    }

    /**
     * Build export button
     *
     * @since 1.0.0
     */
    public function demo_button_output()
    {
        $button =  get_submit_button( __('Import Demo', 'rolo-slider'), $type = 'primary', $name = 'submit', $wrap = false, $other_attributes = array( 'data-action' => 'demo-import', 'disabled' => 'disabled' ) );

        return $button;
    }

    /**
     * Ajax callback
     *
     * @since 1.0.0
     */
    public function ajax_callback()
    {
        # Noonce security check
        check_ajax_referer("rolo_importer_nonce", 'security');

        $name = isset($_POST['name']) ? $_POST['name'] : '';

        /**
         * Main ajax hook which is handling
         * the ajax callback function
         */
        do_action('pf_importer_ajax_callback', $name);

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

                function importSlider(selector) {
                    jQuery('.rolo-demo-data').addClass('load').append('<div class="loader">Loading...</div>');
                    jQuery.ajax({
                        type: 'POST',
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {
                            action: "rolo_import_data",
                            name : selector,
                            security: "<?php echo wp_create_nonce('rolo_importer_nonce'); ?>"
                        },
                        success: function(data) {
                            jQuery('.rolo-demo-data').removeClass('load').find('.loader').remove();
                            if( jQuery('#demo-msg').find('h5').length <= 0 ) {
                                jQuery('#demo-msg').html(data);
                            }

                            if( jQuery("#pf-import-done").length ) {
                                var url = jQuery("#pf-import-done").val();

                                redirect(url);
                            }
                        }
                    });
                }

                function redirect(url) {
                    window.location.href = url;
                }

                function sliderSelect() {
                    var $this = jQuery(this);

                    if( ! $this.hasClass('active') ) {
                        $this.addClass('active').siblings().removeClass('active');
                    }

                    jQuery('#submit').removeAttr('disabled');
                }

                function importAction() {
                    var selector = '';
                    var $this = jQuery(this);

                    if (jQuery(".rolo-demo.active").length <= 0 && $this.data('action') == 'demo-import') {
                        alert(<?php echo json_encode( __( 'Please select the slider to be imported', 'rolo-slider' ) );  ?>);

                        return false;
                    }

                    if( $this.data('action') == 'demo-import' ) {
                        var item = jQuery('.rolo-demo.active').find('.url');
                        selector = item.val();
                    }

                    if( selector ) {
                        importSlider(selector);
                    }

                    return false;
                }

                jQuery( function() {

                    jQuery(document).ready(function() {
                        jQuery(".rolo-demo-button").find('.button').on( 'click', importAction );
                        jQuery('.rolo-demo-data').find('.rolo-demo').on( 'click', sliderSelect );
                    });

                });
            </script>
        <?php
    }
}
