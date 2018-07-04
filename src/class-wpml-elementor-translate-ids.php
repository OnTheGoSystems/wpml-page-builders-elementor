<?php

class WPML_Elementor_Translate_IDs implements IWPML_Action {

	/** @var WPML_Debug_BackTrace */
	private $debug_backtrace;

	public function __construct( WPML_Debug_BackTrace $debug_backtrace ) {
		$this->debug_backtrace = $debug_backtrace;
	}

	public function add_hooks() {
		add_filter( 'elementor/theme/get_location_templates/template_id', array( $this, 'translate_template_id' ) );
		add_filter( 'elementor/documents/get/post_id', array(
			$this,
			'translate_template_id_for_WP_widget_and_shortcode'
		) );
		add_filter( 'elementor/frontend/builder_content_data', array( $this, 'translate_global_widget_ids' ), 10, 2 );
	}

	public function translate_template_id( $template_id ) {
		return apply_filters( 'wpml_object_id', $template_id, get_post_type( $template_id ), true );
	}

	public function translate_template_id_for_WP_widget_and_shortcode( $template_id ) {
		if ( $this->is_WP_widget_call() || $this->is_shortcode_call() ) {
			$template_id = $this->translate_template_id( $template_id );
		}

		return $template_id;
	}

	private function is_WP_widget_call() {
		return $this->debug_backtrace->is_class_function_in_call_stack(
			'ElementorPro\Modules\Library\WP_Widgets\Elementor_Library',
			'widget' );
	}

	private function is_shortcode_call() {
		return $this->debug_backtrace->is_class_function_in_call_stack(
			'ElementorPro\Modules\Library\Classes\Shortcode',
			'shortcode' );
	}

	public function translate_global_widget_ids( $data_array, $post_id ) {
		foreach ( $data_array as &$data ) {
			if ( isset( $data['elType'] ) && 'widget' === $data['elType'] && 'global' === $data['widgetType'] ) {
				$data['templateID'] = $this->translate_template_id( $data['templateID'] );
			}
			$data['elements'] = $this->translate_global_widget_ids( $data['elements'], $post_id );
		}

		return $data_array;
	}
}
