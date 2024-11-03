<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://itmustbebunnies.com.au/filelist
 * @since      1.0.0
 *
 * @package    Flist
 * @subpackage Flist/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Flist
 * @subpackage Flist/admin
 * @author     DrDot <dot_dr@hotmail.com>
 */
class Flist_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/flist-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/flist-admin.js', array( 'jquery' ), $this->version, false );

	}

}

define("MAXFLIST",2);

// Hook to add admin css styles
add_action( 'admin_enqueue_styles', 'enqueue_styles');

// Hook to add admin menu
add_action('admin_menu', 'flist_plugin_menu');

// Hook to register settings
add_action('admin_init', 'flist_plugin_settings');


function flist_plugin_menu() {
    add_menu_page('Flist', 'Flist the File Lister', 'manage_options', 'flist-plugin', 'flist_plugin_page');
}

function flist_plugin_page() {
    $flist_field_set_count = get_option('flist_field_set_count', 1);
    ?>
    <div class="wrap">
        <h1>Flist the File Lister Settings</h1>
        <form method="post" action="options.php" id="flist-plugin-form">
            <?php
	            settings_fields('flist_plugin_options_group');
	            do_settings_sections('flist-plugin');
	            submit_button();
            ?>
            <label for="flist_field_set_count">flist_field_set_count</label>
            <input type="text" id="flist_field_set_count" name="flist_field_set_count" value="<?php echo $flist_field_set_count; ?>" />
        </form>
        <button id="add-field-set" class="button">Add New Set of Fields</button>
    </div>
    <?php
}


function flist_plugin_settings() {
	$flist_field_set_count = get_option('flist_field_set_count', 1);
	// setup all items for list/update
    for ($i=1; $i <= MAXFLIST; $i++) {
		$a = get_option("flist_plugin_option1_$i",-1);
        if ($a == -1 && $i == 1) {  //nothing saved set default values, no need to cache these options
			update_option("flist_plugin_option1_$i","100",false);
			update_option("flist_plugin_option2_$i","/uploads/flist/",false);
			update_option("flist_plugin_option3_$i","$i",false);
			update_option("flist_plugin_option4_$i",'off',false);
			update_option("flist_plugin_option5_$i",'off',false);
        } 
			
		register_setting('flist_plugin_options_group', "flist_plugin_option1_$i");
		register_setting('flist_plugin_options_group', "flist_plugin_option2_$i");
		register_setting('flist_plugin_options_group', "flist_plugin_option3_$i");
		register_setting('flist_plugin_options_group', "flist_plugin_option4_$i");
		register_setting('flist_plugin_options_group', "flist_plugin_option5_$i");

		add_settings_section("flist_plugin_main_section_$i", "Flist Folder $i", null, 'flist-plugin');

		add_settings_field("flist_plugin_option1_$i", 'Thumbnail Size', "flist_plugin_option1_callback_$i", 'flist-plugin', "flist_plugin_main_section_$i");
		add_settings_field("flist_plugin_option2_$i", 'Folder', "flist_plugin_option2_callback_$i", 'flist-plugin', "flist_plugin_main_section_$i");
		add_settings_field("flist_plugin_option3_$i", 'ShortCode ID', "flist_plugin_option3_callback_$i", 'flist-plugin', "flist_plugin_main_section_$i");			
		add_settings_field("flist_plugin_option4_$i", 'Hide Size', "flist_plugin_option4_callback_$i", 'flist-plugin', "flist_plugin_main_section_$i");
		add_settings_field("flist_plugin_option5_$i", 'Hide Name', "flist_plugin_option5_callback_$i", 'flist-plugin', "flist_plugin_main_section_$i");			
			
	}

	// save flist_field_set_count
    register_setting('flist_plugin_options_group', 'flist_field_set_count');
    add_settings_field("flist_field_set_count", "flist_field_set_count", "", 'flist-plugin', "$flist_field_set_count");

}

// callback function for number of items in list
function flist_field_set_count_callback() {
return;
	for ($i=1; $i <= $flist_field_set_count; $i++) {
		delete_option("flist_plugin_option1_$i");
		delete_option("flist_plugin_option2_$i");
		delete_option("flist_plugin_option3_$i");
		delete_option("flist_plugin_option4_$i");
		delete_option("flist_plugin_option5_$i");
	}
}

// callback functions for field sets
for ($i=1; $i <= MAXFLIST; $i++) {
	eval("   	
		function flist_plugin_option1_callback_$i() {
			\$option = wp_strip_all_tags(get_option('flist_plugin_option1_$i'));
			echo \"<input style='width: 50px;' type='text' name='flist_plugin_option1_$i' value='\$option' /> &emsp; Thumbnail display size in px, thumbnails are always square.\";
		}

		function flist_plugin_option2_callback_$i() {
			\$option = wp_strip_all_tags(get_option('flist_plugin_option2_$i'));
			echo \"<input style='width: 250px;' type='text' name='flist_plugin_option2_$i' value='\$option' /> &emsp; Physical location relative to wp-content  ..  and . are stripped. \";
		}

		function flist_plugin_option3_callback_$i() {
			\$option = wp_strip_all_tags(get_option('flist_plugin_option3_$i'));
			echo \"<input style='width: 50px;' type='text' name='flist_plugin_option3_$i' value='\$option' /> &emsp; The shortcode identifier.  Only needs the numeric.   eg [flist=2] the shortcode id here is 2  \";
		}
		
		function flist_plugin_option4_callback_$i() {
			\$option = wp_strip_all_tags(get_option('flist_plugin_option4_$i'));
			if ( get_option('flist_plugin_option4_$i') == 'on' ) { \$option='checked'; } else { \$option='unchecked'; }
			echo \"<input type='checkbox' name='flist_plugin_option4_$i' \$option /> &emsp; Hide size\";
		}

		function flist_plugin_option5_callback_$i() {
			\$option = wp_strip_all_tags(get_option('flist_plugin_option5_$i'));
			if ( get_option('flist_plugin_option5_$i') == 'on' ) { \$option='checked'; } else { \$option='unchecked'; }
			echo \"<input type='checkbox' name='flist_plugin_option5_$i' \$option /> &emsp; Hide name\";
		}

	");
}


?>