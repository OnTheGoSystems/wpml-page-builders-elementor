<?php

/**
 * @group media
 */
class Test_WPML_Elementor_Media_Node_WP_Widget_Media_Image extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_should_translate() {
		$source_lang    = 'en';
		$target_lang    = 'fr';
		$original_id    = 25;
		$translated_id  = 49;
		$original_url   = 'http://example.org/dog.jpg';
		$translated_url = 'http://example.org/chien.jpg';

		$settings = array(
			'wp' => array(
				'attachment_id' => $original_id,
				'url'           => $original_url,
				'caption'       => 'the caption',
				'alt'           => 'the alt',
				'image_title'   => 'the image title',
			),
		);

		$expected_settings = array(
			'wp' => array(
				'attachment_id' => $translated_id,
				'url'           => $translated_url,
				'caption'       => $target_lang . 'the caption',
				'alt'           => $target_lang . 'the alt',
				'image_title'   => $target_lang . 'the image title',
			),
		);

		\WP_Mock::userFunction( 'wp_prepare_attachment_for_js', array(
			'args' => array( $translated_id ),
			'return' => array(
				'caption' => $target_lang . 'the caption',
				'alt'     => $target_lang . 'the alt',
				'title'   => $target_lang . 'the image title',
			),
		));

		$media_translate = $this->get_media_translate();
		$media_translate->method( 'translate_id' )->with( $original_id, $target_lang )->willReturn( $translated_id );
		$media_translate->method( 'translate_image_url' )
		                ->with( $original_url, $target_lang, $source_lang )->willReturn( $translated_url );

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
		);
	}

	private function get_subject( $media_translate ) {
		return new WPML_Elementor_Media_Node_WP_Widget_Media_Image( $media_translate );
	}

	private function get_media_translate() {
		return $this->getMockBuilder( 'WPML_Page_Builders_Media_Translate' )
		            ->setMethods( array( 'translate_id', 'translate_image_url' ) )
		            ->disableOriginalConstructor()->getMock();
	}
}
