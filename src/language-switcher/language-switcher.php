<?php

namespace WPML\PB\Elementor\LanguageSwitcher;

use Elementor\Plugin;

class LanguageSwitcher implements \IWPML_Backend_Action, \IWPML_Frontend_Action {

	public function add_hooks() {
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'registerWidgets' ] );
	}

	public function registerWidgets() {
		Plugin::instance()->widgets_manager->register_widget_type( new Widget() );
	}
}
