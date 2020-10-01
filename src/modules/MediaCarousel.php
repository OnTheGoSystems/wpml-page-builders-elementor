<?php

namespace WPML\PB\Elementor\Modules;

class MediaCarousel extends \WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'slides';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [ 
			'image_link_to' => [ 'field' => 'url', 'field_id' => 'image_link_to' ],
			'video' => [ 'field' => 'url', 'field_id' => 'video' ]
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field_id ) {
		switch ( $field_id ) {
			case 'image_link_to':
				return esc_html__( 'Media Carousel: image link URL', 'sitepress' );
			case 'video':
				return esc_html__( 'Media Carousel: video source URL', 'sitepress' );
			default:
				return '';
		}
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'url':
				return 'LINK';
			default:
				return '';
		}
	}
}