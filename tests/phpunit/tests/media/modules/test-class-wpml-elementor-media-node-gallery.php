<?php

/**
 * @group media
 */
class Test_WPML_PB_Elementor_Media_Modules_Gallery extends OTGS_TestCase {

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
			'gallery' => [ 0 => [ 'id' => $original_id, 'url' => $original_url ] ]
		);

		$expected_settings = array(
			'gallery' => [ 0 => [ 'id' => $translated_id, 'url' => $translated_url ] ]
		);

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
	public function it_should_NOT_translate_when_NO_gallery() {
		$source_lang    = 'en';
		$target_lang    = 'fr';
		$original_id    = 23;
		$translated_id  = 45;
		$original_url   = 'http://example.org/dog.jpg';
		$translated_url = 'http://example.org/chien.jpg';

		$expected_settings = $settings = array(
			'not_gallery' => [ 0 => [ 'id' => $original_id, 'url' => $original_url ] ]
		);



		$media_translate = $this->get_media_translate();
		$media_translate->method( 'translate_id' )->with( $original_id, $target_lang )->willReturn( $translated_id );
		$media_translate->method( 'translate_image_url' )
			->with( $original_url, $target_lang, $source_lang )->willReturn( $translated_url );

		$subject = $this->get_subject( $media_translate );

		$this->assertEquals( $expected_settings, $subject->translate( $settings, $target_lang, $source_lang ) );
	}

	/**
	 * @param $media_translate
	 * @return \WPML\PB\Elementor\Media\Modules\Gallery
	 */
	private function get_subject( $media_translate ) {
		return new \WPML\PB\Elementor\Media\Modules\Gallery( $media_translate );
	}

	private function get_media_translate() {
		return $this->getMockBuilder( 'WPML_Page_Builders_Media_Translate' )
			->setMethods( array( 'translate_id', 'translate_image_url' ) )
			->disableOriginalConstructor()->getMock();
	}
}