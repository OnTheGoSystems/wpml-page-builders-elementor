<?php

namespace WPML\PB\Elementor\Media\Modules;

class Gallery extends \WPML_Elementor_Media_Node {

	/**
	 * @param array $settings
	 * @param string $target_lang
	 * @param string $source_lang
	 *
	 * @return array
	 */
	public function translate( $settings, $target_lang, $source_lang ) {

		if ( isset( $settings['gallery'] ) && is_array( $settings['gallery'] ) ) {
			foreach( $settings['gallery'] as $id => $image ) {
				$settings['gallery'][ $id ] = $this->translate_image_array( $image, $target_lang, $source_lang );
			}
		}

		return $settings;
	}
}