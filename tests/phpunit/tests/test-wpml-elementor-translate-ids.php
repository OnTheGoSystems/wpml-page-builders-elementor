<?php

class Test_WPML_Elementor_Translate_IDs extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_adds_hooks() {

		$subject = new WPML_Elementor_Translate_IDs( \Mockery::mock( 'WPML_Debug_BackTrace' ) );

		$this->expectFilterAdded( 'elementor/theme/get_location_templates/template_id', array(
			$subject,
			'translate_theme_location_template_id'
		), 10 );
		$this->expectFilterAdded( 'elementor/documents/get/post_id', array(
			$subject,
			'translate_template_id'
		), 10 );
		$this->expectFilterAdded( 'elementor/frontend/builder_content_data', array(
			$subject,
			'translate_global_widget_ids'
		), 10, 2 );

		$subject->add_hooks();

	}

	/**
	 * @test
	 */
	public function it_should_translate_template_id() {

		$template_id   = 123;
		$translated_id = 456;
		$post_type     = 'anything';

		\WP_Mock::userFunction( 'get_post_type',
			array(
				'args'   => $template_id,
				'return' => $post_type
			)
		);

		\WP_Mock::onFilter( 'wpml_object_id' )
		        ->with( $template_id, $post_type, true )
		        ->reply( $translated_id );

		$subject = new WPML_Elementor_Translate_IDs( \Mockery::mock( 'WPML_Debug_BackTrace' ) );

		$this->assertEquals( $translated_id, $subject->translate_theme_location_template_id( $template_id ) );

	}

	/**
	 * @test
	 */
	public function it_should_translate_template_id_for_WP_widget() {

		$template_id   = 123;
		$translated_id = 456;
		$post_type     = 'anything';

		\WP_Mock::userFunction( 'get_post_type',
			array(
				'args'   => $template_id,
				'return' => $post_type
			)
		);

		\WP_Mock::onFilter( 'wpml_object_id' )
		        ->with( $template_id, $post_type, true )
		        ->reply( $translated_id );

		$debug_backtrace = \Mockery::mock( 'WPML_Debug_BackTrace' );
		$debug_backtrace->shouldReceive( 'is_class_function_in_call_stack' )
		                ->with( 'ElementorPro\Modules\Library\WP_Widgets\Elementor_Library', 'widget' )
		                ->andReturn( true );

		$subject = new WPML_Elementor_Translate_IDs( $debug_backtrace );

		$this->assertEquals( $translated_id, $subject->translate_template_id( $template_id ) );

	}

	/**
	 * @test
	 */
	public function it_should_translate_template_id_for_shortcode() {

		$template_id   = 123;
		$translated_id = 456;
		$post_type     = 'anything';

		\WP_Mock::userFunction( 'get_post_type',
			array(
				'args'   => $template_id,
				'return' => $post_type
			)
		);

		\WP_Mock::onFilter( 'wpml_object_id' )
		        ->with( $template_id, $post_type, true )
		        ->reply( $translated_id );

		$debug_backtrace = \Mockery::mock( 'WPML_Debug_BackTrace' );
		$debug_backtrace->shouldReceive( 'is_class_function_in_call_stack' )
		                ->with( 'ElementorPro\Modules\Library\WP_Widgets\Elementor_Library', 'widget' )
		                ->andReturn( false );
		$debug_backtrace->shouldReceive( 'is_class_function_in_call_stack' )
		                ->with( 'ElementorPro\Modules\Library\Classes\Shortcode', 'shortcode' )
		                ->andReturn( true );

		$subject = new WPML_Elementor_Translate_IDs( $debug_backtrace );

		$this->assertEquals( $translated_id, $subject->translate_template_id( $template_id ) );

	}

	/**
	 * @test
	 */
	public function it_should_translate_template_id_for_template_widget() {

		$template_id   = 123;
		$translated_id = 456;
		$post_type     = 'anything';

		\WP_Mock::userFunction( 'get_post_type',
			array(
				'args'   => $template_id,
				'return' => $post_type
			)
		);

		\WP_Mock::onFilter( 'wpml_object_id' )
		        ->with( $template_id, $post_type, true )
		        ->reply( $translated_id );

		$debug_backtrace = \Mockery::mock( 'WPML_Debug_BackTrace' );
		$debug_backtrace->shouldReceive( 'is_class_function_in_call_stack' )
		                ->with( 'ElementorPro\Modules\Library\WP_Widgets\Elementor_Library', 'widget' )
		                ->andReturn( false );
		$debug_backtrace->shouldReceive( 'is_class_function_in_call_stack' )
		                ->with( 'ElementorPro\Modules\Library\Classes\Shortcode', 'shortcode' )
		                ->andReturn( false );
		$debug_backtrace->shouldReceive( 'is_class_function_in_call_stack' )
		                ->with( 'ElementorPro\Modules\Library\Widgets\Template', 'render' )
		                ->andReturn( true );

		$subject = new WPML_Elementor_Translate_IDs( $debug_backtrace );

		$this->assertEquals( $translated_id, $subject->translate_template_id( $template_id ) );

	}

	/**
	 * @test
	 */
	public function it_should_not_translate_template_id_for_other_calls() {

		$template_id   = 123;
		$translated_id = 456;
		$post_type     = 'anything';

		\WP_Mock::userFunction( 'get_post_type',
			array(
				'args'   => $template_id,
				'return' => $post_type
			)
		);

		\WP_Mock::onFilter( 'wpml_object_id' )
		        ->with( $template_id, $post_type, true )
		        ->reply( $translated_id );

		$debug_backtrace = \Mockery::mock( 'WPML_Debug_BackTrace' );
		$debug_backtrace->shouldReceive( 'is_class_function_in_call_stack' )
		                ->andReturn( false );

		$subject = new WPML_Elementor_Translate_IDs( $debug_backtrace );

		$this->assertEquals( $template_id, $subject->translate_template_id( $template_id ) );
	}

}