<?php

namespace WPML\PB\Elementor\LanguageSwitcher;

/**
 * @group language-switcher
 */
class TestLanguageSwitcher extends \OTGS_TestCase {

	/**
	 * @test
	 */
	public function itShouldAddHooks() {
		$subject = new LanguageSwitcher();
		\WP_Mock::expectActionAdded( 'elementor/widgets/widgets_registered', [ $subject, 'registerWidgets' ] );
		\WP_Mock::expectFilterAdded( 'wpml_custom_language_switcher_is_enabled', [ $subject, 'enableCustomLanguageSwitcher' ] );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function itShouldregisterWidgets() {
		$subject = new LanguageSwitcher();

		$widgetManager = $this->getMockBuilder( 'Elementor\Widgets_Manager' )
		                      ->setMethods( [ 'register_widget_type' ] )
			                  ->disableOriginalConstructor()
		                      ->getMock();
		$widgetManager->expects( $this->once() )->method( 'register_widget_type' )->with( new Widget() );

		$elementorPlugin = \Mockery::mock( 'alias:Elementor\Plugin' );
		$elementorPlugin->shouldReceive( 'instance' )->andReturnSelf();
		$elementorPlugin->widgets_manager = $widgetManager;

		$subject->registerWidgets();
	}

	/**
	 * @test
	 * @group wpmlcore-6669
	 */
	public function itShouldEnableCustomLanguageSwitcher() {
		$subject = new LanguageSwitcher();

		$this->assertTrue( $subject->enableCustomLanguageSwitcher() );
	}
}
