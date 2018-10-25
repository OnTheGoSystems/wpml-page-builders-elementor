<?php

/**
 * Class Test_WPML_Elementor_Icon_List
 *
 * @group page-builders
 * @group elementor
 */
class Test_WPML_Elementor_Icon_List extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_get_fields() {

		$expected = array( 'text', 'link' => array( 'url' ) );
		$subject = new WPML_Elementor_Icon_List();
		$this->assertEquals( $expected, $subject->get_fields() );
	}

	/**
	 * @test
	 */
	public function it_get_items_field() {
		$subject = new WPML_Elementor_Icon_List();
		$this->assertEquals( 'icon_list', $subject->get_items_field() );
	}

	/**
	 * @test
	 * @group wpmlcore-5929
	 */
	public function it_should_get() {
		$node_id ='f94a4f8';
		$element = array(
			'id' => $node_id,
			'elType' => 'widget',
			'settings' => array(
				'icon_list' => array(
					array(
						'text' => 'List Item #1',
						'icon' => 'fa fa-check',
						'_id' => '38f8b31',
						'link' => array(
							'url' => 'http://wpml.local/sample-page/',
							'is_external' => '',
							'nofollow' => '',
						),
					),
				),
			),
			'elements' => array(),
			'widgetType' => 'icon-list',
		);

		$strings = array();

		$expected_text_string = new WPML_PB_String(
			'List Item #1',
			'icon-list-text-f94a4f8-38f8b31',
			'Icon List: Text',
			'LINE'
		);

		$expected_link_string = new WPML_PB_String(
			'http://wpml.local/sample-page/',
			'icon-list-url-f94a4f8-38f8b31',
			'Icon List: Link URL',
			'LINK'
		);

		$subject = new WPML_Elementor_Icon_List();

		$actual_strings = $subject->get( $node_id, $element, $strings );

		$this->assertEquals( $expected_text_string, $actual_strings[0] );
		$this->assertEquals( $expected_link_string, $actual_strings[1] );

	}
}