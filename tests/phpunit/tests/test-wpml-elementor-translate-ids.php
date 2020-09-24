<?php

class Test_WPML_Elementor_Translate_IDs extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_adds_hooks() {

		$subject = new WPML_Elementor_Translate_IDs( \Mockery::mock( '\WPML\Utils\DebugBackTrace' ) );

		$this->expectFilterAdded( 'elementor/theme/get_location_templates/template_id', array(
			$subject,
			'translate_theme_location_template_id'
		), 10 );
		$this->expectFilterAdded( 'elementor/theme/get_location_templates/condition_sub_id', array(
			$subject,
			'translate_location_condition_sub_id'
		), 10, 2 );
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

		$subject = new WPML_Elementor_Translate_IDs( \Mockery::mock( '\WPML\Utils\DebugBackTrace' ) );

		$this->assertEquals( $translated_id, $subject->translate_theme_location_template_id( $template_id ) );

	}

	/**
	 * @test
	 * @dataProvider dp_not_translate_location_condition_sub_id
	 * @group wpmlcore-5647
	 *
	 * @param mixed $sub_id
	 * @param array $parsed_conditions
	 */
	public function it_should_not_translate_location_condition_sub_id( $sub_id, $parsed_conditions ) {
		\WP_Mock::userFunction( 'get_post_type', array( 'times' => 0 ) );

		$subject = new WPML_Elementor_Translate_IDs( \Mockery::mock( '\WPML\Utils\DebugBackTrace' ) );

		$this->assertEquals( $sub_id, $subject->translate_location_condition_sub_id( $sub_id, $parsed_conditions ) );
	}

	public function dp_not_translate_location_condition_sub_id() {
		$sub_id = mt_rand( 1, 10 );

		return array(
			'missing sub_name'          => array( $sub_id, array() ),
			'sub_id is not a valid int' => array( 'something', array() ),
			'sub_id equals 0'           => array( 0, array() ),
		);
	}

	/**
	 * @test
	 * @group wpmlcore-5647
	 */
	public function it_should_translate_location_condition_sub_id_for_singular_post_or_taxonomy_term_archive() {
		$sub_id           = mt_rand( 1, 10 );
		$translate_id     = mt_rand( 11, 20 );
		$post_type        = 'hotel';
		$parsed_conditions = array( 'sub_name' => $post_type );

		\WP_Mock::userFunction( 'get_post_type',
			array(
				'times' => 0,
			)
		);

		\WP_Mock::onFilter( 'wpml_object_id' )
		        ->with( $sub_id, $post_type, true )
		        ->reply( $translate_id );

		$subject = new WPML_Elementor_Translate_IDs( \Mockery::mock( '\WPML\Utils\DebugBackTrace' ) );

		$this->assertEquals( $translate_id, $subject->translate_location_condition_sub_id( (string) $sub_id, $parsed_conditions ) );
	}

	/**
	 * @test
	 * @group wpmlcore-5647
	 */
	public function it_should_translate_location_condition_sub_id_as_child_of() {
		$sub_id           = mt_rand( 1, 10 );
		$translate_id     = mt_rand( 11, 20 );
		$post_type        = 'hotel';
		$parsed_conditions = array( 'sub_name' => 'child_of' );

		\WP_Mock::userFunction( 'get_post_type',
			array(
				'args'   => $sub_id,
				'return' => $post_type
			)
		);

		\WP_Mock::onFilter( 'wpml_object_id' )
		        ->with( $sub_id, $post_type, true )
		        ->reply( $translate_id );

		$subject = new WPML_Elementor_Translate_IDs( \Mockery::mock( '\WPML\Utils\DebugBackTrace' ) );

		$this->assertEquals( $translate_id, $subject->translate_location_condition_sub_id( (string) $sub_id, $parsed_conditions ) );
	}

	/**
	 * @test
	 * @group wpmlcore-5647
	 */
	public function it_should_translate_location_condition_sub_id_in_taxonomy_term() {
		$sub_id           = mt_rand( 1, 10 );
		$translate_id     = mt_rand( 11, 20 );
		$taxonomy         = 'city';
		$parsed_conditions = array( 'sub_name' => 'in_' . $taxonomy );

		\WP_Mock::onFilter( 'wpml_object_id' )
		        ->with( $sub_id, $taxonomy, true )
		        ->reply( $translate_id );

		$subject = new WPML_Elementor_Translate_IDs( \Mockery::mock( '\WPML\Utils\DebugBackTrace' ) );

		$this->assertEquals( $translate_id, $subject->translate_location_condition_sub_id( (string) $sub_id, $parsed_conditions ) );
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

		$debug_backtrace = \Mockery::mock( '\WPML\Utils\DebugBackTrace' );
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

		$debug_backtrace = \Mockery::mock( '\WPML\Utils\DebugBackTrace' );
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

		$debug_backtrace = \Mockery::mock( '\WPML\Utils\DebugBackTrace' );
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

		$debug_backtrace = \Mockery::mock( '\WPML\Utils\DebugBackTrace' );
		$debug_backtrace->shouldReceive( 'is_class_function_in_call_stack' )
		                ->andReturn( false );

		$subject = new WPML_Elementor_Translate_IDs( $debug_backtrace );

		$this->assertEquals( $template_id, $subject->translate_template_id( $template_id ) );
	}

	/**
	 * @test
	 * @dataProvider dp_global_widget
	 * @group wpmlpb-153
	 *
	 * @param array $data
	 * @param array $expected_data
	 * @param int   $original_template_id
	 * @param int   $translated_template_id
	 */
	public function it_should_translate_global_widget_ids( $data, $expected_data, $original_template_id, $translated_template_id ) {
		$post_type = 'anything';

		\WP_Mock::userFunction( 'get_post_type', array(
			'args'   => $original_template_id,
			'return' => $post_type,
		));

		\WP_Mock::onFilter( 'wpml_object_id' )
		        ->with( (string) $original_template_id, $post_type, true )
		        ->reply( $translated_template_id );

		$debug_backtrace = \Mockery::mock( '\WPML\Utils\DebugBackTrace' );

		$subject = new WPML_Elementor_Translate_IDs( $debug_backtrace );

		$this->assertEquals(
			$expected_data,
			$subject->translate_global_widget_ids( $data, mt_rand( 1001, 2000 ) )
		);
	}

	public function dp_global_widget() {
		$original_template_id   = mt_rand( 1, 100 );
		$translated_template_id = mt_rand( 101, 200 );

		$data = array(
			array(
				'id' => '9246df0',
				'elType' => 'section',
				'settings' => array(),
				'elements' => array(
					array(
						'id' => '9262c46',
						'elType' => 'column',
						'settings' => array(
							'_column_size' => 100,
						),
						'elements' => array(
							array(
								'id' => 'ea1182b',
								'elType' => 'widget',
								'elements' => array(),
							),
						),
						'isInner' => false,
					),
				),
				'isInner' => false,
			),
		);

		$data_global = $data;
		$data_global[0]['elements'][0]['elements'][0]['widgetType'] = 'global';
		$data_global[0]['elements'][0]['elements'][0]['templateID'] = (string) $original_template_id;
		$expected_data_global = $data_global;
		$expected_data_global[0]['elements'][0]['elements'][0]['templateID'] = $translated_template_id;

		$data_template = $data;
		$data_template[0]['elements'][0]['elements'][0]['widgetType'] = 'template';
		$data_template[0]['elements'][0]['elements'][0]['settings']['template_id'] = (string) $original_template_id;
		$expected_data_template = $data_template;
		$expected_data_template[0]['elements'][0]['elements'][0]['settings']['template_id'] = $translated_template_id;

		return array(
			'global widget templateID' => array(
				$data_global,
				$expected_data_global,
				$original_template_id,
				$translated_template_id,

			),
			'template widget template_id' => array(
				$data_template,
				$expected_data_template,
				$original_template_id,
				$translated_template_id,
			),
		);
	}

	/**
	 * @test
	 * @group wpmlcore-7559
	 */
	public function it_should_translate_location_condition_any_child_of() {
		$sub_id            = 123;
		$translate_id      = 456;
		$post_type         = 'page';
		$parsed_conditions = [ 'sub_name' => 'any_child_of' ];

		\WP_Mock::userFunction( 'get_post_type', [ 'args' => $sub_id, 'return' => $post_type ] );

		\WP_Mock::onFilter( 'wpml_object_id' )
		        ->with( $sub_id, $post_type, true )
		        ->reply( $translate_id );

		$subject = new WPML_Elementor_Translate_IDs( \Mockery::mock( '\WPML\Utils\DebugBackTrace' ) );

		$this->assertEquals( $translate_id,
			$subject->translate_location_condition_sub_id( (string) $sub_id, $parsed_conditions ) );
	}
}
