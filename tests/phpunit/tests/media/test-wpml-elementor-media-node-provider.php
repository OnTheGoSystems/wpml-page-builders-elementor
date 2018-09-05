<?php

/**
 * @group media
 */
class Test_WPML_Elementor_Media_Node_Provider extends OTGS_TestCase {

	/**
	 * @test
	 * @dataProvider dp_node_types
	 *
	 * @param string $type
	 * @param string $class_name
	 */
	public function it_should_return_a_node_instance_and_cache_it( $type, $class_name ) {
		$GLOBALS['sitepress'] = $this->getMockBuilder( 'SitePress' )->disableOriginalConstructor()->getMock();
		$this->mock_external_classes();

		$subject = new WPML_Elementor_Media_Node_Provider();

		$this->assertInstanceOf( $class_name, $subject->get( $type ) );
		$this->assertSame( $subject->get( $type ), $subject->get( $type ) );
	}

	public function dp_node_types() {
		return array(
			'image'                   => array( 'image', 'WPML_Elementor_Media_Node_Image' ),
			'slides'                  => array( 'slides', 'WPML_Elementor_Media_Node_Slides' ),
			'call-to-action'          => array( 'call-to-action', 'WPML_Elementor_Media_Node_Call_To_Action' ),
			'media-carousel'          => array( 'media-carousel', 'WPML_Elementor_Media_Node_Media_Carousel' ),
			'image-box'               => array( 'image-box', 'WPML_Elementor_Media_Node_Image_Box' ),
			'image-gallery'           => array( 'image-gallery', 'WPML_Elementor_Media_Node_Image_Gallery' ),
			'image-carousel'          => array( 'image-carousel', 'WPML_Elementor_Media_Node_Image_Carousel' ),
			'wp-widget-media_image'   => array( 'wp-widget-media_image', 'WPML_Elementor_Media_Node_WP_Widget_Media_Image' ),
			'wp-widget-media_gallery' => array( 'wp-widget-media_gallery', 'WPML_Elementor_Media_Node_WP_Widget_Media_Gallery' ),
		);
	}

	private function mock_external_classes() {
		$this->getMockBuilder( 'WPML_Page_Builders_Media_Translate' )->getMock();
		$this->getMockBuilder( 'WPML_Translation_Element_Factory' )->getMock();
		$this->getMockBuilder( 'WPML_Media_Image_Translate' )->getMock();
		$this->getMockBuilder( 'WPML_Media_Attachment_By_URL_Factory' )->getMock();
	}
}