<?php

namespace WPML\PB\Elementor\Modules;

/**
 * @group modules
 * @group wpmlcore-7439
 */
class TestReviews extends \OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_get_fields() {
		$subject = new Reviews();
		$this->assertEquals( [ 'content', 'name', 'title', 'link' => [ 'field' => 'url' ] ], $subject->get_fields() );
	}

	/**
	 * @test
	 */
	public function it_get_items_field() {
		$subject = new Reviews();
		$this->assertEquals( 'slides', $subject->get_items_field() );
	}
}
