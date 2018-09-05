<?php

/**
 * @group media
 */
class Test_WPML_Elementor_Media_Nodes_Iterator extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_should_translate() {
		$lang        = 'fr';
		$source_lang = 'en';

		$image_data  = array( 'original image data' );
		$slides_data = array( 'original slides data' );

		$data = array(
			array(
				'elType'   => 'column',
				'elements' => array(
					array(
						'elType'     => 'widget',
						'widgetType' => 'image',
						'settings'   => $image_data,
					),
				),
			),
			array(
				'elType'     => 'widget',
				'widgetType' => 'slides',
				'settings'   => $slides_data,
				'elements'   => array(),
			),
			// Not supported module
			 array(
				'elType'     => 'widget',
				'widgetType' => 'not-supported',
				'settings'   => array(),
				'elements'   => array(),
			),
			// Not translated modules
			array(),
			array( 'elType' => 'not a widget' ),
			array( 'elType' => 'widget' ),
			array( 'elType' => 'widget', 'settings' => array() ),
			array( 'elType' => 'widget', 'widgetType' => 'image' ),
		);

		$translated_image_data  = array( 'translated image data' );
		$translated_slides_data = array( 'translated slides data' );

		$expected_data                               = $data;
		$expected_data[0]['elements'][0]['settings'] = $translated_image_data;
		$expected_data[1]['settings']                = $translated_slides_data;


		$node_image = $this->get_node();
		$node_image->method( 'translate' )->with( $image_data, $lang, $source_lang )
			->willReturn( $translated_image_data );

		$node_slides = $this->get_node();
		$node_slides->method( 'translate' )->with( $slides_data, $lang, $source_lang )
			->willReturn( $translated_slides_data );

		$node_provider = $this->get_node_provider();
		$node_provider->method( 'get' )->willReturnMap(
			array(
				array( 'image', $node_image ),
				array( 'slides', $node_slides ),
				array( 'not-supported', null ),
			)
		);

		$subject = $this->get_subject( $node_provider );

		$this->assertEquals( $expected_data, $subject->translate( $data, $lang, $source_lang ) );
	}

	private function get_subject( $node_provider ) {
		return new WPML_Elementor_Media_Nodes_Iterator( $node_provider );
	}

	private function get_node_provider() {
		return $this->getMockBuilder( 'WPML_Elementor_Media_Node_Provider' )
			->setMethods( array( 'get' ) )->disableOriginalConstructor()->getMock();
	}

	private function get_node() {
		return $this->getMockBuilder( 'WPML_Elementor_Media_Node' )
		            ->setMethods( array( 'translate' ) )->disableOriginalConstructor()->getMock();
	}
}

if ( ! interface_exists( 'IWPML_PB_Media_Nodes_Iterator' ) ) {
	interface IWPML_PB_Media_Nodes_Iterator {}
}