<?php

/**
 * Class Test_WPML_Elementor_Form
 *
 * @group page-builders
 * @group elementor
 */
class Test_WPML_Elementor_Form extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_get_fields() {

		$expected = array( 'field_label', 'placeholder' );
		$subject = new WPML_Elementor_Form();
		$this->assertEquals( $expected, $subject->get_fields() );
	}

	/**
	 * @test
	 */
	public function it_get_items_field() {
		$subject = new WPML_Elementor_Form();
		$this->assertEquals( 'form_fields', $subject->get_items_field() );
	}
}