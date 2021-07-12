<?php

namespace WPML\PB\Elementor\Media\Modules;

require_once __DIR__ . '/abstract/Test_WPML_Elementor_Media_Node_With_Image_Property.php';

/**
 * @group
 */
class TestVideo extends \Test_WPML_Elementor_Media_Node_With_Image_Property {

	protected function get_image_property() {
		return 'image_overlay';
	}

	protected function get_subject( $media_translate ) {
		return new Video( $media_translate );
	}
}
