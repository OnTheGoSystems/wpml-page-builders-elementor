<?php

/**
 * Class Test_WPML_Elementor_Slides
 *
 * @group page-builders
 * @group elementor
 */
class Test_WPML_Elementor_Slides extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_get_fields() {

		$expected = array( 'heading', 'description', 'button_text', 'link' => array( 'url' ) );
		$subject = new WPML_Elementor_Slides();
		$this->assertEquals( $expected, $subject->get_fields() );
	}

	/**
	 * @test
	 */
	public function it_get_items_field() {
		$subject = new WPML_Elementor_Slides();
		$this->assertEquals( 'slides', $subject->get_items_field() );
	}
}