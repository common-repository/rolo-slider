<?php
namespace PressforeExporter\ParseXML;

/**
 * Main export library
 *
 * @see wp-admin/includes/export.php
 */
function Export( $args = array() )
{
    global $wpdb, $post;

    if( !defined('WXR_VERSION') ) {
        define( 'WXR_VERSION', '1.2' );
    }

    $defaults = array(
        'selector'  => 'all',
        'post_type' => '',
        'has_terms' => false
    );
    $args = wp_parse_args( $args, $defaults );


    $date = date( 'Y-m-d' );
    if(  'all' == $args['selector'] ) {
        $filename =  'rolo_export.xml';
    } else {
        $filename =  'rolo_slide_' . $args['selector'] . '.xml';
    }

    header( 'Content-Description: File Transfer' );
    header( 'Content-Disposition: attachment; filename=' . $filename );
    header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );


    if ( 'all' != $args['selector'] && post_type_exists( $args['post_type'] ) ) {
        $where = $wpdb->prepare( "{$wpdb->posts}.post_type = %s AND {$wpdb->posts}.post_name = %s", $args['post_type'], $args['selector'] );
    } else {
        $where = $wpdb->prepare( "{$wpdb->posts}.post_type = %s", $args['post_type'] );
    }

    // Grab a snapshot of post IDs, just in case it changes during the export.
    $post_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE $where" );

    /*
     * Get the requested terms ready
     */
    $terms = array();
    if ( $args['has_terms'] ) {
        $custom_taxonomies = get_taxonomies( array( '_builtin' => false ) );
        $custom_terms = (array) get_terms( $custom_taxonomies, array( 'get' => 'all' ) );

        // Put terms in order with no child going before its parent.
        while ( $t = array_shift( $custom_terms ) ) {
            if ( $t->parent == 0 || isset( $terms[$t->parent] ) )
                $terms[$t->term_id] = $t;
            else
                $custom_terms[] = $t;
        }

        unset( $custom_taxonomies, $custom_terms );
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
     * Output a term_name XML tag from a given term object
     *
     * @since 1.0.0
     *
     * @param object $term Term Object
     */
    function wxr_term_name( $term ) {
        if ( empty( $term->name ) )
            return;

        echo '<wp:term_name>' . wxr_cdata( $term->name ) . "</wp:term_name>\n";
    }

    /**
     * Output a term_description XML tag from a given term object
     *
     * @since 1.0.0
     *
     * @param object $term Term Object
     */
    function wxr_term_description( $term ) {
        if ( empty( $term->description ) )
            return;

        echo "\t\t<wp:term_description>" . wxr_cdata( $term->description ) . "</wp:term_description>\n";
    }

    /**
     * Output term meta XML tags for a given term object.
     *
     * @since 1.0.0
     *
     * @param WP_Term $term Term object.
     */
    function wxr_term_meta( $term ) {
        global $wpdb;

        $termmeta = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->termmeta WHERE term_id = %d", $term->term_id ) );

        foreach ( $termmeta as $meta ) {
            /**
             * Filters whether to selectively skip term meta used for WXR exports.
             *
             * Returning a truthy value to the filter will skip the current meta
             * object from being exported.
             *
             * @since 4.6.0
             *
             * @param bool   $skip     Whether to skip the current piece of term meta. Default false.
             * @param string $meta_key Current meta key.
             * @param object $meta     Current meta object.
             */
            if ( ! apply_filters( 'wxr_export_skip_termmeta', false, $meta->meta_key, $meta ) ) {
                printf( "\t\t<wp:termmeta>\n\t\t\t<wp:meta_key>%s</wp:meta_key>\n\t\t\t<wp:meta_value>%s</wp:meta_value>\n\t\t</wp:termmeta>\n", wxr_cdata( $meta->meta_key ), wxr_cdata( $meta->meta_value ) );
            }
        }
    }

    /**
     * Output list of authors with posts
     *
     * @since 1.0.0
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param array $post_ids Array of post IDs to filter the query by. Optional.
     */
    function wxr_authors_list( array $post_ids = null ) {
        global $wpdb;

        if ( !empty( $post_ids ) ) {
            $post_ids = array_map( 'absint', $post_ids );
            $and = 'AND ID IN ( ' . implode( ', ', $post_ids ) . ')';
        } else {
            $and = '';
        }

        $authors = array();
        $results = $wpdb->get_results( "SELECT DISTINCT post_author FROM $wpdb->posts WHERE post_status != 'auto-draft' $and" );
        foreach ( (array) $results as $result )
            $authors[] = get_userdata( $result->post_author );

        $authors = array_filter( $authors );

        foreach ( $authors as $author ) {
            echo "\t<wp:author>";
            echo '<wp:author_id>' . intval( $author->ID ) . '</wp:author_id>';
            echo '<wp:author_login>' . wxr_cdata( $author->user_login ) . '</wp:author_login>';
            echo '<wp:author_email>' . wxr_cdata( $author->user_email ) . '</wp:author_email>';
            echo '<wp:author_display_name>' . wxr_cdata( $author->display_name ) . '</wp:author_display_name>';
            echo '<wp:author_first_name>' . wxr_cdata( $author->first_name ) . '</wp:author_first_name>';
            echo '<wp:author_last_name>' . wxr_cdata( $author->last_name ) . '</wp:author_last_name>';
            echo "</wp:author>\n";
        }
    }

    /**
     * Output list of taxonomy terms, in XML tag format, associated with a post
     *
     * @since 1.0.0
     */
    function wxr_post_taxonomy() {
        $post = get_post();

        $taxonomies = get_object_taxonomies( $post->post_type );
        if ( empty( $taxonomies ) )
            return;
        $terms = wp_get_object_terms( $post->ID, $taxonomies );

        foreach ( (array) $terms as $term ) {
            echo "\t\t<category domain=\"{$term->taxonomy}\" nicename=\"{$term->slug}\">" . wxr_cdata( $term->name ) . "</category>\n";
        }
    }

    /**
     *
     * @param bool   $return_me
     * @param string $meta_key
     * @return bool
     */
    function wxr_filter_postmeta( $return_me, $meta_key ) {
        if ( '_edit_lock' == $meta_key )
            $return_me = true;
        return $return_me;
    }
    add_filter( 'wxr_export_skip_postmeta', 'wxr_filter_postmeta', 10, 2 );

    echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . "\" ?>\n";

    ?>
    <!-- This is a Rolo slider  -->
    <rss version="2.0"
         xmlns:excerpt="http://wordpress.org/export/<?php echo WXR_VERSION; ?>/excerpt/"
         xmlns:content="http://purl.org/rss/1.0/modules/content/"
         xmlns:wfw="http://wellformedweb.org/CommentAPI/"
         xmlns:dc="http://purl.org/dc/elements/1.1/"
         xmlns:wp="http://wordpress.org/export/1.2/"
    >

        <channel>
            <pubDate><?php echo date( 'D, d M Y H:i:s +0000' ); ?></pubDate>
            <language><?php bloginfo_rss( 'language' ); ?></language>
            <wp:wxr_version><?php echo WXR_VERSION; ?></wp:wxr_version>

            <?php wxr_authors_list( $post_ids ); ?>

            <?php foreach ( $terms as $t ) : ?>
                <wp:term>
                    <wp:term_id><?php echo wxr_cdata( $t->term_id ); ?></wp:term_id>
                    <wp:term_taxonomy><?php echo wxr_cdata( $t->taxonomy ); ?></wp:term_taxonomy>
                    <wp:term_slug><?php echo wxr_cdata( $t->slug ); ?></wp:term_slug>
                    <wp:term_parent><?php echo wxr_cdata( $t->parent ? $terms[$t->parent]->slug : '' ); ?></wp:term_parent>
                    <?php wxr_term_name( $t );
                    wxr_term_description( $t );
                    wxr_term_meta( $t ); ?>
                </wp:term>
            <?php endforeach; ?>

            <?php
            /** This action is documented in wp-includes/feed-rss2.php */
            do_action( 'rss2_head' );
            ?>

            <?php if ( $post_ids ) {
                /**
                 * @global WP_Query $wp_query
                 */
                global $wp_query;

                // Fake being in the loop.
                $wp_query->in_the_loop = true;

                // Fetch 20 posts at a time rather than loading the entire table into memory.
                while ( $next_posts = array_splice( $post_ids, 0, 20 ) ) {
                    $where = 'WHERE ID IN (' . join( ',', $next_posts ) . ')';
                    $posts = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} $where" );

                    // Begin Loop.
                    foreach ( $posts as $post ) {
                        setup_postdata( $post );
                        $is_sticky = is_sticky( $post->ID ) ? 1 : 0;
                        ?>
                        <item>
                            <title><?php
                                /** This filter is documented in wp-includes/feed.php */
                                echo apply_filters( 'the_title_rss', $post->post_title );
                                ?></title>
                            <link><?php the_permalink_rss() ?></link>
                            <pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
                            <dc:creator><?php echo wxr_cdata( get_the_author_meta( 'login' ) ); ?></dc:creator>
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
                                echo wxr_cdata( apply_filters( 'the_content_export', $post->post_content ) );
                                ?></content:encoded>
                            <excerpt:encoded><?php
                                /**
                                 * Filters the post excerpt used for WXR exports.
                                 *
                                 * @since 2.6.0
                                 *
                                 * @param string $post_excerpt Excerpt for the current post.
                                 */
                                echo wxr_cdata( apply_filters( 'the_excerpt_export', $post->post_excerpt ) );
                                ?></excerpt:encoded>
                            <wp:post_id><?php echo intval( $post->ID ); ?></wp:post_id>
                            <wp:post_date><?php echo wxr_cdata( $post->post_date ); ?></wp:post_date>
                            <wp:post_date_gmt><?php echo wxr_cdata( $post->post_date_gmt ); ?></wp:post_date_gmt>
                            <wp:comment_status><?php echo wxr_cdata( $post->comment_status ); ?></wp:comment_status>
                            <wp:ping_status><?php echo wxr_cdata( $post->ping_status ); ?></wp:ping_status>
                            <wp:post_name><?php echo wxr_cdata( $post->post_name ); ?></wp:post_name>
                            <wp:status><?php echo wxr_cdata( $post->post_status ); ?></wp:status>
                            <wp:post_parent><?php echo intval( $post->post_parent ); ?></wp:post_parent>
                            <wp:menu_order><?php echo intval( $post->menu_order ); ?></wp:menu_order>
                            <wp:post_type><?php echo wxr_cdata( $post->post_type ); ?></wp:post_type>
                            <wp:post_password><?php echo wxr_cdata( $post->post_password ); ?></wp:post_password>
                            <wp:is_sticky><?php echo intval( $is_sticky ); ?></wp:is_sticky>
                            <?php	if ( $post->post_type == 'attachment' ) : ?>
                                <wp:attachment_url><?php echo wxr_cdata( wp_get_attachment_url( $post->ID ) ); ?></wp:attachment_url>
                            <?php 	endif; ?>
                            <?php 	wxr_post_taxonomy(); ?>
                            <?php
                            /**
                             * Action for hooking additional content to xml export file
                             */
                            do_action('pf_exporter_additional_xml_data', $post, $post->ID);
                            ?>
                            <?php	$postmeta = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->postmeta WHERE post_id = %d", $post->ID ) );
                            foreach ( $postmeta as $meta ) :
                                /**
                                 * Filters whether to selectively skip post meta used for WXR exports.
                                 *
                                 *
                                 * @since 1.0.0
                                 *
                                 * @param bool   $skip     Whether to skip the current post meta. Default false.
                                 * @param string $meta_key Current meta key.
                                 * @param object $meta     Current meta object.
                                 */
                                if ( apply_filters( 'pf_exporter_export_skip_postmeta', false, $meta->meta_key, $meta ) )
                                    continue;
                                ?>
                                <wp:postmeta>
                                    <wp:meta_key><?php echo wxr_cdata( $meta->meta_key ); ?></wp:meta_key>
                                    <wp:meta_value><?php echo wxr_cdata( $meta->meta_value ); ?></wp:meta_value>
                                </wp:postmeta>
                            <?php   endforeach;  ?>
                        </item>
                        <?php
                    }
                    /**
                     * Action for hooking additional content after item loop
                     */
                    do_action('pf_exporter_after_item_xml_data');
                }
            } ?>
        </channel>
    </rss>
    <?php
}