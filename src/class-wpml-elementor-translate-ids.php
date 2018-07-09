<?php

class WPML_Elementor_Translate_IDs implements IWPML_Action {

	/** @var WPML_Debug_BackTrace */
	private $debug_backtrace;

	public function __construct( WPML_Debug_BackTrace $debug_backtrace ) {
		$this->debug_backtrace = $debug_backtrace;
	}

	public function add_hooks() {
		add_filter( 'elementor/theme/get_location_templates/template_id', array( $this, 'translate_theme_location_template_id' ) );
		add_filter( 'elementor/documents/get/post_id', array(
			$this,
			'translate_template_id'
		) );
		add_filter( 'elementor/frontend/builder_content_data', array( $this, 'translate_global_widget_ids' ), 10, 2 );
	}

	public function translate_theme_location_template_id( $template_id ) {
		return $this->translate_id( $template_id );
	}

	public function translate_template_id( $template_id ) {
		if ( $this->is_WP_widget_call() || $this->is_shortcode_call() || $this->is_template_widget_call() ) {
			$template_id = $this->translate_id( $template_id );
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

	private function is_template_widget_call() {
		return $this->debug_backtrace->is_class_function_in_call_stack(
			'ElementorPro\Modules\Library\Widgets\Template',
			'render' );
	}

	public function translate_global_widget_ids( $data_array, $post_id ) {
		foreach ( $data_array as &$data ) {
			if ( isset( $data['elType'] ) && 'widget' === $data['elType'] && 'global' === $data['widgetType'] ) {
				$data['templateID'] = $this->translate_id( $data['templateID'] );
			}
			$data['elements'] = $this->translate_global_widget_ids( $data['elements'], $post_id );
		}

		return $data_array;
	}

	private function translate_id( $template_id ) {
		return apply_filters( 'wpml_object_id', $template_id, get_post_type( $template_id ), true );
	}
}
