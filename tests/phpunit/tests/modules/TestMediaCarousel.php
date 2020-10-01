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
		$this->assertEquals( [ 'image_link_to' => [ 'field' => 'url', 'field_id' => 'image_link_to' ] ], $subject->get_fields() );
		$this->assertEquals( [ 'video' => [ 'field' => 'url', 'field_id' => 'video' ] ], $subject->get_fields() );
	}

	/**
	 * @test
	 */
	public function it_get_items_field() {
		$subject = new MediaCarousel();
		$this->assertEquals( 'slides', $subject->get_items_field() );
	}
}
