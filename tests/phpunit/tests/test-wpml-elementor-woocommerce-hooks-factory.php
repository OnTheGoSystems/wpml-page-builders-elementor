<?php

/**
 * Class Test_WPML_Elementor_WooCommerce_Hooks_Factory
 *
 * @group wpmlcore-6209
 */
class Test_WPML_Elementor_WooCommerce_Hooks_Factory extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_creates_instance_of_elementor_woocommerce_hooks() {
		$subject = new WPML_Elementor_WooCommerce_Hooks_Factory();
		$this->assertInstanceOf( WPML_Elementor_WooCommerce_Hooks::class, $subject->create() );
	}
}