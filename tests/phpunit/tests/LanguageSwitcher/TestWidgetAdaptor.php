<?php

namespace WPML\PB\Elementor\LanguageSwitcher;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;

/**
 * @group language-switcher
 */
class TestWidgetAdaptor extends \OTGS_TestCase {

	/**
	 * @test
	 */
	public function itShouldUsePublicMethodsFromWidget() {
		$widgetReflection = new \ReflectionClass( Widget::class );

		foreach ( $this->getPublicWidgetMethods() as $methodName ) {
			$this->assertTrue( $widgetReflection->getMethod( $methodName )->isPublic() );
		}
	}

	/**
	 * @test
	 */
	public function itShouldGetName() {
		$subject = $this->getSubject();
		$this->assertEquals( 'wpml-language-switcher', $subject->getName() );
	}

	/**
	 * @test
	 */
	public function itShouldGetTitle() {
		$subject = $this->getSubject();
		$this->assertEquals( 'WPML Language Switcher', $subject->getTitle() );
	}

	/**
	 * @test
	 */
	public function itShouldGetIcon() {
		$subject = $this->getSubject();
		$this->assertEquals( 'fa fa-globe', $subject->getIcon() );
	}

	/**
	 * @test
	 */
	public function itShouldGetCategories() {
		$subject = $this->getSubject();
		$this->assertEquals( [ 'general' ], $subject->getCategories() );
	}

	/**
	 * @test
	 */
	public function itShouldRegisterControls() {
		$widget = $this->getWidget();

		$this->setExpectedAddedSection( $widget );
		$this->setExpectedAddedControl( $widget );
		$this->setExpectedAddedTabs( $widget );
		$this->setExpectedAddedGroupControls( $widget );

		$subject = $this->getSubject( $widget );

		$subject->registerControls();
	}

	/**
	 * @param \PHPUnit_Framework_MockObject_MockObject $widget
	 */
	private function setExpectedAddedSection( $widget ) {
		$sectionsNumber = 4;

		$widget->expects( $this->exactly( $sectionsNumber ) )
		       ->method( 'start_controls_section' )
		       ->withConsecutive(
			       [
				       'section_content',
				       [
					       'label' => 'Content',
					       'type'  => Controls_Manager::SECTION,
					       'tab'   => Controls_Manager::TAB_CONTENT,
				       ],
			       ],
			       [
				       'style_section',
				       [
					       'label' => 'Style',
					       'tab'   => Controls_Manager::TAB_STYLE,
				       ]
			       ],
			       [
				       'language_flag',
				       [
					       'label'     => 'Language Flag',
					       'tab'       => Controls_Manager::TAB_STYLE,
					       'condition' => [
						       'display_flag' => [ 1 ],
					       ],
				       ]
			       ],
			       [
				       'post_translation_text',
				       [
					       'label'     => 'Post Translation Text',
					       'tab'       => Controls_Manager::TAB_STYLE,
					       'condition' => [
						       'style' => [ 'post_translations' ],
					       ],
				       ]
			       ]
		       );

		$widget->expects( $this->exactly( $sectionsNumber ) )
		       ->method( 'end_controls_section' );
	}

	/**
	 * @param \PHPUnit_Framework_MockObject_MockObject $widget
	 */
	private function setExpectedAddedControl( $widget ) {
		$widget->expects( $this->exactly( 13 ) )
		       ->method( 'add_control' )
		       ->withConsecutive(
			       [
				       'style',
				       [
					       'label'   => 'Language switcher type',
					       'type'    => Controls_Manager::SELECT,
					       'default' => 'custom',
					       'options' => [
						       'custom'            => 'Drop Down',
						       'footer'            => 'Footer',
						       'post_translations' => 'Post Translations',
					       ],
				       ]
			       ],
			       [
				       'display_flag',
				       [
					       'label'        => 'Display Flag',
					       'type'         => Controls_Manager::SWITCHER,
					       'return_value' => 1,
					       'default'      => 1,
				       ],
			       ],
			       [
				       'link_current',
				       [
					       'label'        => 'Show Active Language - has to be ON with Dropdown',
					       'type'         => Controls_Manager::SWITCHER,
					       'return_value' => 1,
					       'default'      => 1,
				       ],
			       ],
			       [
				       'native_language_name',
				       [
					       'label'        => 'Native language name',
					       'type'         => Controls_Manager::SWITCHER,
					       'return_value' => 1,
					       'default'      => 1,
				       ],
			       ],
			       [
				       'language_name_current_language',
				       [
					       'label'        => 'Language name in current language',
					       'type'         => Controls_Manager::SWITCHER,
					       'return_value' => 1,
					       'default'      => 1,
				       ],
			       ],
			       [
				       'switcher_text_color',
				       [
					       'label'     => 'Text Color',
					       'type'      => Controls_Manager::COLOR,
					       'scheme'    => [
						       'type'  => Scheme_Color::get_type(),
						       'value' => Scheme_Color::COLOR_3,
					       ],
					       'default'   => '',
					       'selectors' => [
						       '{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item .wpml-ls-link, 
					{{WRAPPER}} .wpml-elementor-ls .wpml-ls-legacy-dropdown a' => 'color: {{VALUE}}',
					       ],
				       ],
			       ],
			       [
				       'switcher_bg_color',
				       [
					       'label'     => 'Background Color',
					       'type'      => Controls_Manager::COLOR,
					       'default'   => '',
					       'selectors' => [
						       '{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item .wpml-ls-link, 
					{{WRAPPER}} .wpml-elementor-ls .wpml-ls-legacy-dropdown a' => 'background-color: {{VALUE}}',
					       ],
				       ],
			       ],
			       [
				       'switcher_hover_color',
				       [
					       'label'     => 'Text Color',
					       'type'      => Controls_Manager::COLOR,
					       'scheme'    => [
						       'type'  => Scheme_Color::get_type(),
						       'value' => Scheme_Color::COLOR_4,
					       ],
					       'selectors' => [
						       '{{WRAPPER}} .wpml-elementor-ls .wpml-ls-legacy-dropdown a:hover,
					{{WRAPPER}} .wpml-elementor-ls .wpml-ls-legacy-dropdown a:focus,
					{{WRAPPER}} .wpml-elementor-ls .wpml-ls-legacy-dropdown .wpml-ls-current-language:hover>a,
					{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item .wpml-ls-link:hover,
					{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item .wpml-ls-link.wpml-ls-link__active,
					{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item .wpml-ls-link.highlighted,
					{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item .wpml-ls-link:focus' => 'color: {{VALUE}}',
					       ],
				       ],
			       ],
			       [
				       'flag_margin',
				       [
					       'label'      => 'Margin',
					       'type'       => Controls_Manager::DIMENSIONS,
					       'size_units' => [ 'px', '%', 'em' ],
					       'selectors'  => [
						       '{{WRAPPER}} .wpml-elementor-ls .wpml-ls-flag' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					       ],
				       ],
			       ],
			       [
				       'post_translation_color',
				       [
					       'label'     => 'Text Color',
					       'type'      => Controls_Manager::COLOR,
					       'scheme'    => [
						       'type'  => Scheme_Color::get_type(),
						       'value' => Scheme_Color::COLOR_3,
					       ],
					       'default'   => '',
					       'selectors' => [
						       '{{WRAPPER}} .wpml-elementor-ls .wpml-ls-statics-post_translations' => 'color: {{VALUE}}',
					       ],
				       ],
			       ],
			       [
				       'post_translation_bg_color',
				       [
					       'label'     => 'Background Color',
					       'type'      => Controls_Manager::COLOR,
					       'default'   => '',
					       'selectors' => [
						       '{{WRAPPER}} .wpml-elementor-ls .wpml-ls-statics-post_translations' => 'background-color: {{VALUE}}',
					       ],
				       ],
			       ],
			       [
				       'post_translation_padding',
				       [
					       'label'      => 'Padding',
					       'type'       => Controls_Manager::DIMENSIONS,
					       'size_units' => [ 'px', '%', 'em' ],
					       'selectors'  => [
						       '{{WRAPPER}} .wpml-elementor-ls .wpml-ls-statics-post_translations' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					       ],
				       ],
			       ],
			       [
				       'post_translation_margin',
				       [
					       'label'      => 'Margin',
					       'type'       => Controls_Manager::DIMENSIONS,
					       'size_units' => [ 'px', '%', 'em' ],
					       'selectors'  => [
						       '{{WRAPPER}} .wpml-elementor-ls .wpml-ls-statics-post_translations' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					       ],
				       ],
			       ]
		       );
	}

	/**
	 * @param \PHPUnit_Framework_MockObject_MockObject $widget
	 */
	private function setExpectedAddedTabs( $widget ) {
		$tabsNumber = 2;

		$widget->expects( $this->once() )
		       ->method( 'start_controls_tabs' )
		       ->with( 'style_tabs' );

		$widget->expects( $this->once() )
		       ->method( 'end_controls_tabs' );

		$widget->expects( $this->exactly( $tabsNumber ) )
		       ->method( 'start_controls_tab' )
		       ->withConsecutive(
			       [
				       'style_normal_tab',
				       [
					       'label' => 'Normal'
				       ],
			       ],
			       [
				       'style_hover_tab',
				       [
					       'label' => 'Hover',
				       ],
			       ]
		       );

		$widget->expects( $this->exactly( $tabsNumber ) )
		       ->method( 'end_controls_tab' );
	}

	/**
	 * @param \PHPUnit_Framework_MockObject_MockObject $widget
	 */
	private function setExpectedAddedGroupControls( $widget ) {
		$widget->expects( $this->exactly( 3 ) )
		       ->method( 'add_group_control' )
		       ->withConsecutive(
					[
						Group_Control_Typography::get_type(),
						[
						   'name'     => 'switcher_typography',
						   'selector' => '{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item',
						],
					],
					[
						Group_Control_Typography::get_type(),
						[
						   'name'     => 'switcher_hover_typography',
						   'selector' => '{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item:hover,
					{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item.wpml-ls-item__active,
					{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item.highlighted,
					{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item:focus',
						],
					],
					[
						Group_Control_Typography::get_type(),
						[
						   'name'     => 'post_translation_typography',
						   'selector' => '{{WRAPPER}} .wpml-elementor-ls .wpml-ls-statics-post_translations',
						],
					]
		       );
	}

	/**
	 * @test
	 * @dataProvider dpRender
	 *
	 * @param array $settings
	 * @param array $expectedArgs
	 */
	public function itShouldRender( array $settings, array $expectedArgs ) {
		$renderAttribute      = 'some-attribute';
		$languageSwitcherHtml = 'The language switcher';

		$widget = $this->getWidget();

		$widget->method( 'get_settings_for_display' )
			->willReturn( $settings );

		$widget->expects( $this->once() )
			->method( 'add_render_attribute' )
			->with( 'wpml-elementor-ls', 'class', [ 'wpml-elementor-ls' ] );

		\WP_Mock::onAction( 'wpml_language_switcher' )
			->with( $expectedArgs )
			->perform( function() use ( $languageSwitcherHtml ) {
				echo $languageSwitcherHtml;
			} );

		$widget->method( 'get_render_attribute_string' )
			->with( 'wpml-elementor-ls' )
			->willReturn( $renderAttribute );

		$subject = $this->getSubject( $widget );

		ob_start();
		$subject->render();
		$output = ob_get_clean();

		$this->assertEquals(
			"<div $renderAttribute>$languageSwitcherHtml</div>",
			$output
		);
	}

	public function dpRender() {
		return [
			'style == custom' => [
				[
					'style'                          => 'custom',
					'link_current'                   => 'param for link_current',
					'display_flag'                   => 'param for display_flag',
					'native_language_name'           => 'param for native_language_name',
					'language_name_current_language' => 'param for language_name_current_language',
				],
				[
					'display_link_for_current_lang' => 1,
					'flags'                         => 'param for display_flag',
					'native'                        => 'param for native_language_name',
					'translated'                    => 'param for language_name_current_language',
					'type'                          => 'custom',
				]
			],
			'style != custom' => [
				[
					'style'                          => 'some-style',
					'link_current'                   => 'param for link_current',
					'display_flag'                   => 'param for display_flag',
					'native_language_name'           => 'param for native_language_name',
					'language_name_current_language' => 'param for language_name_current_language',
				],
				[
					'display_link_for_current_lang' => 'param for link_current',
					'flags'                         => 'param for display_flag',
					'native'                        => 'param for native_language_name',
					'translated'                    => 'param for language_name_current_language',
					'type'                          => 'some-style',
				]
			],
		];
	}

	private function getSubject( $widget = null ) {
		$widget  = $widget ?: $this->getWidget();
		$adaptor = new WidgetAdaptor();
		$adaptor->setTarget( $widget );

		return $adaptor;
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	private function getWidget() {
		return $this->getMockBuilder( Widget::class )
			->setMethods( $this->getPublicWidgetMethods() )
			->disableOriginalConstructor()->getMock();
	}

	private function getPublicWidgetMethods() {
		return [
			'start_controls_section',
			'add_control',
			'end_controls_section',
			'start_controls_tabs',
			'start_controls_tab',
			'add_group_control',
			'end_controls_tab',
			'end_controls_tabs',
			'get_settings_for_display',
			'add_render_attribute',
			'get_render_attribute_string',
		];
	}
}
