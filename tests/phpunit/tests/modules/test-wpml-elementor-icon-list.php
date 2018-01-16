<?php

/**
 * Class Test_WPML_Elementor_Icon_List
 *
 * @group page-builders
 * @group elementor
 */
class Test_WPML_Elementor_Icon_List extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_get_fields() {

		$expected = array( 'text', 'link' => array( 'url' ) );
		$subject = new WPML_Elementor_Icon_List();
		$this->assertEquals( $expected, $subject->get_fields() );
	}

	/**
	 * @test
	 */
	public function it_get_items_field() {
		$subject = new WPML_Elementor_Icon_List();
		$this->assertEquals( 'icon_list', $subject->get_items_field() );
	}
}