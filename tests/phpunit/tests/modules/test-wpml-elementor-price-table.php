<?php

/**
 * Class Test_WPML_Elementor_Price_Table
 *
 * @group page-builders
 * @group elementor
 */
class Test_WPML_Elementor_Price_Table extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_get_fields() {

		$expected = array( 'item_text' );
		$subject = new WPML_Elementor_Price_Table();
		$this->assertEquals( $expected, $subject->get_fields() );
	}

	/**
	 * @test
	 */
	public function it_get_items_field() {
		$subject = new WPML_Elementor_Price_Table();
		$this->assertEquals( 'features_list', $subject->get_items_field() );
	}
}