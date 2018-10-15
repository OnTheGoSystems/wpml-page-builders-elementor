<?php

/**
 * Class Test_WPML_Elementor_Integration_Factory
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 *
 * @group page-builders
 * @group elementor
 */
class Test_WPML_Elementor_Integration_Factory extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_creates() {
		$elementor_factory = new WPML_Elementor_Integration_Factory();

		$action_filter_loader = \Mockery::mock( 'overload:WPML_Action_Filter_Loader' );
		$action_filter_loader->shouldReceive( 'load' )
		                     ->once()
		                     ->with( array(
								'WPML_Elementor_Translate_IDs_Factory',
								'WPML_Elementor_URLs_Factory',
								'WPML_Elementor_Adjust_Global_Widget_ID_Factory',
								'WPML_PB_Elementor_Handle_Custom_Fields_Factory',
								'WPML_Elementor_Media_Hooks_Factory',
		                     ) );

		$string_registration = \Mockery::mock( 'overload:WPML_PB_String_Registration' );

		$string_registration_factory = \Mockery::mock( 'overload:WPML_String_Registration_Factory' );
		$string_registration_factory->shouldReceive( 'create' )
		                            ->andReturn( $string_registration );

		$this->assertInstanceOf( 'WPML_Page_Builders_Integration', $elementor_factory->create() );
	}
}
