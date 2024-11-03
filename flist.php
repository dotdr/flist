<?php
// with thanks to https://wppb.me/
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://itmustbebunnies.com.au/filelist
 * @since             1.1.0
 * @package           Flist
 *
 * @wordpress-plugin
 * Plugin Name:       File List
 * Plugin URI:        https://itmustbebunnies.com.au/filelist
 * Description:       Flist the file lister
 * Version:           1.0.0
 * Author:            DrDot
 * Author URI:        https://itmustbebunnies.com.au/filelist/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       flist
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
   die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'FLIST_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-flist-activator.php
 */
function activate_flist() {
   require_once plugin_dir_path( __FILE__ ) . 'includes/class-flist-activator.php';
   Flist_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-flist-deactivator.php
 */
function deactivate_flist() {
   require_once plugin_dir_path( __FILE__ ) . 'includes/class-flist-deactivator.php';
   Flist_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_flist' );
register_deactivation_hook( __FILE__, 'deactivate_flist' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-flist.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_flist() {

   $plugin = new Flist();
   $plugin->run();

}
run_flist();


add_action( 'init', 'register_shortcodes');  // the shortcode is [flist]
function register_shortcodes(){
   add_shortcode('flist', 'flist_function');
}

require plugin_dir_path( __FILE__ ) . "flist_lister.php";
