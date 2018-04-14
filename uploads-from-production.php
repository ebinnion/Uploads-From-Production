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
		if ( defined( 'UPLOADS_FROM_PRODUCTION_PROXY_URL' ) ) {
			add_filter( 'wp_get_attachment_url', array( $this, 'filter_image_url' ) );
			add_filter( 'wp_get_attachment_image_attributes', array( $this, 'process_image_attributes' ), PHP_INT_MAX );
		}
	}

	/**
	 * Given an array of image attributes, will proxy the image from the production
	 * server and will remove the srcset attribute.
	 *
	 * @param array $attributes Array of image attributes.
	 * @return array
	 */
	public function process_image_attributes( $attributes ) {
		if ( empty( $attributes['src'] ) ) {
			return $attributes;
		}

		$attributes['src'] = $this->filter_image_url( $attributes['src'] );
		if ( ! empty( $attributes['srcset'] ) ) {
			unset( $attributes['srcset'] );
		}

		return $attributes;
	}

	/**
	 * Given a URL, will replace the host of the URL with a production
	 * URL defined in UPLOADS_FROM_PRODUCTION_PROXY_URL.
	 *
	 * @param string $url The URL to the attachment.
	 * @return string
	 */
	public function filter_image_url( $url ) {
		$url_parts       = wp_parse_url( esc_url_raw( $url ) );
		$proxy_url_parts = wp_parse_url( esc_url_raw( UPLOADS_FROM_PRODUCTION_PROXY_URL ) );

		$url_parts['scheme'] = $proxy_url_parts['scheme'];
		$url_parts['host']   = $proxy_url_parts['host'];

		$components = array(
			'scheme',
			'host',
			'path',
			'query',
			'fragment',
		);

		$url = '';
		foreach ( $components as $key ) {
			if ( empty( $url_parts[ $key ] ) ) {
				continue;
			}
			switch ( $key ) {
				case 'host':
					$prefix = '://';
					break;
				case 'query':
					$prefix = '?';
					break;
				case 'fragment':
					$prefix = '#';
					break;
				default:
					$prefix = '';
					break;
			}

			$url .= "$prefix{$url_parts[ $key ]}";
		}

		return $this->remove_image_dimensions_from_url( $url );
	}

	/**
	 * Given an image URL will remove the image dimensions from the end of the URL.
	 *
	 * @param string $url The image URL.
	 * @return string
	 */
	public function remove_image_dimensions_from_url( $url ) {
		return preg_replace( '/-(\d+)x(\d+)\./', '.', $url );
	}
}

Uploads_From_Production::instance();

if ( defined( 'WP_CLI' ) ) {
	require_once dirname( __FILE__ ) . '/class.uploads-from-production-cli.php';
}

