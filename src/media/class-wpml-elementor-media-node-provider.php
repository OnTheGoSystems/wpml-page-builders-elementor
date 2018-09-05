<?php

class WPML_Elementor_Media_Node_Provider {

	/** @var WPML_Page_Builders_Media_Translate $media_translate */
	private $media_translate;

	/** @var WPML_Elementor_Media_Node[] */
	private $nodes = array();

	/**
	 * @param string $type
	 *
	 * @return WPML_Elementor_Media_Node
	 */
	public function get( $type ) {
		if ( ! array_key_exists( $type, $this->nodes ) ) {
			switch ( $type ) {
				case 'image':
					$node = new WPML_Elementor_Media_Node_Image( $this->get_media_translate() );
					break;

				case 'slides':
					$node = new WPML_Elementor_Media_Node_Slides( $this->get_media_translate() );
					break;

				case 'call-to-action':
					$node = new WPML_Elementor_Media_Node_Call_To_Action( $this->get_media_translate() );
					break;

				case 'media-carousel':
					$node = new WPML_Elementor_Media_Node_Media_Carousel( $this->get_media_translate() );
					break;

				case 'image-box':
					$node = new WPML_Elementor_Media_Node_Image_Box( $this->get_media_translate() );
					break;

				case 'image-gallery':
					$node = new WPML_Elementor_Media_Node_Image_Gallery( $this->get_media_translate() );
					break;

				case 'image-carousel':
					$node = new WPML_Elementor_Media_Node_Image_Carousel( $this->get_media_translate() );
					break;

				case 'wp-widget-media_image':
					$node = new WPML_Elementor_Media_Node_WP_Widget_Media_Image( $this->get_media_translate() );
					break;

				case 'wp-widget-media_gallery':
					$node = new WPML_Elementor_Media_Node_WP_Widget_Media_Gallery( $this->get_media_translate() );
					break;

				default:
					$node = null;
			}

			$this->nodes[ $type ] = $node;
		}

		return $this->nodes[ $type ];
	}

	/** @return WPML_Page_Builders_Media_Translate */
	private function get_media_translate() {
		global $sitepress;

		if ( ! $this->media_translate ) {
			$this->media_translate = new WPML_Page_Builders_Media_Translate(
				new WPML_Translation_Element_Factory( $sitepress ),
				new WPML_Media_Image_Translate( $sitepress, new WPML_Media_Attachment_By_URL_Factory() )
			);
		}

		return $this->media_translate;
	}
}
