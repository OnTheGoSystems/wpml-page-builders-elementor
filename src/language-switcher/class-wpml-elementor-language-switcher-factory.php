<?php

namespace WPML\PB\Elementor\LanguageSwitcher;

class Factory implements \IWPML_Backend_Action_Loader, \IWPML_Frontend_Action_Loader {
	public function create() {
		return new LanguageSwitcher();
	}
}