<?php

namespace WPML\PB\Elementor\Modules;

/**
 * @group modules
 * @group wpmlcore-7149
 */
class TestMediaCarousel extends \OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_get_fields() {
		$config = [
			'image_link_to'	=> [
				'field' => 'url',
			],
		];

		$subject = new ModuleWithItemsFromConfig( 'slides', $config );
		$this->assertEquals( [ 'image_link_to' => [ 'url' ] ], $subject->get_fields() );
	}

	/**
	 * @test
	 */
	public function it_get_items_field() {
		$subject = new ModuleWithItemsFromConfig( 'slides', [] );
		$this->assertEquals( 'slides', $subject->get_items_field() );
	}
}
