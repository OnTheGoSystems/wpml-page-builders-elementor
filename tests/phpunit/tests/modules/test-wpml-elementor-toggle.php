<?php

/**
 * Class Test_WPML_Elementor_Toggle
 *
 * @group page-builders
 * @group elementor
 */
class Test_WPML_Elementor_Toggle extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_get_fields() {

		$expected = array( 'tab_title', 'tab_content' );
		$subject = new WPML_Elementor_Toggle();
		$this->assertEquals( $expected, $subject->get_fields() );
	}

	/**
	 * @test
	 */
	public function it_get_items_field() {
		$subject = new WPML_Elementor_Toggle();
		$this->assertEquals( 'tabs', $subject->get_items_field() );
	}
}