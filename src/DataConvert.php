<?php

namespace WPML\PB\Elementor;

use WPML\FP\Obj;

class DataConvert {

	/**
	 * @param array $data
	 *
	 * @return string
	 */
	public static function serialize( array $data ) {
		return wp_slash( wp_json_encode( $data ) );
	}

	/**
	 * @param array|string $data
	 *
	 * @return array
	 */
	public static function unserialize( $data ) {
		$maybeEncoded = is_array( $data ) ? Obj::prop( 0, $data ) : $data;
		return is_string( $maybeEncoded ) ? json_decode( $maybeEncoded, true ) : $data;
	}
}
