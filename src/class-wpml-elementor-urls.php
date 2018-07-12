<?php

class WPML_Elementor_URLs implements IWPML_Action {

	/** @var WPML_Translation_Element_Factory  */
	private $element_factory;

	/** @var WPML_Language_Domains  */
	private $domains;

	private $sitepress;

	public function __construct(
		WPML_Translation_Element_Factory $element_factory,
		WPML_Language_Domains $domains = null,
		SitePress $sitepress
	) {
		$this->element_factory = $element_factory;
		$this->domains         = $domains;
		$this->sitepress       = $sitepress;
	}

	public function add_hooks() {
		if ( $this->domains ) {
			add_filter( 'elementor/document/urls/edit ', array( $this, 'adjust_edit_with_elementor_url' ), 10, 2 );
		}
	}

	public function adjust_edit_with_elementor_url( $url, $elementor_document ) {
		$post = $elementor_document->get_main_post();

		$post_element  = $this->element_factory->create_post( $post->ID );
		$post_language = $post_element->get_language_code();

		if ( ! $post_language ) {
			$post_language = $this->sitepress->get_current_language();
		}

		$url_parts         = wpml_parse_url( $url );
		$url_parts['host'] = $this->domains->get( $post_language );
		$url               = http_build_url( $url_parts );

		return $url;
	}
}
