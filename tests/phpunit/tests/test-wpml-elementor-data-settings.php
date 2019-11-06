<?php

/**
 * Class Test_WPML_Elementor_Data_Settings
 *
 * @group page-builders
 * @group elementor
 */
class Test_WPML_Elementor_Data_Settings extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_add_hooks() {
		$subject = new WPML_Elementor_Data_Settings();
		\WP_Mock::expectFilterAdded( 'wpml_custom_field_values_for_post_signature', array( $subject, 'add_data_custom_field_to_md5' ), 10, 2 );
		\WP_Mock::expectFilterAdded( 'wpml_pb_copy_meta_field', array( $subject, 'mark_css_field_as_empty' ), 10, 4 );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function it_add_hook_which_save_plain_text() {
		$elementor_db = $this->getMockBuilder( 'WPML_Elementor_DB' )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$subject = new WPML_Elementor_Data_Settings( $elementor_db );
		\WP_Mock::expectActionAdded( 'wpml_page_builder_string_translated', array( $subject, 'save_post_body_as_plain_text' ), 11, 5 );
		$subject->add_hooks();
	}

	/**
	 * @test
	 *
	 * @group wpmlcore-6319
	 */
	public function it_marks_css_field_as_empty_when_status_is_in_another_array_level() {
		$subject = new WPML_Elementor_Data_Settings();

		$value         = array( array( 'status' => 'something' ) );
		$changed_value = array( array( 'status' => '' ) );

		$this->assertEquals( $changed_value, $subject->mark_css_field_as_empty( $value, null, null, '_elementor_css' ) );
	}

	/**
	 * @test
	 *
	 * @group wpmlcore-6319
	 */
	public function it_marks_css_field_as_empty_when_status_is_in_the_single_level() {
		$subject = new WPML_Elementor_Data_Settings();

		$value         = array( 'status' => 'something' );
		$changed_value = array( 'status' => '' );

		$this->assertEquals( $changed_value, $subject->mark_css_field_as_empty( $value, null, null, '_elementor_css' ) );
	}

	/**
	 * @test
	 *
	 * @group wpmlcore-6319
	 */
	public function it_does_not_mark_css_field_as_empty() {
		$subject = new WPML_Elementor_Data_Settings();
		$value = rand_str( 10 );
		$this->assertEquals( $value, $subject->mark_css_field_as_empty( $value, null, null, rand_str( 10 ) ) );
	}

	/**
	 * @test
	 * @group wpmlcore-6400
	 */
	public function it_does_not_mark_css_field_as_empty_when_value_is_not_array() {
		$subject = new WPML_Elementor_Data_Settings();
		$value = rand_str( 10 );
		$this->assertEquals( $value, $subject->mark_css_field_as_empty( $value, null, null, '_elementor_css' ) );
	}

	/**
	 * @test
	 */
	public function it_gets_meta_field() {
		$subject = new WPML_Elementor_Data_Settings();
		$this->assertEquals( '_elementor_data', $subject->get_meta_field() );
	}

	/**
	 * @test
	 */
	public function it_gets_node_id_field() {
		$subject = new WPML_Elementor_Data_Settings();
		$this->assertEquals( 'id', $subject->get_node_id_field() );
	}

	/**
	 * @test
	 */
	public function it_gets_field_to_copy() {
		$fields_to_copy = array( '_elementor_version', '_elementor_edit_mode', '_elementor_css', '_elementor_template_type', '_elementor_template_widget_type' );

		$subject = new WPML_Elementor_Data_Settings();
		$this->assertEquals( $fields_to_copy, $subject->get_fields_to_copy() );
	}

	/**
	 * @test
	 */
	public function it_converts_data_to_array() {
		$data = array(
			'id' => mt_rand(),
			'something' => rand_str( 10 ),
		);

		$subject = new WPML_Elementor_Data_Settings();
		$this->assertEquals( $data, $subject->convert_data_to_array( array( json_encode( $data ) ) ) );
	}

	/**
	 * @test
	 */
	public function it_prepares_data_for_saving() {
		$data = array(
			'id' => mt_rand(),
			'something' => rand_str( 10 ),
		);

		\WP_Mock::wpFunction( 'wp_json_encode', array(
			'args'   => array( $data ),
			'return' => json_encode( $data ),
		) );

		\WP_Mock::wpFunction( 'wp_slash', array(
			'times' => 1,
			'return' => json_encode( $data ),
		) );

		$subject = new WPML_Elementor_Data_Settings();
		$this->assertEquals( json_encode( $data ), $subject->prepare_data_for_saving( $data ) );
	}

	/**
	 * @test
	 */
	public function it_gets_pb_name() {
		$subject = new WPML_Elementor_Data_Settings();
		$this->assertEquals( 'Elementor', $subject->get_pb_name() );
	}

	/**
	 * @test
	 */
	public function it_gets_field_to_save() {
		$fields_to_copy = array( '_elementor_data' );

		$subject = new WPML_Elementor_Data_Settings();
		$this->assertEquals( $fields_to_copy, $subject->get_fields_to_save() );
	}

	/**
	 * @test
	 */
	public function it_adds_custom_field() {
		$subject = new WPML_Elementor_Data_Settings();

		$custom_field_values = array( 'cf1', 'cf2' );
		$pb_cf_value = rand_str( 10 );
		$post_id = mt_rand();

		\WP_Mock::wpFunction( 'get_post_meta', array(
			'args'   => array( $post_id, '_elementor_data', true ),
			'return' => $pb_cf_value,
		) );

		$expected = $custom_field_values;
		$expected[] = $pb_cf_value;

		$this->assertEquals( $expected, $subject->add_data_custom_field_to_md5( $custom_field_values, $post_id ) );
	}

	/**
	 * @test
	 * @group wpmlcore-6929
	 */
	public function it_saves_post_body_as_plain_text_when_elementor_page() {
		$post_id = 123;

		$elementor_db = \Mockery::mock( 'WPML_Elementor_DB' );
		$elementor_db->shouldReceive( 'save_plain_text' )->once()->with( $post_id );

		\WP_Mock::userFunction( 'get_post_meta', [
			'args'   => [ $post_id, '_elementor_data', true ],
			'return' => [ 'some data' ],
		] );

		\WP_Mock::userFunction( 'get_post_meta', [
			'args'   => [ $post_id, '_elementor_edit_mode', true ],
			'return' => 'builder',
		] );

		$subject = new WPML_Elementor_Data_Settings( $elementor_db );
		$subject->save_post_body_as_plain_text( '', $post_id, '', '', '' );
	}

	/**
	 * @test
	 * @dataProvider dp_does_not_save_post_body_as_plain_text_when_elementor_page
	 * @group wpmlcore-6929
	 *
	 * @param mixed $elementor_data
	 * @param mixed $edit_mode
	 */
	public function it_does_not_saves_post_body_as_plain_text_when_not_an_elementor_page( $elementor_data, $edit_mode ) {
		$post_id = 123;

		$elementor_db = \Mockery::mock( 'WPML_Elementor_DB' );
		$elementor_db->shouldNotReceive( 'save_plain_text' );

		\WP_Mock::userFunction( 'get_post_meta', [
			'args'   => [ $post_id, '_elementor_data', true ],
			'return' => $elementor_data,
		] );

		\WP_Mock::userFunction( 'get_post_meta', [
			'args'   => [ $post_id, '_elementor_edit_mode', true ],
			'return' => $edit_mode,
		] );

		$subject = new WPML_Elementor_Data_Settings( $elementor_db );
		$subject->save_post_body_as_plain_text( '', $post_id, '', '', '' );
	}

	public function dp_does_not_save_post_body_as_plain_text_when_elementor_page() {
		return [
			'no elementor data'          => [ '', 'builder' ],
			'not using elementor editor' => [ [ 'some elementor data' ], 'another editor' ],
		];
	}

	/**
	 * @test
	 * @dataProvider dpShouldReturnIsHandlingPost
	 *
	 * @group wpmlcore-6929
	 *
	 * @param mixed $elementorData
	 * @param mixed $elementorEditMode
	 * @param bool  $expectedResult
	 */
	public function itShouldReturnIsHandlingPost( $elementorData, $elementorEditMode, $expectedResult ) {
		$postId = 123;

		\WP_Mock::userFunction( 'get_post_meta', [
			'args'   => [ $postId, '_elementor_data', true ],
			'return' => $elementorData,
		] );

		\WP_Mock::userFunction( 'get_post_meta', [
			'args'   => [ $postId, '_elementor_edit_mode', true ],
			'return' => $elementorEditMode,
		] );

		$subject = new WPML_Elementor_Data_Settings();

		$this->assertSame( $expectedResult, $subject->is_handling_post( $postId ) );
	}

	public function dpShouldReturnIsHandlingPost() {
		return [
			'with elementor data and elementor editor'    => [ 'some data', 'builder', true ],
			'with elementor data and no editor'           => [ 'some data', '', false ],
			'with elementor data and unknown editor'      => [ 'some data', 'foo', false ],
			'with no elementor data and elementor editor' => [ '', 'editor', false ],
		];
	}
}