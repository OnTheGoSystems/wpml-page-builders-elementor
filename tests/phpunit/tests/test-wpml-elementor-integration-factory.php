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
		                     ) );

		$string_registration = \Mockery::mock( 'overload:WPML_PB_String_Registration' );

		$string_registration_factory = \Mockery::mock( 'overload:WPML_String_Registration_Factory' );
		$string_registration_factory->shouldReceive( 'create' )
		                            ->andReturn( $string_registration );

		$this->assertInstanceOf( 'WPML_Page_Builders_Integration', $elementor_factory->create() );
	}

	/**
	 * @test
	 * @group wpmlmedia-511
	 */
	public function it_add_media_translation_hooks() {
		$elementor_factory = new WPML_Elementor_Integration_Factory();

		$action_filter_loader = \Mockery::mock( 'overload:WPML_Action_Filter_Loader' );
		$action_filter_loader->shouldReceive( 'load' )
		                     ->once()
		                     ->with( array(
			                     'WPML_Elementor_Translate_IDs_Factory',
			                     'WPML_Elementor_URLs_Factory',
		                     ) );

		$string_registration = \Mockery::mock( 'overload:WPML_PB_String_Registration' );

		\Mockery::mock( 'overload:WPML_Media_Image_Translate' );
		\Mockery::mock( 'overload:WPML_Media_Attachment_By_URL_Factory' );

		$media_translation = \Mockery::mock( 'overload:WPML_Elementor_Media_Translation' )
			->shouldReceive( 'add_hooks' )
			->once();

		$string_registration_factory = \Mockery::mock( 'overload:WPML_String_Registration_Factory' );
		$string_registration_factory->shouldReceive( 'create' )
		                            ->andReturn( $string_registration );

		$this->assertInstanceOf( 'WPML_Page_Builders_Integration', $elementor_factory->create() );
	}
}