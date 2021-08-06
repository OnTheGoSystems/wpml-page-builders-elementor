<?php

namespace WPML\PB\Elementor;

/**
 * @group elementor
 */
class TestDataConvert extends \OTGS_TestCase {

	/**
	 * @test
	 * @dataProvider dpUnserialize
	 *
	 * @param mixed $data
	 * @param array $expectedOutput
	 */
	public function itUnserializes( $data, $expectedOutput ) {
		$this->assertEquals( $expectedOutput, DataConvert::unserialize( $data ) );
	}

	public function dpUnserialize() {
		return [
			'json string' => [
				'{"foo":"bar"}',
				[ 'foo' => 'bar' ]
			],
			'json string in array' => [
				[ '{"foo":"bar"}' ],
				[ 'foo' => 'bar' ]
			],
			'already unserialized - wpmlcore-8775' => [
				[ 'foo' => 'bar' ],
				[ 'foo' => 'bar' ]
			]
		];
	}
}
