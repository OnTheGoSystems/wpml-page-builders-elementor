<?php

namespace WPML\PB\Elementor\LanguageSwitcher;

class LanguageSwitcher implements \IWPML_Backend_Action, \IWPML_Frontend_Action {

	public function add_hooks() {

		add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);

	}

	public function init_widgets() {

		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widget());

	}

}