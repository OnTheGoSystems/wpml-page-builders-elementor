<?php

namespace WPML\PB\Elementor\LanguageSwitcher;

use Elementor\Plugin;

class LanguageSwitcher implements \IWPML_Backend_Action, \IWPML_Frontend_Action {

	public function add_hooks() {
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'initWidget' ] );
	}

	public function initWidget() {
		do_action( 'wpml_enable_custom_language_switcher', true );
		Plugin::instance()->widgets_manager->register_widget_type( new Widget() );
	}
}
