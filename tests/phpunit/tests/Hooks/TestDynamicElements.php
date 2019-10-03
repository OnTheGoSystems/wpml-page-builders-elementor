<?php

namespace WPML\PB\Elementor\Hooks;

/**
 * @group hooks
 * @group dynamic-element
 * @group wpmlcore-6542
 */
class TestDynamicElements extends \OTGS_TestCase {

	/**
	 * @test
	 */
	public function itShouldAddHooks() {
		$subject = new DynamicElements();
		\WP_Mock::expectFilterAdded( 'elementor/frontend/builder_content_data', [ $subject, 'convert' ] );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function itShouldNotConvertWithNoConvertibleBlock() {
		$data = [
			[
				'elType'   => 'section',
				'elements' => [],
			],
			[
				'elType'   => 'widget',
				'elements' => [],
			],
			[
				'elType'   => 'widget',
				'settings' => [
					'__dynamic__' => [ 'not_a_link' ],
				],
				'elements' => [],
			],
			[
				'elType'   => 'widget',
				'settings' => [
					'__dynamic__' => [
						'link' => '[elementor-tag id="d3587f6"]',
					],
				],
				'elements' => [],
			],
			[
				'elType'   => 'widget',
				'settings' => [
					'__dynamic__' => [
						'link' => '[elementor-tag id="d3587f6" settings="%7B%22popup%22%3A%228%22%7D"]',
					],
				],
				'elements' => [],
			],
			[
				'elType'   => 'widget',
				'settings' => [
					'__dynamic__' => [
						'link' => '[elementor-tag id="d3587f6" name="not_a_popup" settings="%7B%22popup%22%3A%228%22%7D"]',
					],
				],
				'elements' => [],
			],
			[
				'elType'   => 'widget',
				'settings' => [
					'__dynamic__' => [
						'link' => '[elementor-tag id="d3587f6" name="popup" settings="%7B%22not_a_popup%22%3A%228%22%7D"]',
					],
				],
				'elements' => [],
			],
		];

		$subject = new DynamicElements();

		$filteredData = $subject->convert( $data );

		$this->assertEquals( $data, $filteredData );
	}

	/**
	 * @test
	 */
	public function itShouldConvert() {
		$originalId  = 7;
		$convertedId = 12;
		$postType    = 'post_elementor_library';

		\WP_Mock::userFunction( 'get_post_type', [
			'args'   => [ $originalId ],
			'return' => $postType,
		] );

		\WP_Mock::onFilter( 'wpml_object_id' )
			->with( $originalId, $postType, true )
			->reply( $convertedId );

		$getData = function( $id ) {
			$encodedSettings = urlencode( json_encode( [ 'popup' => $id ] ) );

			return [
				[
					'elType'   => 'section',
					'elements' => [],
				],
				[
					'elType'   => 'section',
					'elements' => [
						[
							'elType'   => 'section',
							'elements' => [],
						],
						[
							'elType'   => 'widget',
							'settings' => [
								'__dynamic__' => [
									'link' => '[elementor-tag id="d3587f6" name="popup" settings="' . $encodedSettings . '"]',
								],
							],
							'elements' => [],
						],
					],
				],
			];
		};

		$originalData = $getData( $originalId );
		$expectedData = $getData( $convertedId );

		$subject = new DynamicElements();

		$this->assertEquals( $expectedData, $subject->convert( $originalData ) );
	}
}
