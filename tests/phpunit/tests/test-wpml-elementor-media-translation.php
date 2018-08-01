<?php

/**
 * Class Test_Elementor_Media_Translation
 *
 * @group wpmlmedia-511
 */
class Test_Elementor_Media_Translation extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		$data_settings               = $this->get_data_settings_mock();
		$image_translate             = $this->get_media_image_translate_mock();
		$translation_element_factory = $this->get_translation_element_factory_mock();

		$subject = new WPML_Elementor_Media_Translation( $data_settings, $image_translate, $translation_element_factory );
		\WP_Mock::expectActionAdded( 'wpml_media_after_translate_media_in_post_content', array( $subject, 'translate_image' ), PHP_INT_MAX, 3 );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function it_translates_image_when_page_is_translated() {
		$data_settings               = $this->get_data_settings_mock();
		$image_translate             = $this->get_media_image_translate_mock();
		$translation_element_factory = $this->get_translation_element_factory_mock();
		$source_language             = 'en';
		$target_language             = 'pt-br';
		$field                       = '_elementor_data';
		$translated_image_url        = 'http://dev.otgs/wp-content/uploads/2018/08/translation.jpg';
		$attachment_url              = 'http://dev.otgs/wp-content/uploads/2018/08/original.jpg';

		$data_settings->method( 'get_meta_field' )
		              ->willReturn( $field );

		$post_id            = 1;
		$post_translated_id = 2;

		$attachment_id            = 3;
		$attachment_translated_id = 4;

		$post = $this->getMockBuilder( 'WPML_Post_Element' )
		             ->setMethods( array( 'get_translation', 'get_language_code', 'get_element_id' ) )
		             ->disableOriginalConstructor()
		             ->getMock();

		$post->method( 'get_language_code' )
		     ->willReturn( $source_language );

		$translation = $this->getMockBuilder( 'WPML_Post_Element' )
		                    ->setMethods( array( 'get_translation', 'get_language_code', 'get_element_id' ) )
		                    ->disableOriginalConstructor()
		                    ->getMock();

		$translation->method( 'get_element_id' )
		            ->willReturn( $post_translated_id );

		$translation->method( 'get_language_code' )
		            ->willReturn( $target_language );

		$post->method( 'get_translation' )
		     ->with( $target_language )
		     ->willReturn( $translation );

		$attachment = $this->getMockBuilder( 'WPML_Post_Element' )
		                   ->setMethods( array( 'get_translation', 'get_language_code', 'get_element_id' ) )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$attachment_translation = $this->getMockBuilder( 'WPML_Post_Element' )
		                               ->setMethods( array( 'get_translation', 'get_language_code', 'get_element_id' ) )
		                               ->disableOriginalConstructor()
		                               ->getMock();

		$attachment_translation->method( 'get_element_id' )
		                       ->willReturn( $attachment_translated_id );

		$attachment->method( 'get_translation' )
		           ->with( $target_language )
		           ->willReturn( $attachment_translation );

		$translation_element_factory->method( 'create' )
		                            ->withConsecutive(
			                            array( $post_id, 'post' ),
			                            array( $attachment_id, 'post' )
		                            )
		                            ->willReturnOnConsecutiveCalls(
			                            $post,
			                            $attachment
		                            );

		$custom_field_data_json             = '[{"id":"9f0a7fc","elType":"section","settings":[],"elements":[{"id":"8afe163","elType":"column","settings":{"_column_size":100},"elements":[{"id":"bc0395a","elType":"widget","settings":{"image":{"url":"' . $attachment_url . '","id":' . $attachment_id . '}},"elements":[],"widgetType":"image"}],"isInner":false}],"isInner":false},{"id":"866a12f","elType":"section","settings":[],"elements":[{"id":"b41107f","elType":"column","settings":{"_column_size":100},"elements":[{"id":"01a0fdd","elType":"widget","settings":{"title":"Add Your Heading Text Here"},"elements":[],"widgetType":"heading"}],"isInner":false}],"isInner":false}]';
		$custom_field_data_array            = json_decode( $custom_field_data_json, true );
		$custom_field_data_json_translated  = '[{"id":"9f0a7fc","elType":"section","settings":[],"elements":[{"id":"8afe163","elType":"column","settings":{"_column_size":100},"elements":[{"id":"bc0395a","elType":"widget","settings":{"image":{"url":"' . $translated_image_url . '","id":' . $attachment_translated_id . '}},"elements":[],"widgetType":"image"}],"isInner":false}],"isInner":false},{"id":"866a12f","elType":"section","settings":[],"elements":[{"id":"b41107f","elType":"column","settings":{"_column_size":100},"elements":[{"id":"01a0fdd","elType":"widget","settings":{"title":"Add Your Heading Text Here"},"elements":[],"widgetType":"heading"}],"isInner":false}],"isInner":false}]';
		$custom_field_data_array_translated = json_decode( $custom_field_data_json_translated, true );

		$image_translate->method( 'get_translated_image_by_url' )
		                ->with( $attachment_url, $source_language, $target_language )
		                ->willReturn( $translated_image_url );

		\WP_Mock::userFunction( 'get_post_meta',
			array(
				'args'   => array( $post_translated_id, $field ),
				'return' => $custom_field_data_json,
			)
		);

		\WP_Mock::userFunction( 'wp_get_attachment_url',
			array(
				'args'   => $attachment_id,
				'return' => $attachment_url,
			)
		);

		$data_settings->method( 'convert_data_to_array' )
		              ->with( $custom_field_data_json )
		              ->willReturn( $custom_field_data_array );

		$data_settings->method( 'prepare_data_for_saving' )
		              ->with( $custom_field_data_array_translated )
		              ->willReturn( $custom_field_data_json_translated );

		\WP_Mock::userFunction( 'update_post_meta',
			array(
				'times'  => 1,
				'args'   => array( $post_translated_id, $field, $custom_field_data_json_translated ),
				'return' => $custom_field_data_json,
			)
		);

		$subject = new WPML_Elementor_Media_Translation( $data_settings, $image_translate, $translation_element_factory );
		$subject->translate_image( $post_id, $attachment_id, $target_language );
	}

	/**
	 * @test
	 */
	public function it_does_not_translate_image_when_page_is_not_translated() {
		$data_settings               = $this->get_data_settings_mock();
		$image_translate             = $this->get_media_image_translate_mock();
		$translation_element_factory = $this->get_translation_element_factory_mock();
		$source_language             = 'en';
		$target_language             = 'pt-br';

		$post_id = 1;

		$attachment_id            = 3;
		$attachment_translated_id = 4;

		$post = $this->getMockBuilder( 'WPML_Post_Element' )
		             ->setMethods( array( 'get_translation', 'get_language_code', 'get_element_id' ) )
		             ->disableOriginalConstructor()
		             ->getMock();

		$post->method( 'get_language_code' )
		     ->willReturn( $source_language );

		$translation = false;

		$post->method( 'get_translation' )
		     ->with( $target_language )
		     ->willReturn( $translation );

		$attachment = $this->getMockBuilder( 'WPML_Post_Element' )
		                   ->setMethods( array( 'get_translation', 'get_language_code', 'get_element_id' ) )
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$attachment_translation = $this->getMockBuilder( 'WPML_Post_Element' )
		                               ->setMethods( array( 'get_translation', 'get_language_code', 'get_element_id' ) )
		                               ->disableOriginalConstructor()
		                               ->getMock();

		$attachment_translation->method( 'get_element_id' )
		                       ->willReturn( $attachment_translated_id );

		$attachment->method( 'get_translation' )
		           ->with( $target_language )
		           ->willReturn( $attachment_translation );

		$translation_element_factory->method( 'create' )
		                            ->withConsecutive(
			                            array( $post_id, 'post' ),
			                            array( $attachment_id, 'post' )
		                            )
		                            ->willReturnOnConsecutiveCalls(
			                            $post,
			                            $attachment
		                            );

		\WP_Mock::userFunction( 'update_post_meta',
			array(
				'times' => 0,
			)
		);

		$subject = new WPML_Elementor_Media_Translation( $data_settings, $image_translate, $translation_element_factory );
		$subject->translate_image( $post_id, $attachment_id, $target_language );
	}

	private function get_data_settings_mock() {
		return $this->getMockBuilder( 'IWPML_Page_Builders_Data_Settings' )
		            ->setMethods( array(
			            'prepare_data_for_saving',
			            'get_meta_field',
			            'get_node_id_field',
			            'get_fields_to_copy',
			            'get_fields_to_save',
			            'convert_data_to_array',
			            'get_pb_name',
			            'add_hooks'
		            ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function get_media_image_translate_mock() {
		return $this->getMockBuilder( 'WPML_Media_Image_Translate' )
		            ->setMethods( array( 'get_translated_image_by_url' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}

	private function get_translation_element_factory_mock() {
		return $this->getMockBuilder( 'WPML_Translation_Element_Factory' )
		            ->setMethods( array( 'create' ) )
		            ->disableOriginalConstructor()
		            ->getMock();
	}
}