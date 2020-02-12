<?php
/**
 * Plugin Name: TDM: Font Awesome Kit for Wordpress
 * Plugin URI: https://www.divi-magazine.com
 * Description: Add Font Awesome Kit code to WordPress easily.
 * Version: 1.0
 * Author: Andras Guseo | The Divi Magazine
 * Author URI: https://www.divi-magazine.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/* ADDING SUBMENU */
function tdm_fakitforwp_menu() {
	add_submenu_page(
		'options-general.php',              // The menu where it appears
		'Font Awesome Kit',    // The title to be displayed in the browser window for this page.
		'Font Awesome',                     // The text to be displayed for this menu item
		'administrator',                    // Which type of users can see this menu item
		'tdm_fakitforwp_options',          // The unique ID - that is, the slug - for this menu item
		'tdm_fakitforwp_options_display',  // The name of the function to call when rendering this menu's page
	);
} // end tdm_fakitforwp_menu

add_action( 'admin_menu', 'tdm_fakitforwp_menu', 99 );

/* DISPLAY OPTIONS */
function tdm_fakitforwp_options_display() {
	?>
	<!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">

		<!-- Add the icon to the page -->
		<div id="icon-themes" class="icon32"></div>
		<h2>Font Awesome Kit for WordPress Options</h2>
		<p>version 2.4</p>
		<!-- Make a call to the WordPress function for rendering errors when settings are saved. -->
		<?php settings_errors(); ?>

		<!-- Create the form that will be used to render our options -->
		<form method="post" action="options.php">
			<?php
			settings_fields( 'tdm_fakitforwp_display_options' );
			do_settings_sections( 'tdm_fakitforwp_display_options' );
			submit_button();
			?>
		</form>

		<p>Please report any bugs to <a href="mailto:andras@divi-magazine.com">andras@divi-magazine.com</a>.</p>
		<p>Let me know if you need help or customization, we can surely work out something. ;-)</p>
		<p>If you would like to buy me a coffee,
			<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N6CX32P44TMQJ" target="_blank">click here</a>. :-)
		</p>

	</div><!-- /.wrap -->
	<?php
} // end tdm_fakitforwp_options_display

// ADD SCRIPTS TO WP_HEAD() AND ADMIN_HEAD()
function child_theme_head_script() {
	$options = get_option( 'tdm_fakitforwp_display_options' );
	if ( isset( $options['enable_fontawesome'] ) && $options['enable_fontawesome'] ) {
		?>
		<script src="https://kit.fontawesome.com/<?php echo $options['fontawesome_embed_code'] ?>.js" crossorigin="anonymous"></script>
	<?php }
}

add_action( 'wp_head', 'child_theme_head_script' );
//add_action( 'admin_head', 'child_theme_head_script' );

/* SETTING REGISTRATION */
/**
 * Initializes the theme options page by registering the Sections, Fields, and Settings.
 * This function is registered with the 'admin_init' hook.
 */
function tdm_fakitforwp_initialize_plugin_options() {

	// Check if the option exists. If not, add it.
	if ( false == get_option( 'tdm_fakitforwp_display_options' ) ) {
		add_option( 'tdm_fakitforwp_display_options' );
	} // end if

	// REGISTER A SECTION
	add_settings_section(
		'fontawesome_settings_section',            // ID used to identify this section and with which to register options
		'',                                        // Title to be displayed on the administration page
		'tdm_fakitforwp_general_options_callback', // Callback used to render the description of the section
		'tdm_fakitforwp_display_options'            // Page on which to add this section of options
	);

	// INTRODUCE THE FIELDS
	add_settings_field(
		'enable_fontawesome',                        // ID used to identify the field throughout the theme
		'Enable Font Awesome',                        // The label to the left of the option interface element
		'tdm_toggle_fakitforwp_callback',            // The name of the function responsible for rendering the option interface
		'tdm_fakitforwp_display_options',            // The page on which this option will be displayed
		'fontawesome_settings_section',                // The name of the section to which this field belongs
		array(                                        // The array of arguments to pass to the callback. In this case, just a description.
			'Activate this setting to load Font Awesome for your Divi Builder powered site.<br/><em>If you are loading Font Awesome through another plugin already (like WP Font Awesome, Better Font Awesome, Font Awesome Icons etc.), then disable this option, otherwise it will be loaded more times wasting resources. If you are using this current plugin only, then enable it.</em>'
		)
	);

	add_settings_field(
		'fontawesome_embed_code',                    // ID used to identify the field throughout the theme
		'Font Awesome Embed Code',                    // The label to the left of the option interface element
		'tdm_fakitforwp_embed_code_callback',            // The name of the function responsible for rendering the option interface
		'tdm_fakitforwp_display_options',            // The page on which this option will be displayed
		'fontawesome_settings_section',                // The name of the section to which this field belongs
		array(                                        // The array of arguments to pass to the callback. In this case, just a description.
			'Paste here the Font Awesome Embed Code that you received by email.<br/><em>Don\'t copy the full code, only copy the base of the file name. Don\'t have a code yet? You can <a href="https://fontawesome.com/v4.7.0/get-started/" target="_blank">get it here</a>.</em>'
		)
	);

	/* Leaving here for future option
	add_settings_field(
		'load_anyway',
		'Load styles even if disabled',
		'tdm_toggle_load_anyway_callback',
		'tdm_fakitforwp_display_options',
		'fontawesome_settings_section',
		array( 'If this setting is activated then the styles will be loaded even if the first option is disabled. It\'s recommended to turn this on if you use another plugin to load Font Awesome (like listed above) and you want to use the styling options below.' )
	);
	*/

	// REGISTER THE FIELDS WITH WORDPRESS
	register_setting(
		'tdm_fakitforwp_display_options',
		'tdm_fakitforwp_display_options',
		'tdm_fakitforwp_validate_input'
	);

} // END tdm_fakitforwp_initialize_plugin_options

add_action( 'admin_init', 'tdm_fakitforwp_initialize_plugin_options' );

/* VALIDATE INPUT */
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

} // END tdm_fakitforwp_validate_input


/* SECTION CALLBACKS */
/**
 * This function provides a simple description for the General Options page.
 *
 * It is called from the 'tdm_fakitforwp_initialize_plugin_options' function by being passed as a parameter
 * in the add_settings_section function.
 */

function tdm_fakitforwp_general_options_callback() {
	echo '<p></p>';
} // END tdm_fakitforwp_general_options_callback


/* RENDER THE INTERFACE ELEMENTS */
function tdm_toggle_fakitforwp_callback( $args ) {

	// READ THE OPTIONS COLLECTION
	$options = get_option( 'tdm_fakitforwp_display_options' );

	// UPDATE THE ATTRIBUTE TO ACCESS THIS ELEMENT'S ID IN THE CONTEXT OF THE OPTIONS ARRAY
	// WE ALSO ACCESS THE ENABLE_FONTAWESOME ELEMENT OF THE OPTIONS COLLECTION IN THE CALL TO THE CHECKED() HELPER FUNCTION
	$html = '<input type="checkbox" id="enable_fontawesome" name="tdm_fakitforwp_display_options[enable_fontawesome]" value="1" ' . checked( 1, ( $options != "" ? $options['enable_fontawesome'] : "" ), false ) . '/>';

	// TAKE THE FIRST ARGUMENT OF THE ARRAY AND ADD IT TO A LABEL NEXT TO THE CHECKBOX
	$html .= '<label for="enable_fontawesome"> ' . $args[0] . '</label>';

	echo $html;

} // END tdm_toggle_fakitforwp_callback

function tdm_fakitforwp_embed_code_callback() {
	$options = get_option( 'tdm_fakitforwp_display_options' );
	echo 'https://kit.fontawesome.com/<input type="text" id="fontawesome_embed_code" name="tdm_fakitforwp_display_options[fontawesome_embed_code]" value="' . ( $options != "" ? $options['fontawesome_embed_code'] : "" ) . '" crossorigin="anonymous" placeholder="Font Awesome Embed Code" />.js   (You can get the code <a href="https://fontawesome.com/v4.7.0/get-started/" target="_blank">here</a>.</em>)<br/>';
	echo 'Paste here the Font Awesome Embed Code that you received by email from the Font Awesome guys. This field is required, it will not work without this.<br/><em>Don\'t copy the full code, only copy the base of the file name, what you see in bold: &lt;script src="https://use.fontawesome.com/<strong>{uniquenum}</strong>.js"&gt;&lt;/script&gt;<br/>Don\'t have a code yet? You can <a href="http://fontawesome.io/get-started/" target="_blank">get it here</a>.</em>';

} // END tdm_fakitforwp_embed_code_callback

function tdm_toggle_load_anyway_callback( $args ) {

	$options = get_option( 'tdm_fakitforwp_display_options' );
	$html    = '<input type="checkbox" id="load_anyway" name="tdm_fakitforwp_display_options[load_anyway]" value="1" ' . checked( 1, ( $options != "" ? $options['load_anyway'] : "" ), false ) . '/>';
	$html    .= '<label for="load_anyway"> ' . $args[0] . '</label>';
	echo $html;

} // END tdm_toggle_load_anyway_callback

function tdm_toggle_keep_settings_callback( $args ) {

	$options = get_option( 'tdm_fakitforwp_display_options' );
	$html    = '<input type="checkbox" id="keep_settings" name="tdm_fakitforwp_display_options[keep_settings]" value="1" ' . checked( 1, ( $options != "" ? $options['keep_settings'] : "" ), false ) . '/>';
	$html    .= '<label for="keep_settings"> ' . $args[0] . '</label>';
	echo $html;

} // END tdm_toggle_keep_settings_callback

function tdm_toggle_override_tinymce_callback( $args ) {

	$options = get_option( 'tdm_fakitforwp_display_options' );
	$html    = '<input type="checkbox" id="override_tinymce" name="tdm_fakitforwp_display_options[override_tinymce]" value="1" ' . checked( 1, ( $options != "" ? $options['override_tinymce'] : "" ), false ) . '/>';
	$html    .= '<label for="override_tinymce"> ' . $args[0] . '</label>';
	echo $html;

} // END tdm_toggle_override_tinymce_callback

function list_left_margin_input_element_callback() {
	$options = get_option( 'tdm_fakitforwp_display_options' );
	echo '<input type="text" id="list_left_margin" name="tdm_fakitforwp_display_options[list_left_margin]" value="' . ( $options != "" ? $options['list_left_margin'] : "" ) . '" placeholder="E.g. 10px or 1.5em" /> ';
	echo '<em>If empty, Font Awesome default is used: 2.14286em</em>';

} // END list_left_margin_input_element_callback

function list_right_margin_input_element_callback() {
	$options = get_option( 'tdm_fakitforwp_display_options' );
	echo '<input type="text" id="list_right_margin" name="tdm_fakitforwp_display_options[list_right_margin]" value="' . ( $options != "" ? $options['list_right_margin'] : "" ) . '" placeholder="E.g. 10px or 1.5em" />';

} // END list_right_margin_input_element_callback

function list_top_margin_input_element_callback() {
	$options = get_option( 'tdm_fakitforwp_display_options' );
	echo '<input type="text" id="list_top_margin" name="tdm_fakitforwp_display_options[list_top_margin]" value="' . ( $options != "" ? $options['list_top_margin'] : "" ) . '" placeholder="E.g. 10px or 1.5em" />';

} // END list_top_margin_input_element_callback

function list_bottom_margin_input_element_callback() {
	$options = get_option( 'tdm_fakitforwp_display_options' );
	echo '<input type="text" id="list_bottom_margin" name="tdm_fakitforwp_display_options[list_bottom_margin]" value="' . ( $options != "" ? $options['list_bottom_margin'] : "" ) . '" placeholder="E.g. 10px or 1.5em" />';

} // END list_bottom_margin_input_element_callback

function item_distance_from_bullet_input_element_callback() {
	$options = get_option( 'tdm_fakitforwp_display_options' );
	echo '<input type="text" id="item_distance_from_bullet" name="tdm_fakitforwp_display_options[item_distance_from_bullet]" value="' . ( $options != "" ? $options['item_distance_from_bullet'] : "" ) . '" placeholder="E.g. 10px or 1.5em" />';

} // END item_distance_from_bullet_input_element_callback

function distance_between_lines_input_element_callback() {
	$options = get_option( 'tdm_fakitforwp_display_options' );
	echo '<input type="text" id="distance_between_lines" name="tdm_fakitforwp_display_options[distance_between_lines]" value="' . ( $options != "" ? $options['distance_between_lines'] : "" ) . '" placeholder="E.g. 10px or 1.5em" />';

} // END distance_between_lines_input_element_callback


/* ADD ACTION LINKS */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'tdm_plugin_action_links' );
function tdm_plugin_action_links( $links ) {
	$links[] = '<a href="' . esc_url( get_admin_url( null, 'admin.php?page=tdm_fakitforwp_options' ) ) . '">Settings</a>';

	return $links;
}

add_filter( 'plugin_row_meta', 'tdm_plugin_row_meta', 10, 2 );
function tdm_plugin_row_meta( $links, $file ) {
	if ( strpos( $file, 'tdm-fontawesome-for-divi.php' ) !== false ) {
		$new_links = array(
			'<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N6CX32P44TMQJ" target="_blank"><i class="fa fa-coffee"></i> Invite me for a coffee :)</a>'
		);
		$links     = array_merge( $links, $new_links );
	}

	return $links;
}

/* UNINSTALL */
if ( ! function_exists( 'uninstall_tdm_fakitforwp_for_divi' ) ) {
	function uninstall_tdm_fakitforwp_for_divi() {
		$options = get_option( 'tdm_fakitforwp_display_options' );
		if ( ! isset( $options['keep_settings'] ) ) {
			delete_option( 'tdm_fakitforwp_display_options' );
		}
	}
}
register_uninstall_hook( __FILE__, 'uninstall_tdm_fakitforwp_for_divi' );

/* INCLUDE DASHBOARD FEED */
include_once( 'dashboard/dashboard-feed.php' );


?>