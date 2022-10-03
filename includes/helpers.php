<?php
/**
 * Helper functions for the plugin.
 *
 *
 * @since         1.0.0
 * @package       Glacial_Notifications
 * @subpackage    Glacial_Notifications/includes
 * @author        Glacial Multimedia
 */

function glacial_notifications_flatten_array( $array = null, $depth = 1 ) {
	$result = [];
	if ( ! is_array( $array ) ) {
		$array = func_get_args();
	}
	foreach ( $array as $key => $value ) {
		if ( is_array( $value ) && $depth ) {
			$result = array_merge( $result, glacial_notifications_flatten_array( $value, $depth - 1 ) );
		} else {
			$result = array_merge( $result, [ $key => $value ] );
		}
	}

	return $result;

}