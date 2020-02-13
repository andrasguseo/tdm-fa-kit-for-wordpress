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
	 * Main Class
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

			// Add script to head
			add_action( 'wp_head', [ $this, 'child_theme_head_script' ] );
			add_action( 'admin_head', [ $this, 'child_theme_head_script' ] );

			// Uninstall
			//register_uninstall_hook( __FILE__, [ $this, 'uninstall_tdm_fakitforwp' ] );
			register_activation_hook( __FILE__, [ $this, 'tdm_fakitforwp_activate' ] );
		}

		/**
		 * Generates the 'Settings' link on the plugins page
		 *
		 * @param $links
		 *
		 * @return array
		 */
		function tdm_plugin_action_links( $links ) {
			$links[] = '<a href="' . esc_url( get_admin_url( null, 'admin.php?page=tdm_fakitforwp' ) ) . '">' . esc_html__( 'Settings', 'tdm-font-awesome-kit-for-wordpress' ) . '</a>';

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
				$new_links = [
					'<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N6CX32P44TMQJ" target="_blank"><i class="fa fa-coffee"></i> ' . esc_html__( 'Invite me for a coffee :)', 'tdm-font-awesome-kit-for-wordpress' ) . '</a>'
				];
				$links     = array_merge( $links, $new_links );
			}

			return $links;
		}

		/**
		 * Handles option deletion on uninstall
		 */
		function tdm_fakitforwp_activate(){
			register_uninstall_hook( __FILE__, 'uninstall_tdm_fakitforwp' );
		}
		function uninstall_tdm_fakitforwp() {
			delete_option( 'tdm_fakitforwp_options' );
		}

		/**
		 * Adding the submenu page
		 */
		function tdm_fakitforwp_menu() {
			add_submenu_page(
				'options-general.php',                          // The menu where it appears
				'Font Awesome Kit',                             // The title to be displayed in the browser window for this page.
				'Font Awesome Kit',                             // The text to be displayed for this menu item
				'administrator',                                // Which type of users can see this menu item
				'tdm_fakitforwp',                               // The unique ID - that is, the slug - for this menu item
				[ $this, 'tdm_fakitforwp_options_display' ]     // The name of the function to call when rendering this menu's page
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
				<h2><?php esc_html_e( 'Font Awesome Kit for WordPress Options', 'tdm-font-awesome-kit-for-wordpress' ); ?></h2>
				<p><?php esc_html_e( 'version 1.0', 'tdm-font-awesome-kit-for-wordpress' ); ?></p>

				<!-- Make a call to the WordPress function for rendering errors when settings are saved. -->
				<?php settings_errors(); ?>

				<!-- Create the form that will be used to render our options -->
				<form method="post" action="options.php">
					<?php
					settings_fields( 'tdm_fakitforwp_options_group' );
					do_settings_sections( 'tdm_fakitforwp_options_page' );
					submit_button();
					?>
				</form>

				<p><?php printf( esc_html__( 'Please report any bugs to %s.', 'tdm-font-awesome-kit-for-wordpress' ), '<a href="mailto:andras@divi-magazine.com">andras@divi-magazine.com</a>' ); ?></p>
				<p><?php printf( esc_html__( 'If you would like to buy me a coffee, %sclick here%s. :-)', 'tdm-font-awesome-kit-for-wordpress' ), '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N6CX32P44TMQJ" target="_blank">', '</a>' ); ?>
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
			$html  = '<p>' . esc_html__( 'Paste the Font Awesome Kit code in the below field.', 'tdm-font-awesome-kit-for-wordpress' ) . '</p>';
			$html .= '<p>' . esc_html__( 'Don\'t copy the full code, only copy the base of the file name, what you see in bold:', 'tdm-font-awesome-kit-for-wordpress' ) . ' <code>&lt;script src="https://kit.fontawesome.com/<strong>{uniquenum}</strong>.js"&gt;&lt;/script&gt;</code></p>';
			$html .= '<p>' . sprintf( esc_html__( 'Don\'t have a Font Awesome Kit yet? You can %screate one here%s.', 'tdm-font-awesome-kit-for-wordpress' ), '<a href="https://fontawesome.com/start" target="_blank">', '</a>' ) . ' ';
			$html .= sprintf( esc_html__( 'If you already have one, you can %sfind it here%s.', 'tdm-font-awesome-kit-for-wordpress' ), '<a href="https://fontawesome.com/kits" target="_blank">', '</a>' ) . '</p>';

			echo $html;
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
				'fontawesome_settings_section',                                 // ID used to identify this section and with which to register options
				esc_html__( 'Instructions', 'tdm-font-awesome-kit-for-wordpress' ),     // Title to be displayed on the administration page
				[ $this, 'tdm_fakitforwp_options_callback' ],                   // Callback used to render the description of the section
				'tdm_fakitforwp_options_page'                                   // Page on which to add this section of options
			);

			add_settings_field(
				'fontawesome_kit_code',                                                 // ID used to identify the field throughout the theme
				esc_html__( 'Font Awesome Kit Code', 'tdm-font-awesome-kit-for-wordpress' ),    // The label to the left of the option interface element
				[ $this, 'tdm_fakitforwp_code_callback' ],                              // The name of the function responsible for rendering the option interface
				'tdm_fakitforwp_options_page',                                          // The page on which this option will be displayed
				'fontawesome_settings_section'                                          // The name of the section to which this field belongs
			);

			// Register the fields with WordPress
			register_setting(
				'tdm_fakitforwp_options_group',     // Settings group name
				'tdm_fakitforwp_options',           // Name of the option
				'tdm_fakitforwp_validate_input'     // Arguments
			);

		}

		/**
		 * Renders the option
		 */
		function tdm_fakitforwp_code_callback() {
			$options = get_option( 'tdm_fakitforwp_options' );
			echo '<p>https://kit.fontawesome.com/<input type="text" id="fontawesome_kit_code" name="tdm_fakitforwp_options[fontawesome_kit_code]" value="' . ( $options != "" ? $options['fontawesome_kit_code'] : "" ) . '"  placeholder="' . esc_html__( 'Font Awesome Kit Code', 'tdm-font-awesome-kit-for-wordpress' ) . '" />.js</p>';

			if( ! empty( $options['fontawesome_kit_code'] ) ) {
				echo '<p><i class="fab fa-font-awesome-alt fa-2x" style="vertical-align: middle;"></i> &lt;-- ' . esc_html__( 'If you see the Font Awesome flag here, the code works.', 'tdm-font-awesome-kit-for-wordpress' ) . '</p>';
			}
		}

		/**
		 * Adds the script to the header if option exists
		 */
		function child_theme_head_script() {
			$options = get_option( 'tdm_fakitforwp_options' );
			if ( isset( $options['fontawesome_kit_code'] ) && ! empty( $options['fontawesome_kit_code'] ) ) {
				?>
				<script src="https://kit.fontawesome.com/<?php echo $options['fontawesome_kit_code'] ?>.js" crossorigin="anonymous"></script>
			<?php }
		}

		/**
		 * Input validation
		 * @param $input
		 *
		 * @return mixed
		 */
		function tdm_fakitforwp_validate_input( $input ) {
			// Create our array for storing the validated options
			$output = [];

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

$tdm_fakitforwp = new Tdm__Font_Awesome_Kit_for_WordPress();