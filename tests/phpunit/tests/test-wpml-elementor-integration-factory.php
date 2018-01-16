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
		global $sitepress;

		$bb_factory = new WPML_Elementor_Integration_Factory();

		$absolute_links = \Mockery::mock('overload:AbsoluteLinks');
		$absolute_to_permalinks = \Mockery::mock('overload:WPML_Absolute_To_Permalinks');
		$translate_link_targets = \Mockery::mock('overload:WPML_Translate_Link_Targets');
		$string_registration = \Mockery::mock('overload:WPML_PB_String_Registration');
		$hooks_strategy = \Mockery::mock('overload:WPML_PB_API_Hooks_Strategy');
		$string_factory = \Mockery::mock('overload:WPML_ST_String_Factory');

		$sitepress = $this->getMockBuilder( 'SitePress' )
			->setMethods( array( 'get_active_languages' ) )
			->disableOriginalConstructor()
			->getMock();

		$bb_factory->create();

		$this->assertInstanceOf( 'WPML_Page_Builders_Integration', $bb_factory->create() );
	}
}