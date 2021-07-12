<?php

abstract class Test_WPML_Elementor_Media_Node_With_Image_Property extends OTGS_TestCase {

	abstract protected function get_image_property();

	abstract protected function get_subject( $media_translate );

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

		$image_property = $this->get_image_property();

		$settings = [
			$image_property => [ 'id' => $original_id, 'url' => $original_url ],
		];

		$expected_settings = [
			$image_property => [ 'id' => $translated_id, 'url' => $translated_url ],
		];

		$media_translate = $this->get_media_translate();
		$media_translate->method( 'translate_id' )
		                ->with( $original_id, $target_lang )->willReturn( $translated_id );
		$media_translate->method( 'translate_image_url' )
		                ->with( $original_url, $target_lang, $source_lang )->willReturn( $translated_url );

		$subject = $this->get_subject( $media_translate );

		$this->assertEquals( $expected_settings, $subject->translate( $settings, $target_lang, $source_lang ) );
	}

	/**
	 * @test
	 */
	public function it_should_not_translate_if_property_is_missing() {
		$settings = [ 'something' ];

		$media_translate = $this->get_media_translate();

		$subject = $this->get_subject( $media_translate );

		$this->assertEquals( $settings, $subject->translate( $settings, 'fr', 'en' ) );
	}

	private function get_media_translate() {
		return $this->getMockBuilder( 'WPML_Page_Builders_Media_Translate' )
		            ->setMethods( array( 'translate_id', 'translate_image_url' ) )
		            ->disableOriginalConstructor()->getMock();
	}

}
