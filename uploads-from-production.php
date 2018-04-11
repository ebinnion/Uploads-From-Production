<?php
/**
 * Plugin Name
 *
 * @package     UploadsFromProduction
 * @author      Eric Binnion
 * @copyright   2018 Eric Binnion
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Uploads from Production
 * Plugin URI:  https://eric.blog
 * Description: Provides utilities for working with images on development or staging site.
 * Version:     0.1.0
 * Author:      Eric Binnion
 * Author URI:  https://eric.blog
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

class Uploads_From_Production {
	static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new Uploads_From_Production();
		}
		return self::$instance;
	}

	/**
	 * Enforce singleton
	 */
	private function __construct() {

	}
}

if ( defined( 'WP_CLI' ) ) {
	require_once dirname( __FILE__ ) . '/class.uploads-from-production-cli.php';
}

