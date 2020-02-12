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

		function uninstall_tdm_fakitforwp() {
			delete_option( 'tdm_fakitforwp_display_options' );
		}
	}
}

$abc = new Tdm__Font_Awesome_Kit_for_WordPress();