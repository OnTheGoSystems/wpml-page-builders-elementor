<?php

/**
 * Class Test_WPML_Elementor_Tabs
 *
 * @group page-builders
 * @group elementor
 */
class Test_WPML_Elementor_Tabs extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_get_fields() {

		$expected = array( 'tab_title', 'tab_content' );
		$subject = new WPML_Elementor_Tabs();
		$this->assertEquals( $expected, $subject->get_fields() );
	}

	/**
	 * @test
	 */
	public function it_get_items_field() {
		$subject = new WPML_Elementor_Tabs();
		$this->assertEquals( 'tabs', $subject->get_items_field() );
	}
}