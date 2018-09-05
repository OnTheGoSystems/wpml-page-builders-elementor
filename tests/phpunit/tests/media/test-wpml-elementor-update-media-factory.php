<?php

/**
 * @group media
 */
class Test_WPML_Elementor_Update_Media_Factory extends OTGS_TestCase {

	public function setUp() {
		parent::setUp();
		\Mockery::mock( 'alias:WPML_Page_Builders_Update_Media' );
	}

	/**
	 * @test
	 */
	public function it_should_implement_iwpml_pb_media_update_factory() {
		$subject = new WPML_Elementor_Update_Media_Factory();
		$this->assertInstanceOf( 'IWPML_PB_Media_Update_Factory', $subject );
	}

	/**
	 * @test
	 */
	public function it_should_return_an_instance_of_page_builders_media_update() {
		$subject = new WPML_Elementor_Update_Media_Factory();
		$this->assertInstanceOf( 'WPML_Page_Builders_Update_Media', $subject->create() );
	}
}

if ( ! interface_exists( 'IWPML_PB_Media_Update_Factory' ) ) {
	interface IWPML_PB_Media_Update_Factory {}
}
