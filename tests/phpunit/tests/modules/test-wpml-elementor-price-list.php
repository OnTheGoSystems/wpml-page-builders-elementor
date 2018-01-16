<?php

/**
 * Class Test_WPML_Elementor_Price_List
 *
 * @group page-builders
 * @group elementor
 */
class Test_WPML_Elementor_Price_List extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_get_fields() {

		$expected = array( 'title', 'item_description', 'link' => array( 'url' ) );
		$subject = new WPML_Elementor_Price_List();
		$this->assertEquals( $expected, $subject->get_fields() );
	}

	/**
	 * @test
	 */
	public function it_get_items_field() {
		$subject = new WPML_Elementor_Price_List();
		$this->assertEquals( 'price_list', $subject->get_items_field() );
	}
}