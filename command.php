<?php
/**
 * Option Import for WP-CLI: Command script.
 *
 * @package wp-cli-option-import
 */

declare( strict_types=1 );

namespace Option_Import;

use \WP_CLI;

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * Imports a .yml file containing values to update to WordPress options.
 *
 * ## OPTIONS
 *
 * <file>
 *
 * The .yml file to import.
 *
 * ## EXAMPLES
 *
 *     # Import a .yml file containing values to update to WordPress options.
 *     $ wp option import ~/config/.options.yml
 *     Success: Updated 2 of 4 options (2 skipped).
 *
 * @when after_wp_load
 */
WP_CLI::add_command( 'option import', function( array $args ) {
	if ( ! isset( $args[0] ) ) {
		WP_CLI::error( sprintf( 'Filename missing.' ) );
	}

	$file = realpath( $args[0] );
	$updates = parse_yaml_file( $file );

	if ( is_null( $updates ) ) {
		WP_CLI::error( sprintf( 'Could not parse file %s', $args[0] ) );
	}

	list( $total, $passed, $failed, $skipped ) = bulk_update_options( $updates );

	WP_CLI\Utils\report_batch_operation_results(
		'option',
		'updated',
		$total,
		$passed,
		$failed,
		$skipped
	);
} );
