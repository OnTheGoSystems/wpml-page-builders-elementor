<?php

/**
 * Class Test_WPML_Elementor_Update_Translations
 * @group page-builders
 * @group elementor
 * @group update-translations
 */
class Test_WPML_Elementor_Update_Translations extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_updates_editor_field() {

		\WP_Mock::wpPassthruFunction( '__' );

		$node_id = mt_rand();
		$translated_post_id = mt_rand();
		$original_post_id = mt_rand();
		$original_post = (object) array( 'ID' => $original_post_id );
		$lang = 'en';
		$translation = 'translation-value';
		$translation_after_wpautop = 'translation-after-wpautop-value';
		$translated_editor_element = 'translated-editor-field-value';
		$string_translations = array(
			'editor-text-editor-' . $node_id => array(
				$lang => array(
					'status' => 10,
					'value' => $translation
				)
			)
		);

		$element = array(
			'id' => $node_id,
			'widgetType' => 'text-editor',
			'elType' => 'widget',
			'settings' => array(
				'editor' => 'editor-field-value',
			),
			'elements' => array(),
		);

		$translated_element = array(
			'id' => $node_id,
			'widgetType' => 'text-editor',
			'elType' => 'widget',
			'text' => 'translated-' . $translation,
			'settings' => array(
				'editor' => $translated_editor_element,
			),
			'elements' => array(),
		);

		$elementor_data = array(
			array(
				'id' => $node_id,
				'elements' => array(
					array(
						'id' => $node_id,
						'elements' => array( $element ),
					),
				),
			),
		);

		$elementor_translated_data = array(
			array(
				'id' => $node_id,
				'elements' => array(
					array(
						'id' => $node_id,
						'elements' => array( $translated_element ),
					),
				),
			),
		);

		\WP_Mock::wpFunction( 'get_post_meta', array(
			'times'  => 1,
			'args'   => array( $original_post_id, '_elementor_data', true ),
			'return' => $elementor_data,
		) );

		\WP_Mock::wpFunction( 'update_post_meta', array(
			'times' => 1,
			'args'  => array( $translated_post_id, '_elementor_data', $elementor_translated_data ),
		) );

		$this->add_copy_meta_fields_checks( $translated_post_id, $original_post_id );

		$translatable_nodes_mock = $this->getMockBuilder( 'WPML_Elementor_Translatable_Nodes' )
		                                ->setMethods( array( 'update' ) )
		                                ->getMock();
		$translatable_nodes_mock->expects( $this->once() )
		                        ->method( 'update' )
		                        ->with( $node_id, $element )
		                        ->willReturn( $translated_element );

		$data_settings = $this->getMockBuilder( 'WPML_Elementor_Data_Settings' )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$data_settings->method( 'get_meta_field' )
		              ->willReturn( '_elementor_data' );

		$data_settings->method( 'get_node_id_field' )
		              ->willReturn( 'id' );

		$data_settings->method( 'get_fields_to_copy' )
		              ->willReturn( array( '_elementor_version', '_elementor_edit_mode', '_elementor_css' ) );

		$data_settings->method( 'convert_data_to_array' )
		              ->with( $elementor_data )
		              ->willReturn( $elementor_data );

		$data_settings->method( 'prepare_data_for_saving' )
		              ->with( $elementor_translated_data )
		              ->willReturn( $elementor_translated_data );

		$data_settings->method( 'get_fields_to_save' )
		              ->willReturn( array( '_elementor_data' ) );

		\WP_Mock::wpFunction( 'wpautop', array(
			'times'  => 1,
			'args'   => $translation,
			'return' => $translation_after_wpautop,
		) );

		$subject = new WPML_Elementor_Update_Translation( $translatable_nodes_mock, $data_settings );
		$subject->update( $translated_post_id, $original_post, $string_translations, $lang );
	}

	/**
	 * @test
	 */
	public function it_updates_non_editor_field() {

		\WP_Mock::wpPassthruFunction( '__' );

		$node_id = mt_rand();
		$translated_post_id = mt_rand();
		$original_post_id = mt_rand();
		$original_post = (object) array( 'ID' => $original_post_id );
		$lang = 'en';
		$translation = 'translation-value';
		$string_translations = array(
			'heading-title-' . $node_id => array(
				$lang => array(
					'status' => 10,
					'value' => $translation
				)
			)
		);

		$element = array(
			'id' => $node_id,
			'widgetType' => 'heading',
			'elType' => 'widget',
			'settings' => array(
				'title' => 'title-value',
			),
			'elements' => array(),
		);

		$translated_element = array(
			'id' => $node_id,
			'widgetType' => 'heading',
			'elType' => 'widget',
			'text' => 'translated-' . $translation,
			'settings' => array(
				'title' => 'title-translated-field-value',
			),
			'elements' => array(),
		);

		$elementor_data = array(
			array(
				'id' => $node_id,
				'elements' => array(
					array(
						'id' => $node_id,
						'elements' => array( $element ),
					),
				),
			),
		);

		$elementor_translated_data = array(
			array(
				'id' => $node_id,
				'elements' => array(
					array(
						'id' => $node_id,
						'elements' => array( $translated_element ),
					),
				),
			),
		);

		\WP_Mock::wpFunction( 'get_post_meta', array(
			'times'  => 1,
			'args'   => array( $original_post_id, '_elementor_data', true ),
			'return' => $elementor_data,
		) );

		\WP_Mock::wpFunction( 'update_post_meta', array(
			'times' => 1,
			'args'  => array( $translated_post_id, '_elementor_data', $elementor_translated_data ),
		) );

		$this->add_copy_meta_fields_checks( $translated_post_id, $original_post_id );

		$translatable_nodes_mock = $this->getMockBuilder( 'WPML_Elementor_Translatable_Nodes' )
		                                ->setMethods( array( 'update' ) )
		                                ->getMock();
		$translatable_nodes_mock->expects( $this->once() )
		                        ->method( 'update' )
		                        ->with( $node_id, $element )
		                        ->willReturn( $translated_element );

		$data_settings = $this->getMockBuilder( 'WPML_Elementor_Data_Settings' )
		                      ->disableOriginalConstructor()
		                      ->getMock();

		$data_settings->method( 'get_meta_field' )
		              ->willReturn( '_elementor_data' );

		$data_settings->method( 'get_node_id_field' )
		              ->willReturn( 'id' );

		$data_settings->method( 'get_fields_to_copy' )
		              ->willReturn( array( '_elementor_version', '_elementor_edit_mode', '_elementor_css' ) );

		$data_settings->method( 'convert_data_to_array' )
		              ->with( $elementor_data )
		              ->willReturn( $elementor_data );

		$data_settings->method( 'prepare_data_for_saving' )
		              ->with( $elementor_translated_data )
		              ->willReturn( $elementor_translated_data );

		$data_settings->method( 'get_fields_to_save' )
		              ->willReturn( array( '_elementor_data' ) );

		$subject = new WPML_Elementor_Update_Translation( $translatable_nodes_mock, $data_settings );
		$subject->update( $translated_post_id, $original_post, $string_translations, $lang );
	}

	private function add_copy_meta_fields_checks( $translated_post_id, $original_post_id ) {
		foreach( array( '_elementor_version', '_elementor_edit_mode', '_elementor_css' ) as $meta_key ) {
			$value = rand_str();
			\WP_Mock::wpFunction( 'get_post_meta', array(
				'times'  => 1,
				'args'   => array( $original_post_id, $meta_key, true ),
				'return' => $value,
			) );
			\WP_Mock::wpFunction( 'update_post_meta', array(
				'times' => 1,
				'args'  => array( $translated_post_id, $meta_key, $value ),
			) );
			\WP_Mock::onFilter( 'wpml_pb_copy_meta_field' )
			        ->with(
				        array(
					        $value, $translated_post_id, $original_post_id, $meta_key
				        )
			        )
			        ->reply( $value );
		}

	}
}