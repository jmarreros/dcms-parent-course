<?php
/*
Plugin Name: Parent Course LMS
Plugin URI: https://decodecms.com
Description: Plugin para añadir funcionalidad de cursos padre en LMS Master Study
Version: 1.0
Author: Jhon Marreros Guzmán
Author URI: https://decodecms.com
Text Domain: dcms-parent-course
Domain Path: languages
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

namespace dcms\parent;

use dcms\parent\includes\Plugin;
use dcms\parent\includes\Submenu;
use dcms\parent\includes\Enqueue;
use dcms\parent\includes\Configuration;
use dcms\parent\includes\Process;
use dcms\parent\includes\Courses;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin class to handle settings constants and loading files
**/
final class Loader{

	// Define all the constants we need
	public function define_constants(): void {
		define ('DCMS_PARENT_VERSION', '1.0');
		define ('DCMS_PARENT_PATH', plugin_dir_path( __FILE__ ));
		define ('DCMS_PARENT_URL', plugin_dir_url( __FILE__ ));
		define ('DCMS_PARENT_BASE_NAME', plugin_basename( __FILE__ ));
		define ('DCMS_PARENT_SUBMENU', 'options-general.php');

		// Conditional defined MAX_DATE constant
		defined('MAX_DATE') or define('MAX_DATE', '2100-01-01');

		// Special product multiprices or flexible prices - plugin name prices
		if ( ! defined('DCMS_PARENT_ID_PRODUCT_MULTI_PRICES') ) {
			define('DCMS_PARENT_ID_PRODUCT_MULTI_PRICES', 'dcms-parent-product-multi-prices');
		}
	}

	// Load all the files we need
	public function load_includes(): void {
		include_once ( DCMS_PARENT_PATH . '/helpers/helper.php');
		include_once ( DCMS_PARENT_PATH . '/includes/plugin.php');
		include_once ( DCMS_PARENT_PATH . '/includes/submenu.php');
		include_once ( DCMS_PARENT_PATH . '/includes/enqueue.php');
		include_once ( DCMS_PARENT_PATH . '/includes/database.php');
		include_once ( DCMS_PARENT_PATH . '/includes/configuration.php');
		include_once ( DCMS_PARENT_PATH . '/includes/process.php');
		include_once ( DCMS_PARENT_PATH . '/includes/courses.php');

    	// Back-end
		include_once ( DCMS_PARENT_PATH . '/back-end/metabox-parent-course.php');

    	// Front-end
		include_once ( DCMS_PARENT_PATH . '/front-end/courses-product.php');
		include_once ( DCMS_PARENT_PATH . '/front-end/shortcode.php');
	}

	// Load tex domain
	public function load_domain(){
		add_action('plugins_loaded', function(){
			$path_languages = dirname(DCMS_PARENT_BASE_NAME).'/languages/';
			load_plugin_textdomain('dcms-parent-course', false, $path_languages );
		});
	}

	// Add link to plugin list
	public function add_link_plugin(){
		add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), function( $links ){
			return array_merge( array(
				'<a href="' . esc_url( admin_url( DCMS_PARENT_SUBMENU . '?page=parent-course' ) ) . '">' . __( 'Settings', 'dcms-parent-course' ) . '</a>'
			), $links );
		} );
	}

	// Initialize all
	public function init(): void {
		$this->define_constants();
		$this->load_includes();
		$this->load_domain();
		$this->add_link_plugin();
		new Plugin;
		new SubMenu;
		new Enqueue;
		new Configuration;
		new Process;
		new Courses;
	}

}

$dcms_parent_process = new Loader();
$dcms_parent_process->init();


