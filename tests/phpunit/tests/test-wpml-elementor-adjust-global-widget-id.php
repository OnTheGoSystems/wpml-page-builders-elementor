<?php

use WPML\Collect\Support\Collection;

/**
 * Class Test_WPML_Elementor_Adjust_Global_Widget_ID
 */
class Test_WPML_Elementor_Adjust_Global_Widget_ID extends OTGS_TestCase {

	public function tearDown() {
		unset( $_GET );
		parent::tearDown();
	}

	/**
	 * @test
	 * @group wpmlcore-5793
	 */
	public function it_adds_hooks_on_admin() {
		\WP_Mock::userFunction( 'is_admin' , array(
			'return' => true,
		));

		$subject = new WPML_Elementor_Adjust_Global_Widget_ID(
			\Mockery::mock( 'IWPML_Page_Builders_Data_Settings' ),
			\Mockery::mock( 'WPML_Translation_Element_Factory' ),
			\Mockery::mock( 'SitePress' )
		);

		$this->expectActionAdded( 'elementor/editor/before_enqueue_scripts', array(
			$subject,
			'adjust_ids'
		), 10 );
		$this->expectActionAdded( 'elementor/editor/after_enqueue_scripts', array(
			$subject,
			'restore_current_language'
		), 10 );
		$this->expectActionAdded( 'elementor/frontend/the_content', array(
			$subject,
			'duplicate_css_class_with_original_id'
		), 10 );

		$this->expectFilterAdded( 'wpml_should_use_display_as_translated_snippet', array(
			$subject, 'should_use_display_as_translated_snippet'
		), PHP_INT_MAX, 2 );

		$subject->add_hooks();
	}

	/**
	 * @test
	 * @group wpmlcore-5793
	 */
	public function it_adds_hooks_on_frontend() {
		\WP_Mock::userFunction( 'is_admin' , array(
			'return' => false,
		));

		$subject = new WPML_Elementor_Adjust_Global_Widget_ID(
			\Mockery::mock( 'IWPML_Page_Builders_Data_Settings' ),
			\Mockery::mock( 'WPML_Translation_Element_Factory' ),
			\Mockery::mock( 'SitePress' )
		);

		$this->expectActionAdded( 'elementor/editor/before_enqueue_scripts', array(
			$subject,
			'adjust_ids'
		), 10 );
		$this->expectActionAdded( 'elementor/editor/after_enqueue_scripts', array(
			$subject,
			'restore_current_language'
		), 10 );
		$this->expectActionAdded( 'elementor/frontend/the_content', array(
			$subject,
			'duplicate_css_class_with_original_id'
		), 10 );

		$this->expectFilterAdded( 'wpml_should_use_display_as_translated_snippet', array(
			$subject, 'should_use_display_as_translated_snippet'
		), PHP_INT_MAX, 2, 0 );

		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function test_adjust_ids() {

		$post_id       = 12;
		$post_language = 'fr';
		\WP_Mock::passthruFunction( 'absint' );

		$global_id            = '20';
		$global_id_translated = '21';

		$elementor_data = array(
			array(
				'elType'     => 'widget',
				'widgetType' => 'global',
				'templateID' => $global_id,
				'elements'   => array(),
			)
		);

		$converted_data = array(
			array(
				'elType'     => 'widget',
				'widgetType' => 'global',
				'templateID' => $global_id_translated,
				'elements'   => array(),
			)
		);

		$settings = \Mockery::mock( 'IWPML_Page_Builders_Data_Settings' );
		$settings->shouldReceive( 'get_meta_field' )->andReturn( '_elementor_data' );
		$settings->shouldReceive( 'convert_data_to_array' )->with( 'post meta' )->andReturn( $elementor_data );
		$settings->shouldReceive( 'prepare_data_for_saving' )->with( $converted_data )->andReturn( $converted_data );

		$element = \Mockery::mock( 'WPML_Post_Element' );
		$element->shouldReceive( 'get_language_code' )->andReturn( $post_language );

		$global_element_translated = \Mockery::mock( 'WPML_Post_Element' );
		$global_element_translated->shouldReceive( 'get_element_id' )->andReturn( $global_id_translated );

		$global_element = \Mockery::mock( 'WPML_Post_Element' );
		$global_element->shouldReceive( 'get_language_code' )->andReturn( 'en' );
		$global_element->shouldReceive( 'get_translation' )->with( $post_language )->andReturn( $global_element_translated );

		$element_factory = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$element_factory->shouldReceive( 'create_post' )->with( $post_id )->andReturn( $element );
		$element_factory->shouldReceive( 'create_post' )->with( $global_id )->andReturn( $global_element );

		\WP_Mock::userFunction( 'get_post_meta', array(
			'args'   => array( $post_id, '_elementor_data', true ),
			'return' => 'post meta'
		) );

		$sitepress = \Mockery::mock( 'SitePress' );
		$sitepress->shouldReceive( 'get_current_language' )->andReturn( 'en' );
		$sitepress->shouldReceive( 'switch_lang' )->once()->with( 'fr' );

		$subject = new WPML_Elementor_Adjust_Global_Widget_ID(
			$settings,
			$element_factory,
			$sitepress
		);

		$_REQUEST['post'] = $post_id;

		$this->expect_post_is_updated_correctly( $post_id, $converted_data );

		$subject->adjust_ids();

		$sitepress->shouldReceive( 'switch_lang' )->once()->with( 'en' );
		$subject->restore_current_language();

		unset( $_REQUEST['post'] );
	}

	/**
	 * @test
	 */
	public function it_does_not_adjust_ids_when_custom_field_is_empty() {

		$post_id       = 12;
		$post_language = 'fr';
		\WP_Mock::passthruFunction( 'absint' );

		$global_id            = '20';
		$global_id_translated = '21';

		$elementor_data = array(
			array(
				'elType'     => 'widget',
				'widgetType' => 'global',
				'templateID' => $global_id,
				'elements'   => array(),
			)
		);

		$converted_data = array(
			array(
				'elType'     => 'widget',
				'widgetType' => 'global',
				'templateID' => $global_id_translated,
				'elements'   => array(),
			)
		);

		$settings = \Mockery::mock( 'IWPML_Page_Builders_Data_Settings' );
		$settings->shouldReceive( 'get_meta_field' )->andReturn( '_elementor_data' );
		$settings->shouldReceive( 'convert_data_to_array' )->with( 'post meta' )->andReturn( $elementor_data );
		$settings->shouldReceive( 'prepare_data_for_saving' )->with( $converted_data )->andReturn( $converted_data );

		$element = \Mockery::mock( 'WPML_Post_Element' );
		$element->shouldReceive( 'get_language_code' )->andReturn( $post_language );

		$global_element_translated = \Mockery::mock( 'WPML_Post_Element' );
		$global_element_translated->shouldReceive( 'get_element_id' )->andReturn( $global_id_translated );

		$global_element = \Mockery::mock( 'WPML_Post_Element' );
		$global_element->shouldReceive( 'get_language_code' )->andReturn( 'en' );
		$global_element->shouldReceive( 'get_translation' )->with( $post_language )->andReturn( $global_element_translated );

		$element_factory = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$element_factory->shouldReceive( 'create_post' )->with( $post_id )->andReturn( $element );
		$element_factory->shouldReceive( 'create_post' )->with( $global_id )->andReturn( $global_element );

		\WP_Mock::userFunction( 'get_post_meta', array(
			'args'   => array( $post_id, '_elementor_data', true ),
			'return' => array(),
		) );

		$sitepress = \Mockery::mock( 'SitePress' );
		$sitepress->shouldReceive( 'get_current_language' )->andReturn( 'en' );
		$sitepress->shouldReceive( 'switch_lang' )->once()->with( 'fr' );

		$subject = new WPML_Elementor_Adjust_Global_Widget_ID(
			$settings,
			$element_factory,
			$sitepress
		);

		$_REQUEST['post'] = $post_id;

		\WP_Mock::userFunction( 'update_post_meta', array(
			'times' => 0,
		) );

		$subject->adjust_ids();

		unset( $_REQUEST['post'] );
	}

	private function expect_post_is_updated_correctly( $post_id, $converted_data ) {
		\WP_Mock::userFunction( 'get_post', array( 'return' => array() ) );
		\WP_Mock::userFunction( 'current_time', array( 'return' => 'time' ) );

		\WP_Mock::userFunction( 'update_post_meta', array(
			'times' => 1,
			'args'  => array( $post_id, '_elementor_data', $converted_data )
		) );
		\WP_Mock::userFunction( 'wp_update_post', array(
			'times' => 1,
			'args'  => array( array( 'post_date' => 'time', 'post_date_gmt' => '' ) )
		) );
	}

	/**
	 * @test
	 * @dataProvider dp_not_use_display_as_translated_snippet
	 * @group wpmlcore-5793
	 *
	 * @param $_get
	 * @param $post_types
	 */
	public function it_should_not_alter_use_display_as_translated_snippet( $_get, $post_types ) {
		$_GET = $_get;

		$settings        = \Mockery::mock( 'IWPML_Page_Builders_Data_Settings' );
		$element_factory = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$sitepress       = \Mockery::mock( 'SitePress' );

		$subject = new WPML_Elementor_Adjust_Global_Widget_ID( $settings, $element_factory, $sitepress );

		$this->assertFalse( $subject->should_use_display_as_translated_snippet( false, $post_types ) );
		$this->assertTrue( $subject->should_use_display_as_translated_snippet( true, $post_types ) );
	}

	public function dp_not_use_display_as_translated_snippet() {
		return array(
			array( array(), array( 'elementor_library' => array() ) ),
			array( array( 'action' => 'something' ), array( 'elementor_library' => array() ) ),
			array( array( 'action' => 'elementor' ), array( 'something' => array() ) ),
		);
	}

	/**
	 * @test
	 * @group wpmlcore-5793
	 */
	public function it_should_force_to_use_display_as_translated_snippet() {
		$_GET = array( 'action' => 'elementor' );
		$post_types = array(
			'something'         => array(),
			'elementor_library'	=> array(),
		);

		$settings        = \Mockery::mock( 'IWPML_Page_Builders_Data_Settings' );
		$element_factory = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$sitepress       = \Mockery::mock( 'SitePress' );

		$subject = new WPML_Elementor_Adjust_Global_Widget_ID( $settings, $element_factory, $sitepress );

		$this->assertTrue( $subject->should_use_display_as_translated_snippet( false, $post_types ) );
	}

	/**
	 * @test
	 * @group wpmlcore-6006
	 * @group wpmlcore-6630
	 */
	public function it_should_replace_css_class_id_with_original() {
		$source_id_1     = 123;
		$translated_id_1 = 234;
		$source_id_2     = 345;
		$translated_id_2 = 456;

		$getContent = function( Collection $ids1, Collection $ids2 ) {
			$idsToClasses = function( Collection $ids, $classPrefix ) {
				return $ids->map( function ( $id ) use ( $classPrefix ) {
					return $classPrefix . $id;
				} )->implode( ' ' );
			};

			$classesElementorGlobal = $idsToClasses( $ids1, 'elementor-global-' );
			$classesElementor       = $idsToClasses( $ids2, 'elementor-' );

			return '<div class="wrapper">
						<div data-id="b7135e3" class="elementor-element elementor-element-b7135e3 elementor-widget elementor-widget-global ' . $classesElementorGlobal . ' elementor-widget-button" data-element_type="button.default">
							<span class="elementor-button-text">Click here (translated)</span>
						</div>
						<div data-id="fl48r65s" class=" ' . $classesElementor . ' ">
							<span class="elementor-button-text">Another text (translated)</span>
						</div>
					</div>';
		};

		$content          = $getContent( wpml_collect( [ $translated_id_1 ] ), wpml_collect( [ $translated_id_2 ] ) );
		$expected_content = $getContent( wpml_collect( [ $translated_id_1, $source_id_1 ] ), wpml_collect( [ $translated_id_2, $source_id_2 ] ) );

		$source_1 = \Mockery::mock( 'WPML_Post_Element' );
		$source_1->shouldReceive( 'get_id' )->andReturn( $source_id_1 );

		$source_2 = \Mockery::mock( 'WPML_Post_Element' );
		$source_2->shouldReceive( 'get_id' )->andReturn( $source_id_2 );

		$translation_1 = \Mockery::mock( 'WPML_Post_Element' );
		$translation_1->shouldReceive( 'get_source_element' )->andReturn( $source_1 );

		$translation_2 = \Mockery::mock( 'WPML_Post_Element' );
		$translation_2->shouldReceive( 'get_source_element' )->andReturn( $source_2 );

		$element_factory = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$element_factory->shouldReceive( 'create_post' )->with( $translated_id_1 )->andReturn( $translation_1 );
		$element_factory->shouldReceive( 'create_post' )->with( $translated_id_2 )->andReturn( $translation_2 );

		$settings        = \Mockery::mock( 'IWPML_Page_Builders_Data_Settings' );
		$sitepress       = \Mockery::mock( 'SitePress' );

		$subject = new WPML_Elementor_Adjust_Global_Widget_ID( $settings, $element_factory, $sitepress );

		$this->assertEquals(
			$expected_content,
			$subject->duplicate_css_class_with_original_id( $content )
		);
	}
}
