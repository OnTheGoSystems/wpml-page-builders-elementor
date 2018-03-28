<?php

/**
 * Class WPML_Elementor_Form
 */
class WPML_Elementor_Form extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'form_fields';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'field_label', 'placeholder', 'field_html' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'field_label':
				return esc_html__( 'Form: Field label', 'sitepress' );

			case 'placeholder':
				return esc_html__( 'Form: Field placeholder', 'sitepress' );

			case 'field_html':
				return esc_html__( 'Form: Field HTML', 'sitepress' );

			default:
				return '';
		}
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'field_label':
			case 'placeholder':
				return 'LINE';

			case 'field_html':
				return 'VISUAL';				

			default:
				return '';
		}
	}

}
