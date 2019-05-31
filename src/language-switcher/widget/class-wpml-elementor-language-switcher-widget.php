<?php
namespace WPML\PB\Elementor\LanguageSwitcher;
use Elementor\Controls_Manager;

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
					'custom' => __('Drop Down', 'sitepress'),
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
				'label' => __('Show Active Language', 'sitepress'),
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

		$this->add_control(
			'align_items',
			[
				'label' => __('Align', 'sitepress'),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
				'separator' => 'before'
,				'options' => [
					'left' => [
						'title' => __('Left', 'sitepress'),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __('Center', 'sitepress'),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __('Right', 'sitepress'),
						'icon' => 'fa fa-align-right',
					]
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$args = array(
			'display_link_for_current_lang' => $settings['link_current'], //does not work with dropdown
			'flags' => $settings['display_flag'],
			'native' => $settings['native_language_name'],
			'translated' => $settings['language_name_current_language'],
			'type' => $settings['style']
		);

		do_action('wpml_language_switcher', $args);

	}
}