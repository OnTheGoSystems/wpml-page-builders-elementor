<?php

/**
 * @group media
 */
class Test_WPML_Elementor_Media_Node_WP_Widget_Media_Gallery extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_should_translate() {
		$source_lang     = 'en';
		$target_lang     = 'fr';
		$original_id_1   = 25;
		$original_id_2   = 26;
		$translated_id_1 = 49;
		$translated_id_2 = 43;

		$settings = array(
			'wp' => array(
				'ids' => "$original_id_1,$original_id_2",
			),
		);

		$expected_settings = array(
			'wp' => array(
				'ids' => "$translated_id_1,$translated_id_2",
			),
		);

		$media_translate = $this->get_media_translate();
		$media_translate->method( 'translate_id' )->willReturnMap(
			array(
				array( $original_id_1, $target_lang, $translated_id_1 ),
				array( $original_id_2, $target_lang, $translated_id_2 ),
			)
		);

		$subject = $this->get_subject( $media_translate );

		$this->assertEquals( $expected_settings, $subject->translate( $settings, $target_lang, $source_lang ) );
	}

	/**
	 * @test
	 * @dataProvider dp_should_not_translate
	 *
	 * @param array $settings
	 */
	public function it_should_not_translate_if_slide_is_incomplete( $settings ) {
		$media_translate = $this->get_media_translate();

		$subject = $this->get_subject( $media_translate );

		$this->assertEquals( $settings, $subject->translate( $settings, 'fr', 'en' ) );
	}

	public function dp_should_not_translate() {
		return array(
			array( array() ),
			array( array( 'wp' => 'no an array' ) ),
			array( array( 'wp' => array( 'ids' => '' ) ) ),
		);
	}

	private function get_subject( $media_translate ) {
		return new WPML_Elementor_Media_Node_WP_Widget_Media_Gallery( $media_translate );
	}

	private function get_media_translate() {
		return $this->getMockBuilder( 'WPML_Page_Builders_Media_Translate' )
		            ->setMethods( array( 'translate_id', 'translate_image_url' ) )
		            ->disableOriginalConstructor()->getMock();
	}
}
