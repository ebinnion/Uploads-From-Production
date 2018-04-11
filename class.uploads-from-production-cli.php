<?php

class Uploads_From_Production_CLI extends WP_CLI_Command {
	/**
	 * Import images from the production server.
	 *
	 * ## EXAMPLES
	 *
	 * wp uploads-from-production import
	 */
	public function import( $args, $assoc_args ) {
		WP_CLI::log( 'Not yet implemented!' );
	}
}

WP_CLI::add_command( 'uploads-from-production', 'Uploads_From_Production_CLI' );
