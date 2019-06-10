<?php
namespace WPML\PB\Elementor\LanguageSwitcher;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;

class Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'wpml-language-switcher';
	}

	public function get_title() {
		return __( 'WPML Language Switcher', 'sitepress' );
	}

	public function get_icon() {
		return 'fa fa-globe';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function _register_controls() {

		//Content Tab
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'sitepress' ),
				'type' => Controls_Manager::SECTION,
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'style',
			[
				'label' => __('Language switcher type', 'sitepress'),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'custom' => __('Drop Down', 'sitepress'), //wrong, it depends on settings in Languages -> Custom language switchers
					'footer' => __('Footer', 'sitepress'),
					'post_translations' => __('Post Translations', 'sitepress'),
				],
			]
		);

		$this->add_control(
			'display_flag',
			[
				'label' => __('Display Flag', 'sitepress'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 1,
				'default' => 1,
			]
		);

		$this->add_control(
			'link_current',
			[
				'label' => __('Show Active Language - has to be ON with Dropdown', 'sitepress'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 1,
				'default' => 1,
			]
		);

		$this->add_control(
			'native_language_name',
			[
				'label' => __('Native language name', 'sitepress'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 1,
				'default' => 1,
			]
		);

		$this->add_control(
			'language_name_current_language',
			[
				'label' => __('Language name in current language', 'sitepress'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 1,
				'default' => 1,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style', 'sitepress' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'style_tabs' );

		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => __( 'Normal', 'sitepress' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'switcher_typography',
				'selector' => '{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item',
			]
		);


		$this->add_control(
			'switcher_color',
			[
				'label' => __( 'Text Color', 'sitepress' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item .wpml-ls-link, 
					{{WRAPPER}} .wpml-elementor-ls .wpml-ls-legacy-dropdown a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => __( 'Hover', 'sitepress' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'switcher_hover_typography',
				'selector' => '{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item:hover,
					{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item.wpml-ls-item__active,
					{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item.highlighted,
					{{WRAPPER}} .wpml-elementor-ls .wpml-ls-item:focus',
			]
		);

		$this->add_control(
			'switcher_hover_color',
			[
				'label' => __( 'Text Color', 'sitepress' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
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
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'language_flag',
			[
				'label' => __( 'Language Flag', 'sitepress' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'display_flag' => [ 1 ],
				],
			]
		);

		$this->add_control(
			'flag_margin',
			[
				'label' => __( 'Margin', 'sitepress' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wpml-elementor-ls .wpml-ls-flag' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'post_translation_text',
			[
				'label' => __( 'Post Translation Text', 'sitepress' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => [ 'post_translations' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_translation_typography',
				'selector' => '{{WRAPPER}} .wpml-elementor-ls .wpml-ls-statics-post_translations',
			]
		);

		$this->add_control(
			'post_translation_color',
			[
				'label' => __( 'Text Color', 'sitepress' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpml-elementor-ls .wpml-ls-statics-post_translations' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute('wpml-elementor-ls', 'class', [
			'wpml-elementor-ls',
		]);

		$args = array(
			'display_link_for_current_lang' => ($settings['style'] == 'custom' ? 1 : $settings['link_current']), //forcing in dropdown case
			'flags' => $settings['display_flag'],
			'native' => $settings['native_language_name'],
			'translated' => $settings['language_name_current_language'],
			'type' => $settings['style']
		);

		echo "<div " . $this->get_render_attribute_string('wpml-elementor-ls') . ">";
		do_action('wpml_language_switcher', $args);
		echo "</div>";

	}
}