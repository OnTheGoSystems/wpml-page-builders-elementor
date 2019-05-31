<?php

namespace WPML\PB\Elementor\LanguageSwitcher;

class LanguageSwitcher implements \IWPML_Action {

	public function add_hooks() {

		add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
		// Register Widget Styles
		add_action('elementor/frontend/after_enqueue_styles', [$this, 'widget_styles']);

	}

	public function init_widgets() {

		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widget());

	}

	public function widget_styles() {

		wp_register_style('language-switcher', plugins_url('vendor/wpml/page-builders-elementor/assets/language-switcher.css', __FILE__));
	}

}