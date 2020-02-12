<?php
/**
 * Plugin Name:     Font Awesome Kit for Wordpress
 * Plugin URI:      https://www.divi-magazine.com
 * Description:     Add Font Awesome Kit code to WordPress easily. Nothing more, nothing less.
 * Version:         1.0
 * Author:          Andras Guseo | The Divi Magazine
 * Author URI:      https://www.divi-magazine.com
 * License:         GPL version 3 or any later version
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:     tdm-font-awesome-kit-for-wordpress
 *
 *     This plugin is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     any later version.
 *
 *     This plugin is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *     GNU General Public License for more details.
 */

namespace Tdm__Font_Awesome_Kit_for_WordPress;

if ( ! class_exists( 'Tdm__Font_Awesome_Kit_for_WordPress' ) ) {
	/**
	 * Extension main class, class begins loading on init() function.
	 */
	class Tdm__Font_Awesome_Kit_for_WordPress {

		/**
		 * Setup the plugin's properties.
		 */
		public function __construct() {

			// Load plugin textdomain
			load_plugin_textdomain( 'tdm-font-awesome-kit-for-wordpress', false, basename( dirname( __FILE__ ) ) . '/languages/' );

			// Add action links
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'tdm_plugin_action_links' ] );
			add_filter( 'plugin_row_meta', [ $this, 'tdm_plugin_row_meta' ], 10, 2 );

			// Initialize plugin options
			add_action( 'admin_init', [ $this, 'tdm_fakitforwp_initialize_plugin_options' ] );

			// Render plugin options page
			add_action( 'admin_menu', [ $this, 'tdm_fakitforwp_menu' ], 99 );
			
			// Uninstall
			register_uninstall_hook( __FILE__, 'uninstall_tdm_fakitforwp' );
		}

		/**
		 * Generates the 'Settings' link on the plugins page
		 *
		 * @param $links
		 *
		 * @return array
		 */
		function tdm_plugin_action_links( $links ) {
			$links[] = '<a href="' . esc_url( get_admin_url( null, 'admin.php?page=tdm_fakitforwp_options' ) ) . '">Settings</a>';

			return $links;
		}

		/**
		 * Generates the donation link for the plugin
		 *
		 * @param $links
		 * @param $file
		 *
		 * @return array
		 */
		function tdm_plugin_row_meta( $links, $file ) {
			if ( strpos( $file, 'tdm-fa-kit-for-wordpress.php' ) !== false ) {
				$new_links = array(
					'<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N6CX32P44TMQJ" target="_blank"><i class="fa fa-coffee"></i> Invite me for a coffee :)</a>'
				);
				$links     = array_merge( $links, $new_links );
			}

			return $links;
		}

		/**
		 * Handles option deletion on uninstall
		 */
		function uninstall_tdm_fakitforwp() {
			delete_option( 'tdm_fakitforwp_options' );
		}

		/**
		 * Adding the submenu page
		 */
		function tdm_fakitforwp_menu() {
			add_submenu_page(
				'options-general.php',            // The menu where it appears
				'Font Awesome Kit',               // The title to be displayed in the browser window for this page.
				'Font Awesome Kit',               // The text to be displayed for this menu item
				'administrator',                  // Which type of users can see this menu item
				'tdm_fakitforwp_options',         // The unique ID - that is, the slug - for this menu item
				[ $this, 'tdm_fakitforwp_options_display' ]  // The name of the function to call when rendering this menu's page
			);
		}

		/**
		 * Rendering the options page
		 */
		function tdm_fakitforwp_options_display() {
			?>
			<!-- Create a header in the default WordPress 'wrap' container -->
			<div class="wrap">

				<!-- Add the icon to the page -->
				<div id="icon-themes" class="icon32"></div>
				<h2>Font Awesome Kit for WordPress Options</h2>
				<p>version 1.0</p>

				<!-- Make a call to the WordPress function for rendering errors when settings are saved. -->
				<?php settings_errors(); ?>

				<!-- Create the form that will be used to render our options -->
				<form method="post" action="options.php">
					<?php
					settings_fields( 'tdm_fakitforwp_options' );
					do_settings_sections( 'tdm_fakitforwp_options' );
					submit_button();
					?>
				</form>

				<p>Please report any bugs to <a href="mailto:andras@divi-magazine.com">andras@divi-magazine.com</a>.</p>
				<p>If you would like to buy me a coffee,
					<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N6CX32P44TMQJ" target="_blank">click here</a>. :-)
				</p>

			</div><!-- /.wrap -->
			<?php
		}

		/**
		 * Provides a simple description for the General Options page.
		 *
		 * It is called from the 'tdm_fakitforwp_initialize_plugin_options' function by being passed as a parameter
		 * in the add_settings_section function.
		 */
		function tdm_fakitforwp_options_callback() {
			echo '<p>Paste the Font Awesome Kit code in the below field.</p>';
			echo '<p>Don\'t copy the full code, only copy the base of the file name, what you see in bold: <code>&lt;script src="https://kit.fontawesome.com/<strong>{uniquenum}</strong>.js"&gt;&lt;/script&gt;</code></p>';
			echo '<p>Don\'t have a Font Awesome Kit yet? You can <a href="https://fontawesome.com/start" target="_blank">create one here</a>.' . ' ' . 'If you already have one, you can <a href="https://fontawesome.com/kits" target="_blank">find it here</a>.' . '</p>';
		}

		/**
		 * Initializes the theme options page by registering the Sections, Fields, and Settings.
		 * This function is registered with the 'admin_init' hook.
		 */
		function tdm_fakitforwp_initialize_plugin_options() {

			// Check if the option exists. If not, add it.
			if ( false == get_option( 'tdm_fakitforwp_options' ) ) {
				add_option( 'tdm_fakitforwp_options' );
			} // end if

			// Register a section
			add_settings_section(
				'fontawesome_settings_section',            // ID used to identify this section and with which to register options
				'Instructions',                                        // Title to be displayed on the administration page
				[ $this, 'tdm_fakitforwp_options_callback' ], // Callback used to render the description of the section
				'tdm_fakitforwp_options'           // Page on which to add this section of options
			);

			add_settings_field(
				'fontawesome_kit_code',                    // ID used to identify the field throughout the theme
				'Font Awesome Kit Code',                   // The label to the left of the option interface element
				[ $this, 'tdm_fakitforwp_code_callback' ],            // The name of the function responsible for rendering the option interface
				'tdm_fakitforwp_options',                 // The page on which this option will be displayed
				'fontawesome_settings_section',            // The name of the section to which this field belongs
				array(                                     // The array of arguments to pass to the callback. In this case, just a description.
					'xxxPaste here the Font Awesome Kit code.<br/><em>Don\'t copy the full code, only copy the base of the file name. Don\'t have a code yet? You can <a href="https://use.fontawesome.com/start" target="_blank">get it here</a>.</em>'
				)
			);

			$args = array(
				'type' => 'string',
				'sanitize_callback' => 'tdm_fakitforwp_validate_input',
				'default' => NULL,
			);

			// Register the fields with WordPress
			register_setting(
				'tdm_fakitforwp_options',   // Settings group name
				'tdm_fakitforwp_options',   // Name of the option
				$args                       // Arguments
			);

		}

		/**
		 *
		 */
		function tdm_fakitforwp_code_callback() {
			$options = get_option( 'tdm_fakitforwp_options' );
			echo 'https://kit.fontawesome.com/<input type="text" id="fontawesome_kit_code" name="tdm_fakitforwp_options[fontawesome_kit_code]" value="' . ( $options != "" ? $options['fontawesome_kit_code'] : "" ) . '"  placeholder="Font Awesome Kit Code" />.js';
		}

		/**
		 * Input validation
		 * @param $input
		 *
		 * @return mixed
		 */
		function tdm_fakitforwp_validate_input( $input ) {
			// Create our array for storing the validated options
			$output = array();

			// Loop through each of the incoming options
			foreach ( $input as $key => $value ) {

				// Check to see if the current option has a value. If so, process it.
				if ( isset( $input[ $key ] ) ) {

					// Strip all HTML and PHP tags and properly handle quoted strings
					$output[ $key ] = strip_tags( stripslashes( $input[ $key ] ) );

				} // end if

			} // end foreach

			// Return the array processing any additional functions filtered by this action
			return apply_filters( 'tdm_fakitforwp_validate_input', $output, $input );

		}

	}
}

$abc = new Tdm__Font_Awesome_Kit_for_WordPress();