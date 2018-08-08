<?php

/**
 * Class Test_WPML_PB_Elementor_Handle_Custom_Fields_Factory
 *
 * @group wpmlpb-149
 */
class Test_WPML_PB_Elementor_Handle_Custom_Fields_Factory extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_runs_on_back_front_ajax() {
		$subject = new WPML_PB_Elementor_Handle_Custom_Fields_Factory();
		$this->assertInstanceOf( 'IWPML_Backend_Action_Loader', $subject );
		$this->assertInstanceOf( 'IWPML_AJAX_Action_Loader', $subject );
		$this->assertInstanceOf( 'IWPML_Frontend_Action_Loader', $subject );
	}

	/**
	 * @test
	 */
	public function it_returns_instance() {
		\Mockery::mock( 'overload:WPML_PB_Handle_Custom_Fields' );

		$subject = new WPML_PB_Elementor_Handle_Custom_Fields_Factory();
		$this->assertInstanceOf( 'WPML_PB_Handle_Custom_Fields', $subject->create() );
	}
}

if ( ! interface_exists( 'IWPML_Backend_Action_Loader' ) ) {
	interface IWPML_Backend_Action_Loader{}
}

if ( ! interface_exists( 'IWPML_AJAX_Action_Loader' ) ) {
	interface IWPML_AJAX_Action_Loader{}
}

if ( ! interface_exists( 'IWPML_Frontend_Action_Loader' ) ) {
	interface IWPML_Frontend_Action_Loader{}
}