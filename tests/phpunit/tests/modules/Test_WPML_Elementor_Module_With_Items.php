<?php

/**
 * @group module-with-items
 */
class Test_WPML_Elementor_Module_With_Items extends OTGS_TestCase {

	const NODE_ID = 'a1b2c3d4';

	/**
	 * @test
	 */
	public function it_gets() {
		$strings = [
			new WPML_PB_String( 'something', 'some name', 'some title', 'LINE' ), // initial string
		];

		$fields = [
			[
				'_id'     => 'id_1',
				'field_A' => self::get_string_value( 'field_A', 'id_1' ),
				'field_B' => [ 'inner_field_B' => self::get_string_value( 'inner_field_B', 'id_1' ) ],
			],
			[
				'_id'            => 'id_2',
				'field_A'        => self::get_string_value( 'field_A', 'id_2' ),
				'not_translated' => 'not translated field',
			],
		];

		$element = self::get_element( $fields );

		$expected_strings = array_merge(
			$strings,
			[
				$this->get_string( 'field_A', 'id_1' ),
				$this->get_string( 'inner_field_B', 'id_1' ),
				$this->get_string( 'field_A', 'id_2' ),
			]
		);

		$subject = new WPML_Elementor_Module_With_Items_For_Test();

		$this->assertEquals( $expected_strings, $subject->get( self::NODE_ID, $element, $strings ) );
	}

	/**
	 * @test
	 */
	public function it_updates_a_field() {
		$translated_value = 'the translated value';

		$string = $this->get_string( 'field_A', 'id_2' );
		$string->set_value( $translated_value );

		$field_to_translate = [
			'_id'            => 'id_2',
			'field_A'        => 'something to translate',
			'not_translated' => 'not translated field',
		];

		$expected_translated_field            = $field_to_translate;
		$expected_translated_field['field_A'] = $translated_value;
		// We are modifying the field, and this 'index' will be used outside the class...
		$expected_translated_field['index']   = 1;

		$fields = [
			[
				'_id'     => 'id_1',
				'field_A' => 'some value for field_A - id_1',
				'field_B' => [ 'inner_field_B' => 'some value for inner_field_B - id_1' ],
			],
			$field_to_translate,
			[
				'_id'     => 'id_3',
				'field_A' => 'some value for field_A - id_3',
				'field_B' => [ 'inner_field_B' => 'some value for inner_field_B - id_3' ],
			],
		];

		$element = self::get_element( $fields );

		$subject = new WPML_Elementor_Module_With_Items_For_Test();

		$this->assertEquals(
			$expected_translated_field,
			$subject->update( self::NODE_ID, $element, $string )
		);
	}

	/**
	 * @test
	 */
	public function it_updates_a_inner_field() {
		$translated_value = 'the translated value';

		$string = $this->get_string( 'inner_field_B', 'id_2' );
		$string->set_value( $translated_value );

		$field_to_translate = [
			'_id'            => 'id_2',
			'field_A'        => 'something',
			'field_B'        => [
				'foo'           => 'bar',
				'inner_field_B' => 'TO BE TRANSLATED',
				'hello'         => 'there',
			],
		];

		$expected_translated_field                             = $field_to_translate;
		$expected_translated_field['field_B']['inner_field_B'] = $translated_value;
		// We are modifying the field, and this 'index' will be used outside the class...
		$expected_translated_field['index'] = 1;

		$fields = [
			[
				'_id'     => 'id_1',
				'field_A' => 'some value for field_A - id_1',
				'field_B' => [ 'inner_field_B' => 'some value for inner_field_B - id_1' ],
			],
			$field_to_translate,
			[
				'_id'     => 'id_3',
				'field_A' => 'some value for field_A - id_3',
				'field_B' => [ 'inner_field_B' => 'some value for inner_field_B - id_3' ],
			],
		];

		$element = self::get_element( $fields );

		$subject = new WPML_Elementor_Module_With_Items_For_Test();

		$this->assertEquals(
			$expected_translated_field,
			$subject->update( self::NODE_ID, $element, $string )
		);
	}

	public static function get_string_value( $name, $item_id ) {
		return "The value for $name - $item_id";
	}

	public static function get_field_prop( $field, $prop ) {
		return "The $prop for $field";
	}

	private static function get_string( $field, $item_id ) {
		return new WPML_PB_String(
			self::get_string_value( $field, $item_id ),
			WPML_Elementor_Module_With_Items_For_Test::WIDGET_TYPE . '-' . $field . '-' . Test_WPML_Elementor_Module_With_Items::NODE_ID . '-' . $item_id,
			Test_WPML_Elementor_Module_With_Items::get_field_prop( $field, 'title' ),
			Test_WPML_Elementor_Module_With_Items::get_field_prop( $field, 'type' )
		);
	}

	private static function get_element( array $fields ) {
		return [
			WPML_Elementor_Translatable_Nodes::TYPE           => WPML_Elementor_Module_With_Items_For_Test::WIDGET_TYPE,
			WPML_Elementor_Translatable_Nodes::SETTINGS_FIELD => [
				'test_field' => $fields,
			],
		];
	}
}

class WPML_Elementor_Module_With_Items_For_Test extends WPML_Elementor_Module_With_Items {

	const WIDGET_TYPE = 'nice-widget';

	public function get_fields() {
		return [
			'first_missing_field_in_widget',
			'field_A',
			'field_B' => [
				'first_missing_inner_field',
				'inner_field_B',
				'second_missing_inner_field',
			],
			'second_missing_field_in_widget',
		];
	}

	protected function get_title( $field ) {
		return Test_WPML_Elementor_Module_With_Items::get_field_prop( $field, 'title' );
	}

	protected function get_editor_type( $field ) {
		return Test_WPML_Elementor_Module_With_Items::get_field_prop( $field, 'type' );
	}

	public function get_items_field() {
		return 'test_field';
	}
}