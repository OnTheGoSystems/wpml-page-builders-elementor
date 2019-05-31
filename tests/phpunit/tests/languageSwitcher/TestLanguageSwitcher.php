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
		\WP_Mock::expectActionAdded( 'elementor/widgets/widgets_registered', [ $subject, 'initWidget' ] );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function itShoulInitWidget() {
		\WP_Mock::expectAction( 'wpml_enable_custom_language_switcher', true );
		$subject = new LanguageSwitcher();

		$widgetManager = $this->getMockBuilder( 'Elementor\Widgets_Manager' )
		                      ->setMethods( [ 'register_widget_type' ] )
			                  ->disableOriginalConstructor()
		                      ->getMock();
		$widgetManager->expects( $this->once() )->method( 'register_widget_type' )->with( new Widget() );

		$elementorPlugin = \Mockery::mock( 'alias:Elementor\Plugin' );
		$elementorPlugin->shouldReceive( 'instance' )->andReturnSelf();
		$elementorPlugin->widgets_manager = $widgetManager;

		$subject->initWidget();
	}
}
