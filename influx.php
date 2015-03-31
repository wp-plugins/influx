<?php
/*
Plugin Name: Influx
Plugin URI: https://influxhq.com/
Description: Integrates Influx fitness business software into your WordPress site.
Version: 0.1
Author: S Mayo
Author URI: http://influxhq.com/
*/

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

add_action('admin_menu', 'influx_menu');
add_action('admin_init', 'influx_settings' );
add_action('init', 'influx_shortcodes' );
add_action('wp_enqueue_scripts','influx_enqueue_dependencies');

function influx_menu() {
  add_menu_page('Influx Settings', 'Influx', 'administrator', 'influx-settings', 'influx_settings_page', 'dashicons-admin-generic');
}

function influx_settings() {
  register_setting( 'influx-settings-group', 'influx_slug' );
}
function influx_shortcodes() {
  add_shortcode('influx_planner', 'influx_shortcode_planner' );
}

function influx_settings_page() {
  // Our settings form
?>
<div class="wrap">
<h2>Influx Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'influx-settings-group' ); ?>
    <?php do_settings_sections( 'influx-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Slug Name</th>
        <td><input type="text" name="influx_slug" value="<?php echo esc_attr( get_option('influx_slug') ); ?>" /></td>
        </tr>
    </table>
    <?php submit_button(); ?>

</form>
</div>
<?php
}

function influx_enqueue_dependencies(){
  $plugin_path = plugin_dir_url( __FILE__ );
  $version = '0.1';
  // Enqueue the scripts if not already...
  if ( !wp_script_is( 'influx', 'enqueued' ) ) {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'influx', '//embed.influx.ws/embedinflux.js', array( 'jquery'), $version );
  }
}

function influx_shortcode_planner() {
  $slug = esc_attr( get_option('influx_slug') );
  $output = "<script type=\"text/javascript\">Influx.planner({slug:'" . $slug . "'});</script>";
  if(empty($slug)){$output = '<p>Cannot show planner: Influx slug setting is empty.</p>';}
  return $output;
}
?>