<?php
//add to menu dashboard
if( is_admin() ) {
  add_action( 'admin_menu', 'rolo_lcns' );
}

function rolo_lcns() {
  // subpage
  add_submenu_page( 'edit.php?post_type=rolo_slider', __( 'Licenses', 'rolo-slider' ), __( 'Licenses', 'rolo-slider' ), 'manage_options','ed-licencing', 'rolo_license_page' );
}
  
function rolo_license_page() {
  ?>
  <div class="wrap">
    <h2><?php _e('Addons Licenses', 'rolo-slider'); ?></h2>
    <p><?php _e('Once you install Addon(s) the license fields will be added.', 'rolo-slider'); ?></p>
    <form method="post" action="options.php">
    
      <?php settings_fields('rolo_license'); ?>
      <?php settings_fields('rolo_license_status'); ?>
      
      <table class="form-table">
        <tbody>
          <?php do_action( 'rolo_license_row' ); ?>
          
        </tbody>
      </table>  
      <?php //submit_button(); ?>
      <a id="lic-subm" style="display: inline-block;margin-top: 30px;padding: 5px 20px;background: rgb(43, 162, 13);border-radius: 3px;color: #fff;cursor: pointer">Save</a>
    </form>
  <?php
}

function rolo_reg_reg() {
  // creates our settings in the options table
  register_setting('rolo_license', 'rolo_license_key', 'rolo_sant_license' );
  register_setting('rolo_license_status', 'rolo_status_license_key', 'rolo_sant_license' );
}
add_action('admin_init', 'rolo_reg_reg');

function rolo_sant_license( $new ) {
  $old = get_option( 'rolo_license_key' );
  if( $old && $old != $new ) {
    delete_option( 'rolo_license_status' ); 
  }
  return $new;
}

function rolo_reg_activate_license($name, $license, $addon_key) { 

    $api_params = array( 
      'edd_action'=> 'activate_license', 
      'license'   => $license, 
      'item_name' => urlencode( $name ),
      'url'       => home_url()
    );
    
    $response = wp_remote_post( PF_STORE_URL, array(
      'timeout'   => 15,
      'sslverify' => false,
      'body'      => $api_params
    ) ); 

    if ( is_wp_error( $response ) )
      return false;

    $license_data = json_decode( wp_remote_retrieve_body( $response ) ); 
    

    update_option( $addon_key, $license_data->license );

}

function rolo_reg_check_license($addon_key, $name) {
    $store_url = PF_STORE_URL;
    $item_name = $name;
    $license = get_option( $addon_key );
    $api_params = array(
        'edd_action' => 'check_license',
        'license' => $license,
        'item_name' => urlencode( $item_name )
    );
    $response = wp_remote_get( add_query_arg( $api_params, $store_url ), array( 'timeout' => 15, 'sslverify' => false ) );
    if ( is_wp_error( $response ) )
        return false;
    $license_data = json_decode( wp_remote_retrieve_body( $response ) );
    $addon_key = str_replace( '_key', '_status', $addon_key );
    if( $license_data->license == 'expired' ) { 
        update_option( $addon_key, 'expired' );
    } elseif( $license_data->license == 'invalid' ) {
        update_option( $addon_key, 'invalid' );
    } elseif( $license_data->license == 'inactive' ) {
        update_option( $addon_key, 'inactive' );
    }

    rolo_reg_activate_license($name, $license, $addon_key);
}

function rolo_license_check() {
  $check = false;
  $addons = array();
  $woo = get_option( 'woo_slider_license_key' );
  $posts = get_option( 'posts_slider_license_key' );
  $layer = get_option( 'layer_colors_license_key' );
  $bundle = get_option( 'rolo_bundle_license_key' );
  $data = array(
      'posts' => array(
         'name' => 'Posts Slider',
         'key' => 'posts_slider_license_key'
      ),
      'woo' => array(
         'name' => 'WooCommerce Products Slider',
         'key' => 'woo_slider_license_key'
      ),
      'layercolor' => array(
         'name' => 'Layer Colors',
         'key' => 'layer_colors_license_key'
      ),
      'bundle' => array(
         'name' => 'Rolo Slider Bundle',
         'key' => 'rolo_bundle_license_key'
      )
  );
  if( get_option( 'woo_slider_license_key' ) || get_option( 'posts_slider_license_key' ) || get_option( 'layer_colors_license_key' ) || get_option( 'rolo_bundle_license_key' ) ) {
     $check = true;
     if( $posts ) {
        array_push( $addons, 'posts' );   
     }
     if( $woo ) {
        array_push( $addons, 'woo' ); 
     }
     if( $layer ) {
        array_push( $addons, 'layercolor' ); 
     }
     if( $bundle ) {
        array_push( $addons, 'bundle' ); 
     }
  }

  if( $check ) {
    foreach( $addons as $addon ) {
      $addon_key = $data[$addon]['key'];
      $name = $data[$addon]['name'];
      rolo_reg_check_license($addon_key, $name);
    }
  }
  
} 
add_action('admin_init', 'rolo_license_check');

function rolo_save_license() {
    $data = isset($_POST['data']) ? $_POST['data'] : '';
    
    if( $data ) {
      foreach( $data as $addon ) {
        update_option( $addon['key'], $addon['val'] );
      }
    } 

    wp_die();
}
add_action('wp_ajax_rolo_save_license', 'rolo_save_license');

function rolo_license_ajax() {
  ?>
    <script type="text/javascript">
       jQuery(document).ready(function($){
          $data = {};
          $('#lic-subm').on('click', function(){
              $('.regular-text').each(function(){
                 var $this = $(this);
                 var $val = $this.val();
                 var $key = $this.attr('name');
                 $data[$key] = {
                  val: $val,
                  key: $key
                 }
              });
              $('#lic-subm').text('Saving...');
              var data = {
                'action': 'rolo_save_license',
                'data': $data
              }
              jQuery.post(ajaxurl, data, function(response) {
                location.reload(true);
              })
          });
       });
    </script>
  <?php
}
add_action( 'admin_footer', 'rolo_license_ajax' );

?>