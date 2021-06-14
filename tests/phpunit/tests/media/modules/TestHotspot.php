<?php

namespace WPML\PB\Elementor\Media\Modules;

/**
 * @group media
 */
class TestHotspot extends \OTGS_TestCase {

	/**
	 * @test
	 */
	public function itShouldTranslate() {
		$targetLang   = 'fr';
		$sourceLang   = 'en';
		$url          = 'https://example.com/my-image.jpg';
		$urlConverted = 'https://example.com/my-image-converted.jpg';
		$id           = 123;
		$idConverted  = 456;

		$getSettings = function( $url, $id ) {
			return [
				'foo'   => 'bar',
				'image' => [
					'url' => $url,
					'id'  => $id,
				],
			];
		};

		$mediaTranslate = $this->getMockBuilder( \WPML_Page_Builders_Media_Translate::class )
		                       ->setMethods( [ 'translate_id', 'translate_image_url' ] )
		                       ->disableOriginalConstructor()->getMock();

		$mediaTranslate->method( 'translate_id' )
		               ->with( $id, $targetLang )
		               ->willReturn( $idConverted );
		$mediaTranslate->method( 'translate_image_url' )
		               ->with( $url, $targetLang, $sourceLang )
		               ->willReturn( $urlConverted );

		$subject = new Hotspot( $mediaTranslate );

		$this->assertEquals(
			$getSettings( $urlConverted, $idConverted ),
			$subject->translate( $getSettings( $url, $id ), $targetLang, $sourceLang )
		);
	}
}
