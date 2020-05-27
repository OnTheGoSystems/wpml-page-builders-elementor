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

		$expected = [
			'field_label',
			'placeholder',
			'field_html',
			'acceptance_text',
			'field_options',
			'step_next_label',
			'step_previous_label',
			'previous_button',
			'next_button',
		];
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