<?php

class WPML_Elementor_URLs_Factory implements IWPML_Backend_Action_Loader {

	public function create() {
		global $sitepress, $wpdb;

		$domains = null;
		if ( WPML_LANGUAGE_NEGOTIATION_TYPE_DOMAIN === (int) $sitepress->get_setting( 'language_negotiation_type' ) ) {
			$domains = new WPML_Language_Domains( $sitepress, new WPML_URL_Converter_Url_Helper( $wpdb ) );
		}

		$element_factory = new WPML_Translation_Element_Factory( $sitepress );

		return new WPML_Elementor_URLs( $element_factory, $domains, $sitepress );
	}
}
