<?php
namespace PressforeExporter;

use PressforeExporter\ParseXML;

/**
 * Class which holds all the
 * functions which hooks into main
 * Exporter class
 */
class functions
{

    # Slide images array
    public $slide_images;
    /**
     * main constructor method
     *
     * @since 1.0.0
     */
    function __construct()
    {
        add_action( "pf_exporter_content_selection", array( $this, 'page_content' ), 10, 1 );
        add_action( "pf_exporter_ajax_callback", array( $this, 'ajax_callback' ) );
        add_action( "pf_exporter_additional_xml_data", array( $this, 'slide_img' ),10 ,2 );
        add_action( "pf_exporter_after_item_xml_data", array( $this, 'attachments' ) );
    }

    /**
     * HTML markup of the export page
     *
     * @param $posts
     * @since 1.0.0
     */
    public function page_content($data)
    {
        if( !is_array($data) ) {
            $posts[] = $data;
        } else {
            $posts = $data;
        }
        ?>
            <div class="rolo-export-sliders">
                <h2><?php esc_html_e('Choose Slider(s) to export', 'rolo-slider'); ?></h2>
                <?php foreach( $posts as $post ): ?>
                    <div class="rolo-slider">
                        <?php
                            $image_src = $this::get_image_preview($post);
                            $name      = $this::get_slider_name($post);
                        ?>

                        <div class="rolo-slider-img-wrap">
                            <img src="<?php echo $image_src; ?>" />
                        </div>

                        <h3><?php echo $name ?></h3>

                        <a data-name="<?php echo $name ?>" class="slider-sel"></a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php
    }

    /**
     * Get the first slide's image as preview image
     *
     * @param $post
     * @return string
     * @since 1.0.0
     */
    public static function get_image_preview($post)
    {
        $layout = get_post_meta( $post->ID, '_rl_layout', true );
        $slide_data = get_post_meta($post->ID, '_rl_slide', true);
        $resp_data = get_post_meta($post->ID, '_rl_responsive', true);

        $image = '';

        if( isset($layout[0]) && 'images' == $layout[0] ) {

            if(  isset( $resp_data ) && is_array($resp_data) ) {
                $image = current($resp_data);
            }
        } else {

            if(  isset( $slide_data[0] ) && isset( $slide_data[0]['_rl_screen'] ) ) {
                $image = $slide_data[0]['_rl_screen'];
            }
        }

        return $image;
    }

    /**
     * Get the slider's name
     *
     * @param $post
     * @return string
     * @since 1.0.0
     */
    public static function get_slider_name($post)
    {
        $name = $post->post_name;

        return $name;
    }

    /**
     * Add slide's img to xml export file
     *
     * @param $posts
     * @param $postmeta
     * @since 1.0.0
     */
    public function slide_img($post, $id)
    {
        $slides = get_post_meta($id, '_rl_slide', true);
        $resp_data = get_post_meta($post->ID, '_rl_responsive', true);

        foreach ($slides as $slide) {
            $this->slide_images[] = $slide['_rl_screen_id'];
        }

        if(  isset( $resp_data ) && is_array($resp_data) ) {
            foreach( $resp_data as $id => $url ) {
                $this->slide_images[] = $id;
            }
        }
    }

    /**
     * Add slide's img to xml export file
     *
     * @param $posts
     * @param $postmeta
     * @since 1.0.0
     */
    public function attachments()
    {
        $image_data = $this->slide_images;
        echo '<!-- Collection of images attached to the slides of current slider -->';
        // Begin Loop.
        foreach ( $image_data as $image ) {
        $post = get_post($image);
        setup_postdata( $post );
        $is_sticky = 0;
        if ( $post->post_type == 'attachment' ):
        ?>

            <item>
            <title><?php
            /** This filter is documented in wp-includes/feed.php */
            echo apply_filters( 'the_title_rss', $post->post_title );
            ?></title>
                <link><?php the_permalink_rss() ?></link>
                <pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
                <dc:creator><?php echo $this->wxr_cdata( get_the_author_meta( 'login' ) ); ?></dc:creator>
                <guid isPermaLink="false"><?php the_guid(); ?></guid>
                <description></description>
                <content:encoded><?php
                /**
                 * Filters the post content used for WXR exports.
                 *
                 * @since 2.5.0
                 *
                 * @param string $post_content Content of the current post.
                 */
            echo $this->wxr_cdata( apply_filters( 'the_content_export', $post->post_content ) );
            ?></content:encoded>
                <excerpt:encoded><?php
                /**
                 * Filters the post excerpt used for WXR exports.
                 *
                 * @since 2.6.0
                 *
                 * @param string $post_excerpt Excerpt for the current post.
                 */
            echo $this->wxr_cdata( apply_filters( 'the_excerpt_export', $post->post_excerpt ) );
            ?></excerpt:encoded>
                <wp:post_id><?php echo intval( $post->ID ); ?></wp:post_id>
                <wp:post_date><?php echo $this->wxr_cdata( $post->post_date ); ?></wp:post_date>
                <wp:post_date_gmt><?php echo $this->wxr_cdata( $post->post_date_gmt ); ?></wp:post_date_gmt>
                <wp:comment_status><?php echo $this->wxr_cdata( $post->comment_status ); ?></wp:comment_status>
                <wp:ping_status><?php echo $this->wxr_cdata( $post->ping_status ); ?></wp:ping_status>
                <wp:post_name><?php echo $this->wxr_cdata( $post->post_name ); ?></wp:post_name>
                <wp:status><?php echo $this->wxr_cdata( $post->post_status ); ?></wp:status>
                <wp:post_parent><?php echo intval( $post->post_parent ); ?></wp:post_parent>
                <wp:menu_order><?php echo intval( $post->menu_order ); ?></wp:menu_order>
                <wp:post_type><?php echo $this->wxr_cdata( $post->post_type ); ?></wp:post_type>
                <wp:post_password><?php echo $this->wxr_cdata( $post->post_password ); ?></wp:post_password>
                <wp:is_sticky><?php echo intval( $is_sticky ); ?></wp:is_sticky>
            <?php	if ( $post->post_type == 'attachment' ) : ?>
                <wp:attachment_url><?php echo $this->wxr_cdata( wp_get_attachment_url( $post->ID ) ); ?></wp:attachment_url>
            <?php 	endif; ?>
            <?php
            ?>
         </item>
         <?php
          endif;
        }
    }

    /**
     * Wrap given string in XML CDATA tag.
     *
     * @since 1.0.0
     *
     * @param string $str String to wrap in XML CDATA tag.
     * @return string
     */
    function wxr_cdata( $str ) {
        if ( ! seems_utf8( $str ) ) {
            $str = utf8_encode( $str );
        }
        // $str = ent2ncr(esc_html($str));
        $str = '<![CDATA[' . str_replace( ']]>', ']]]]><![CDATA[>', $str ) . ']]>';

        return $str;
    }

    /**
     * Ajax callback function
     *
     * @param $posts
     * @since 1.0.0
     */
    public function ajax_callback()
    {
        $selector = 'all';
        if( isset($_GET['selector']) ) {
            $selector = $_GET['selector'];
        }

        $args = array(
            'post_type' => 'rolo_slider',
            'selector'  => $selector
        );

        ParseXML\Export( $args );
    }

    /**
     * Function that Writes export content to XML file
     *
     * @param $content
     * @return string  path to the xml file
     * @since 1.0.0
     */
    public static function write_xml($content)
    {
        WP_Filesystem();
        global $wp_filesystem;

        $upload_dir = wp_upload_dir();
        $base = $upload_dir['basedir'];
        $date = date( 'Y-m-d' );
        $filename =  'rolo_export.' . $date . '.xml';
        $file = trailingslashit($base) . $filename;
        $link = trailingslashit($upload_dir['baseurl']) . $filename;

        $wp_filesystem->put_contents($file, $content, FS_CHMOD_FILE);

        return array(
            'file' => $file,
            'link' => $link
        );
    }
}