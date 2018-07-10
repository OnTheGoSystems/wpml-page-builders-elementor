<?php

class WPML_Elementor_URLs implements IWPML_Action {

	/** @var SitePress  */
	private $sitepress;

	public function __construct(
		WPML_Translation_Element_Factory $element_factory,
		WPML_Language_Domains $domains = null
	) {
		$this->element_factory = $element_factory;
		$this->domains = $domains;
	}

	public function add_hooks() {
		if ( $this->domains ) {
			add_filter( 'elementor/document/urls/edit ', array( $this, 'adjust_edit_with_elementor_url' ), 10, 2 );
		}
	}

	public function adjust_edit_with_elementor_url( $url, $elementor_document ) {
		$post = $elementor_document->get_main_post();

		$post_element = $this->element_factory->create( $post->ID, WPML_Translation_Element_Factory::ELEMENT_TYPE_POST );
		$post_language = $post_element->get_language_code();

		if ( $post_language ) {
			$url_parts = wpml_parse_url( $url );
			$url       = $url_parts['scheme'] . '://' . $this->domains->get( $post_language ) . $url_parts['path'] . '?' . $url_parts['query'];
		}

		return $url;
	}
}
