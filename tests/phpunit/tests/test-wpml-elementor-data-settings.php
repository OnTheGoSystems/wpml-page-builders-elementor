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

	public function it_marks_css_field_as_empty() {
		$subject = new WPML_Elementor_Data_Settings();
		$this->assertEquals( '', $subject->mark_css_field_as_empty( rand_str( 10 ), null, null, '_elementor_css' ) );
	}

	public function it_does_not_mark_css_field_as_empty() {
		$subject = new WPML_Elementor_Data_Settings();
		$value = rand_str( 10 );
		$this->assertEquals( $value, $subject->mark_css_field_as_empty( $value, null, null, rand_str( 10 ) ) );
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
		$fields_to_copy = array( '_elementor_version', '_elementor_edit_mode', '_elementor_css' );

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
}