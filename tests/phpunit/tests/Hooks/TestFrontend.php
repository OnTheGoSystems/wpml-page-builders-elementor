<?php

namespace WPML\PB\Elementor\Hooks;

/**
 * @group hooks
 * @group frontend
 * @group wpmlcore-7279
 */
class TestFrontend extends \OTGS_TestCase {

	/**
	 * @test
	 */
	public function itShouldAddHooks() {
		$subject = new Frontend();
		\WP_Mock::expectActionAdded( 'elementor_pro/search_form/after_input', [ $subject, 'addLanguageFormField' ] );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function itShouldAddLanguageFormField() {
		$subject = new Frontend();
		\WP_Mock::expectAction( 'wpml_add_language_form_field' );
		$subject->addLanguageFormField();
	}
}