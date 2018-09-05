<?php

/**
 * @group media
 */
class Test_WPML_Elementor_Media_Node_Image extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_should_translate() {
		$source_lang    = 'en';
		$target_lang    = 'fr';
		$original_id    = 23;
		$translated_id  = 45;
		$original_url   = 'http://example.org/dog.jpg';
		$translated_url = 'http://example.org/chien.jpg';

		$settings = array(
			'image'                   => array( 'id' => $original_id, 'url' => $original_url ),
			'_background_image'       => array( 'id' => $original_id, 'url' => $original_url ),
			'_background_hover_image' => array( 'id' => $original_id, 'url' => $original_url ),
			'caption'                 => 'The caption',
		);

		$expected_settings = array(
			'image'                   => array( 'id' => $translated_id, 'url' => $translated_url ),
			'_background_image'       => array( 'id' => $translated_id, 'url' => $translated_url ),
			'_background_hover_image' => array( 'id' => $translated_id, 'url' => $translated_url ),
			'caption'                 => $target_lang . 'The caption',
		);

		\WP_Mock::userFunction( 'wp_prepare_attachment_for_js', array(
			'args' => array( $translated_id ),
			'return' => array(
				'caption' => $target_lang . 'The caption',
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
	 */
	public function it_should_not_translate_if_properties_missing_or_empty() {
		$settings = array(
			'image'   => array( 'id' => '' ),
			'caption' => '',
		);

		$media_translate = $this->get_media_translate();

		$subject = $this->get_subject( $media_translate );

		$this->assertEquals( $settings, $subject->translate( $settings, 'fr', 'en' ) );
	}

	private function get_subject( $media_translate ) {
		return new WPML_Elementor_Media_Node_Image( $media_translate );
	}

	private function get_media_translate() {
		return $this->getMockBuilder( 'WPML_Page_Builders_Media_Translate' )
		            ->setMethods( array( 'translate_id', 'translate_image_url' ) )
		            ->disableOriginalConstructor()->getMock();
	}
}
