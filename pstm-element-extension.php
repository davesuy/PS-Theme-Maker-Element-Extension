<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/davesuy
 * @since             1.0.0
 * @package           Pstm_Element_Extension
 *
 * @wordpress-plugin
 * Plugin Name:       PS Theme Maker Element Extension
 * Plugin URI:        https://github.com/davesuy/PS-Theme-Maker-Element-Extension
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Dave Ramirez
 * Author URI:        https://github.com/davesuy
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pstm-element-extension
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$theme = wp_get_theme();


if ( 'PS Theme Maker' == $theme->name || 'PS Theme Maker (BETA)' == $theme->name ) {

add_action( 'st_pb_addon', 'st_pb_builtin_sc_init_extension' );

	function st_pb_builtin_sc_init_extension() {

		/**
		 * Main class to init Shortcodes
		 * for ProStyler Builder
		 *
		 * @package  ProStyler Builder Shortcodes
		 * @since    1.0.0
		 */
		class ST_Pb_Builtin_Shortcode_Extension extends ST_Pb_Addon {
				public function __construct() {

				add_post_type_support( 'page', 'excerpt' );

				// Addon information
				$this->set_provider(
				array(
						'name'             => __( 'Standard Elements', ST_PBL ),
						'file'             => get_bloginfo('url').'/ps-thememaker/library/builder/shortcodes/main.php',
						'shortcode_dir'    => plugin_dir_path( __FILE__ ).'shortcodes',
						'js_shortcode_dir' => 'assets/js/shortcodes',
				)
				);

				//$this->custom_assets();
				// call parent construct
				parent::__construct();

				add_filter( 'plugin_action_links', array( &$this, 'plugin_action_links' ), 10, 2 );
			}

		
		}

		

		$this_ = new ST_Pb_Builtin_Shortcode_Extension();

	}


}

// function head_func() {

// 	//echo '<pre>'.print_r(get_theme_root(), true).'</pre>';

// 	$theme = wp_get_theme();

// 	echo '<pre>'.plugins_url( 'shortcodes/blogexcerptallowedtags/assets/css/blogexcerptallowedtags_frontend.css', plugin_basename( __FILE__ ) , true).'</pre>';

	

// 	//echo '<pre>'.print_r(plugin_dir_path( __FILE__ ) , true).'</pre>';
// 	//echo '<pre>'.print_r(get_theme_root().'/ps-thememaker/library/builder/shortcodes/main.php' , true).'</pre>';
// //
// }

// add_action('wp_head', 'head_func');