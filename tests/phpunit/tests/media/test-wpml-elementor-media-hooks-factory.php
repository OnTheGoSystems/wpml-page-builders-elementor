<?php

/**
 * @group media
 */
class Test_WPML_Elementor_Media_Hooks_Factory extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_should_create_and_return_an_instance() {
		$GLOBALS['sitepress'] = $this->getMockBuilder( 'SitePress' )->getMock();
		\Mockery::mock( 'alias:WPML_Page_Builders_Media_Hooks' );
		$subject = new WPML_Elementor_Media_Hooks_Factory();
		$this->assertInstanceOf( 'WPML_Page_Builders_Media_Hooks', $subject->create() );
	}
}

if ( ! interface_exists( 'IWPML_PB_Media_Update_Factory' ) ) {
	interface IWPML_PB_Media_Update_Factory {}
}
