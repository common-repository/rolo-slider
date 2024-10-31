<?php
namespace PressForeImporter;

/**
 * Importer class
 */

class functions {

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
     * Main Hooks
     */
    function hooks()
    {
        add_action('pf_importer_import_page_output', array( $this, 'dispatch' ) );
        add_action('pf_importer_import_end', array( $this, 'after_import' ) );
        add_action('pf_importer_ajax_callback', array( $this, 'import_demo' ), 10, 1 );
        add_action( "admin_footer", array( $this, 'js_functions' ) );
    }

    /**
     * Registered callback function for the WordPress Importer
     *
     * Manages the three separate stages of the WXR import process
     */
    function dispatch()
    {
        $this->header();

        $importer = pf_importer_get_instance();

        $step = !isset($_GET['step']) ? 'start' : $_GET['step'];
        switch ($step) {
            case 'start':
                $this->import_options();
                break;
            case 'exported':
                check_admin_referer('pf-importer');
                if ($importer->handle_upload()) {
                    $importer->fetch_attachments = $importer->allow_fetch_attachments();

                    $file = get_attached_file($importer->id);
                    set_time_limit(0);
                    $importer->import($file);
                }
                break;
        }

        $this->footer();
    }

    /**
     * Display the import page markup
     */
    function import_options()
    {
        ?>
        <?php
        $importer = pf_importer_get_instance();
        $bytes = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
        $size = size_format( $bytes );
        $upload_dir = wp_upload_dir();
        if ( ! empty( $upload_dir['error'] ) ) :
            ?><div class="error"><p><?php _e('Before you can upload your import file, you will need to fix the following error:'); ?></p>
            <p><strong><?php echo $upload_dir['error']; ?></strong></p></div><?php
        else :
            echo '<div class="narrow">';
            echo '<p>' . __('Upload your exported slider(s) to your website.', 'rolo-slider') . '</p>';
            echo '<p>' . __('Choose a WXR (.xml) slider file to upload, then click Upload file and import.', 'rolo-slider') . '</p>';
            echo '</div>';
            ?>
            <form enctype="multipart/form-data" id="import-upload-form" method="post" class="wp-upload-form" action="<?php echo admin_url('edit.php?post_type=rolo_slider&amp;page=import&amp;step=exported'); ?>">
                <?php wp_nonce_field('pf-importer'); ?>
                <p>
                    <label for="upload"><?php _e( 'Choose a file from your computer:' ); ?></label> (<?php printf( __('Maximum size: %s' ), $size ); ?>)
                    <input type="file" id="upload" name="import" size="25" />
                    <input type="hidden" name="action" value="save" />
                    <input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />
                    <input type="hidden" name="import_id" value="<?php echo $importer->id; ?>"/>
                </p>
                <?php submit_button( __('Upload file and import'), 'primary' ); ?>
            </form>
            <?php
        endif;
    }

    /**
     * Function which fires after import is done
     */
    function after_import()
    {
        echo '<h4 style="color: green">' . __('Import successfully finished.', 'rolo-slider') . '</h4>';
        echo '<h5>' . __('Redirecting...', 'rolo-slider') . '</h5>';

        $url = 'edit.php?post_type=rolo_slider';
        $url = apply_filters('pf_importer_after_done_redirect_url', $url);

        ?>
        <input type="hidden" value="<?php echo admin_url($url); ?>" id="pf-import-done" />
        <?php
    }

    /**
     * Function that imports slider demo content
     *
     * @param $content
     * @return string  path to the xml file
     * @since 1.0.0
     */
    public function import_demo($name)
    {
        if( $name ) {
            $file = $this->write_xml($name);

            if( $file ) {
                $importer = pf_importer_get_instance();
                $import_data = $importer->parse($file);

                if (is_wp_error($import_data)) {
                    echo '<p><strong>' . __('Sorry, there has been an error.', 'rolo-slider') . '</strong><br />';
                    echo esc_html($import_data->get_error_message()) . '</p>';
                    return false;
                }

                $importer->get_authors_from_import($import_data);

                $importer->fetch_attachments = $importer->allow_fetch_attachments();
                set_time_limit(0);
                $importer->import($file);

                wp_import_cleanup($file);

                wp_cache_flush();
                foreach (get_taxonomies() as $tax) {
                    delete_option("{$tax}_children");
                    _get_term_hierarchy($tax);
                }

                wp_defer_term_counting(false);
                wp_defer_comment_counting(false);

                do_action('pf_importer_import_end');

                @unlink($file);

                return true;
            }
        }
    }

    /**
     * Function that Writes import content to XML file
     *
     * @param $content
     * @return string  path to the xml file
     * @since 1.0.0
     */
    public function write_xml($name)
    {
        WP_Filesystem();
        global $wp_filesystem;

        $upload_dir = wp_upload_dir();
        $base = $upload_dir['basedir'];
        $date = date( 'Y-m-d' );
        $filename =  'rolo_export.' . $date . '.xml';
        $file = trailingslashit($base) . $filename;

        $content = $this->fetch_content($name);

        $wp_filesystem->put_contents($file, $content, FS_CHMOD_FILE);

        return $file;
    }

    /**
     * Fetch demo data.
     *
     * @param void
     * @return array
     * @since 1.0.0
     */
    function fetch_content($name)
    {
        $query_params = array(
            'name' => $name
        );
        $args = array( 'headers' => array("Accept" => "text/plain"));

        $url = $this->fetch_url();
        $url = add_query_arg( $query_params, $url );

        $data = wp_safe_remote_get($url, $args);

        if( !empty($data) ) {
            $data = wp_remote_retrieve_body($data);
        } else {
            return false;
        }

        return $data;
    }

    /**
     * Fetch demo data.
     *
     * @param void
     * @return array
     * @since 1.0.0
     */
    function fetch_url()
    {

        $args = array( 'headers' => array("Accept" => "application/json"));

        $query_params = array(
            'name' => 'rolo-slider'
        );
        $url = add_query_arg( $query_params, 'http://demo.pressfore.com/plugins/demo.php' );

        $data = wp_safe_remote_get($url, $args);

        if( !empty($data) ) {
            $data = wp_remote_retrieve_body($data);

            $data = json_decode($data, ARRAY_A);
        } else {
            return false;
        }

        return $data[0];
    }

    /**
     * Javascript functions
     *
     * @since 1.0.0
     */
    public function js_functions()
    {
        ?>
        <script type="text/javascript">

            function redirect(url) {
                window.location.href = url;
            }

            jQuery( function() {

                jQuery(document).ready(function() {
                    if( jQuery("#pf-import-done").length ) {
                        var url = jQuery("#pf-import-done").val();

                        redirect(url);
                    }
                });

            });
        </script>
        <?php
    }

    // Display import page title
    function header()
    {
        echo '<div class="wrap">';
        echo '<h2>' . __('Import Rolo Slider', 'rolo-slider') . '</h2>';
    }

    // Close div.wrap
    function footer()
    {
        echo '</div>';
    }
}