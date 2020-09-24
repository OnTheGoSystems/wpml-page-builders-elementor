<?php

namespace WPML\PB\Elementor\Media\Modules;

use WPML\FP\Math;

/**
 * @group media
 */
class Test_AllNodes extends \OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_translates_background_images() {
		$settings = [
			'structure'              => 20,
			'background_image'       => [
				'url' => 'background_image_1',
				'id'  => 1,
			],
			'background_size'        => 'contain',
			'background_hover_image' => [
				'url' => 'background_image_2',
				'id'  => 2,
			],
		];

		$source = 'en';
		$target = 'fr';

		$expected = [
			'structure'              => 20,
			'background_image'       => [
				'url' => 'translated_background_image_1',
				'id'  => 101,
			],
			'background_size'        => 'contain',
			'background_hover_image' => [
				'url' => 'translated_background_image_2',
				'id'  => 102,
			],
		];

		$mediaTranslate = $this->createMock( '\WPML_Page_Builders_Media_Translate' );
		$mediaTranslate->method( 'translate_id' )->willReturnCallback( Math::add( 100 ) );
		$mediaTranslate->method( 'translate_image_url' )->willReturnMap( [
			[ 'background_image_1', $target, $source, 'translated_background_image_1' ],
			[ 'background_image_2', $target, $source, 'translated_background_image_2' ],
		] );

		$subject = new AllNodes( $mediaTranslate );

		$this->assertEquals( $expected, $subject->translate( $settings, $target, $source ) );
	}

}