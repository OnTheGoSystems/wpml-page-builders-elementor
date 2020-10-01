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
		$subject = new MediaCarousel();
		$this->assertEquals( [ 'image_link_to' => [ 'url' ], 'video' => [ 'url' ] ], $subject->get_fields() );
	}

	/**
	 * @test
	 */
	public function it_get_items_field() {
		$subject = new MediaCarousel();
		$this->assertEquals( 'slides', $subject->get_items_field() );
	}
}
