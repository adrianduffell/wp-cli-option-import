<?php
/**
 * Option Import for WP-CLI: Functions file.
 *
 * @package wp-cli-option-import
 */

declare( strict_types=1 );

namespace Option_Import;

use \Symfony\Component\Yaml\{Yaml,Exception\ParseException};
use \WP_CLI;

/**
 * Bulk update WordPress options.
 *
 * @param array $updates Array of option name/value pairs.
 * @return array Array of integers specifying:
 *               Total amount of updates.
 *               Successful updates.
 *               Failed updates.
 *               Skipped updates.
 */
function bulk_update_options( array $updates ) : array {
	$total = count( $updates );
	$passed = 0;
	$failed = 0;
	$skipped = 0;

	foreach ( $updates as $name => $value ) {
		if ( assert_option_as_json( $name, json_encode( $value ) ) ) {
			$skipped ++;
			continue;
		}

		update_option( $name, $value );

		if ( assert_option_as_json( $name, json_encode( $value ) ) ) {
			$passed ++;
		} else {
			$failed ++;
		}
	}

	return [ $total, $passed, $failed, $skipped ];
}

/**
 * Converts an option’s value to JSON (to allow type comparison) and asserts
 * it against an expected JSON encoded value.
 *
 * @param  string $name Option name.
 * @param  string $expected Expected option value (JSON encoded).
 * @return boolean Whether the actual option value equals the expected value.
 */
function assert_option_as_json( string $name, string $expected ) : bool {
	$actual = get_option( $name );
	return ( json_encode( $actual ) === $expected );
}

/**
 * Parse a YAML file to an array.
 *
 * @param  string $file Filename to parse.
 * @return array Parsed YAML.
 */
function parse_yaml_file( string $file ) : ?array {
	if ( empty( $file ) ) {
		return null;
	}

	try {
		$contents = file_get_contents( $file );
		$parsed = \Symfony\Component\Yaml\Yaml::parse( $contents );
	} catch ( ParseException $e ) {
		return null;
	}
	return $parsed;
}
