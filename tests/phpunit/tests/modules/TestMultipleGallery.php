<?php

namespace WPML\PB\Elementor\Modules;

/**
 * @group modules
 * @group wpmlcore-7223
 */
class TestMultipleGallery extends \OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_get_fields() {
		$subject = new MulitpleGallery();
		$this->assertEquals( [ 'gallery_title' ], $subject->get_fields() );
	}

	/**
	 * @test
	 */
	public function it_get_items_field() {
		$subject = new MulitpleGallery();
		$this->assertEquals( 'galleries', $subject->get_items_field() );
	}
}
