<?php

/**
 * Class Test_WPML_Translatable_Nodes
 *
 * @group page-builders
 * @group elementor
 * @group translatable-nodes
 */
class Test_WPML_Elementor_Translatable_Nodes extends OTGS_TestCase {

	/**
	 * @test
	 * @dataProvider node_data_provider
	 *
	 * @param $type
	 * @param $fields
	 */
	public function it_gets( $type, $fields, $items_field, $items ) {
		$node_id = rand_str( 10 );
		$settings = array();

		foreach ( $fields as $key => $field ) {
			if ( is_numeric( $key ) ) {
				$settings[ $field['field'] ] = rand_str( 10 );
			} else {
				$settings[ $key ] = array(
					$field['field'] => rand_str( 10 ),
					'type' => $field['type'],
					'editor_type' => $field['editor_type'],
				);
			}
		}

		$sub_items = array();
		if ( $items ) {
			foreach ( $items as $item_key => $item ) {
				if ( ! is_numeric( $item_key ) ) {
					$sub_items[ $item_key ][ $item['field'] ] = $item['value'];
				} else {
					$sub_items[ $item['field'] ] = $item['value'];
				}
			}
		}

		$sub_items[ '_id' ] = mt_rand( 1, 10 );
		$settings[ $items_field ][] = $sub_items;

		$element = array(
			'id' => $node_id,
			'widgetType' => $type,
			'settings' => $settings,
		);

		\WP_Mock::wpPassthruFunction( '__' );
		\WP_Mock::wpPassthruFunction( 'esc_html__' );

		$subject = new WPML_Elementor_Translatable_Nodes();
		$strings = $subject->get( $node_id, $element );

		if ( $items ) {
			if ( $fields ) {
				if ( 'price-table' === $type ) {
					$this->assertCount( count( $fields ) + count( $items ), $strings );
				} else {
					$this->assertCount( count( $fields ), $strings );
				}
			} else {
				$this->assertCount( count( $items ), $strings );
			}
		}

		if ( $fields ) {
			if ( 'price-table' === $type && $items ) {
				$this->assertCount( count( $fields ) + count( $items ), $strings );
			} else {
				$this->assertCount( count( $fields ), $strings );
			}
		}

		foreach ( $fields as $key => $field ) {
			if ( is_numeric( $key ) ) {
				$string = $strings[ $key ];
				$this->assertEquals( $element['settings'][ $field['field'] ], $string->get_value() );
				$this->assertEquals( $field['field'] . '-' . $element[ 'widgetType'] . '-' . $node_id, $string->get_name() );
				$this->assertEquals( $field['type'], $string->get_title() );
				$this->assertEquals( $field['editor_type'], $string->get_editor_type() );
			} else {
				$string = $strings[0];
				if ( 'button' === $type ) {
					$string = $strings[1];
				}

				if ( 'image' === $type ) {
					$string = $strings[1];
				}

				if ( 'flip-box' === $type ) {
					$string = $strings[5];
				}

				if ( 'price-table' === $type ) {
					$string = $strings[6];
				}

				if ( 'icon-box' === $type ) {
					$string = $strings[2];
				}

				if ( 'call-to-action' === $type ) {
					$string = $strings[4];
				}

				$this->assertEquals( $element['settings'][ $key ][ $field['field'] ], $string->get_value() );
				$this->assertEquals( $field['field'] . '-' . $element[ 'widgetType'] . '-' . $node_id, $string->get_name() );
				$this->assertEquals( $field['type'], $string->get_title() );
				$this->assertEquals( $field['editor_type'], $string->get_editor_type() );
			}
		}

		if ( ! $fields ) {
			$key = 0;
			foreach ( $items as $item_key => $item ) {
				$string = $strings[ $key ];

				if ( is_numeric( $item_key ) ) {
					$this->assertEquals( $element['settings'][ $items_field ][0][ $item['field'] ], $string->get_value() );
					$this->assertEquals( $element['widgetType'] . '-' . $item['field'] . '-' . $node_id . '-' . $sub_items[ '_id' ], $string->get_name() );
				} else {
					$this->assertEquals( $element['settings'][ $items_field ][0][ $item_key ][ $item['field'] ], $string->get_value() );
				}
				$this->assertEquals( $item['type'], $string->get_title() );
				$this->assertEquals( $item['editor_type'], $string->get_editor_type() );
				$key++;
			}
		}
	}

	public function node_data_provider() {

		return array(
			'Heading' => array( 'heading', array(
				array( 'field' => 'title', 'type' => 'Heading', 'editor_type' => 'LINE' ) ),
				'',
				array(),
			),
			'Text editor' => array( 'text-editor', array(
				array( 'field' => 'editor', 'type' => 'Text editor', 'editor_type' => 'VISUAL' ) ),
				'',
				array(),
			),
			'HTML' => array( 'html', array(
				array( 'field' => 'html', 'type' => 'HTML', 'editor_type' => 'AREA' ) ),
				'',
				array(),
			),
			'Video' => array( 'video', array(
				array( 'field' => 'link', 'type' => 'Video: Link', 'editor_type' => 'LINE' ),
				array( 'field' => 'vimeo_link', 'type' => 'Video: Vimeo link', 'editor_type' => 'LINE' ) ),
				'',
				array(),
			),
			'Login' => array( 'login', array(
				array( 'field' => 'button_text', 'type' => 'Login: Button text', 'editor_type' => 'LINE' ),
				array( 'field' => 'user_label', 'type' => 'Login: User label', 'editor_type' => 'LINE' ),
				array( 'field' => 'user_placeholder', 'type' => 'Login: User placeholder', 'editor_type' => 'LINE' ),
				array( 'field' => 'password_label', 'type' => 'Login: Password label', 'editor_type' => 'LINE' ),
				array( 'field' => 'password_placeholder', 'type' => 'Login: Password placeholder', 'editor_type' => 'LINE' ) ),
				'',
				array(),
			),
			'Icon' => array( 'icon', array(
				'link' => array( 'field' => 'url', 'type' => 'Icon: Link URL', 'editor_type' => 'LINK' ) ),
				'',
				array(),
			),
			'Button' => array( 'button', array(
				array( 'field' => 'text', 'type' => 'Button', 'editor_type' => 'LINE' ),
				'link' => array( 'field' => 'url', 'type' => 'Button: Link URL', 'editor_type' => 'LINK' ) ),
				'',
				array(),
			),
			'Image' => array( 'image', array(
				array( 'field' => 'caption', 'type' => 'Image: Caption', 'editor_type' => 'LINE' ),
				'link' => array( 'field' => 'url', 'type' => 'Image: Link URL', 'editor_type' => 'LINK' ) ),
				'',
				array(),
			),
			'Alert' => array( 'alert', array(
				array( 'field' => 'alert_title', 'type' => 'Alert title', 'editor_type' => 'LINE' ),
				array( 'field' => 'alert_description', 'type' => 'Alert description', 'editor_type' => 'VISUAL' ) ),
				'',
				array(),
			),
			'Blockquote' => array( 'blockquote', array(
				array( 'field' => 'blockquote_content', 'type' => 'Blockquote: Content', 'editor_type' => 'VISUAL' ),
				array( 'field' => 'tweet_button_label', 'type' => 'Blockquote: Tweet button label', 'editor_type' => 'LINE' ) ),
				'',
				array(),
			),
			'Testimonial' => array( 'testimonial', array(
				array( 'field' => 'testimonial_content', 'type' => 'Testimonial content', 'editor_type' => 'VISUAL' ),
				array( 'field' => 'testimonial_name', 'type' => 'Testimonial name', 'editor_type' => 'LINE' ),
				array( 'field' => 'testimonial_job', 'type' => 'Testimonial job', 'editor_type' => 'LINE' ) ),
				'',
				array(),
			),
			'Progress' => array( 'progress', array(
				array( 'field' => 'title', 'type' => 'Progress: Title', 'editor_type' => 'LINE' ),
				array( 'field' => 'inner_text', 'type' => 'Progress: Inner text', 'editor_type' => 'LINE' ) ),
				'',
				array(),
			),
			'Counter' => array( 'counter', array(
				array( 'field' => 'starting_number', 'type' => 'Starting number', 'editor_type' => 'LINE' ),
				array( 'field' => 'title', 'type' => 'Title', 'editor_type' => 'LINE' ) ),
				'',
				array(),
			),
			'Countdown' => array( 'countdown', array(
				array( 'field' => 'label_days', 'type' => 'Countdown: Label days', 'editor_type' => 'LINE' ),
				array( 'field' => 'label_hours', 'type' => 'Countdown: Label hours', 'editor_type' => 'LINE' ),
				array( 'field' => 'label_minutes', 'type' => 'Countdown: Label minutes', 'editor_type' => 'LINE' ),
				array( 'field' => 'label_seconds', 'type' => 'Countdown: Label seconds', 'editor_type' => 'LINE' ) ),
				'',
				array(),
			),
			'Icon box' => array( 'icon-box', array(
				array( 'field' => 'title_text', 'type' => 'Icon Box: Title text', 'editor_type' => 'LINE' ),
				array( 'field' => 'description_text', 'type' => 'Icon Box: Description text', 'editor_type' => 'VISUAL' ),
				'link' => array( 'field' => 'url', 'type' => 'Icon Box: Link', 'editor_type' => 'LINK' ) ),
				'',
				array(),
			),
			'Image box' => array( 'image-box', array(
				array( 'field' => 'title_text', 'type' => 'Image Box: Title text', 'editor_type' => 'LINE' ),
				array( 'field' => 'description_text', 'type' => 'Image Box: Description text', 'editor_type' => 'LINE' ) ),
				'',
				array(),
			),
			'Animated Headline' => array( 'animated-headline', array(
				array( 'field' => 'before_text', 'type' => 'Animated Headline: Before text', 'editor_type' => 'LINE' ),
				array( 'field' => 'highlighted_text', 'type' => 'Animated Headline: Highlighted text', 'editor_type' => 'LINE' ),
				array( 'field' => 'rotating_text', 'type' => 'Animated Headline: Rotating text', 'editor_type' => 'AREA' ),
				array( 'field' => 'after_text', 'type' => 'Animated Headline: After text', 'editor_type' => 'LINE' ) ),
				'',
				array(),
			),
			'Flip box' => array( 'flip-box', array(
				array( 'field' => 'title_text_a', 'type' => 'Flip Box: Title text side A', 'editor_type' => 'LINE' ),
				array( 'field' => 'description_text_a', 'type' => 'Flip Box: Description text side A', 'editor_type' => 'VISUAL' ),
				array( 'field' => 'title_text_b', 'type' => 'Flip Box: Title text side B', 'editor_type' => 'LINE' ),
				array( 'field' => 'description_text_b', 'type' => 'Flip Box: Description text side B', 'editor_type' => 'VISUAL' ),
				array( 'field' => 'button_text', 'type' => 'Flip Box: Button text', 'editor_type' => 'LINE' ),
				'link' => array( 'field' => 'url', 'type' => 'Flip Box: Button link', 'editor_type' => 'LINK' ) ),
				'',
				array(),
			),
			'Call to action' => array( 'call-to-action', array(
				array( 'field' => 'title', 'type' => 'Call to action: title', 'editor_type' => 'LINE' ),
				array( 'field' => 'description', 'type' => 'Call to action: description', 'editor_type' => 'VISUAL' ),
				array( 'field' => 'button', 'type' => 'Call to action: button', 'editor_type' => 'LINE' ),
				array( 'field' => 'ribbon_title', 'type' => 'Call to action: ribbon title', 'editor_type' => 'LINE' ),
				'link' => array( 'field' => 'url', 'type' => 'Call to action: link', 'editor_type' => 'LINK' ) ),
				'',
				array(),
			),
			'Toggle' => array( 'toggle',
				array(),
				'tabs',
				array(
					array( 'field' => 'tab_title', 'value' => rand_str( 10 ), 'type' => 'Toggle: Title', 'editor_type' => 'LINE' ),
					array( 'field' => 'tab_content', 'value' => rand_str( 10 ), 'type' => 'Toggle: Content', 'editor_type' => 'VISUAL' ),
				),
			),
			'Accordion' => array( 'accordion',
				array(),
				'tabs',
				array(
					array( 'field' => 'tab_title', 'value' => rand_str( 10 ), 'type' => 'Accordion: Title', 'editor_type' => 'LINE' ),
					array( 'field' => 'tab_content', 'value' => rand_str( 10 ), 'type' => 'Accordion: Content', 'editor_type' => 'VISUAL' ),
				),
			),
			'Tabs' => array( 'tabs',
				array(),
				'tabs',
				array(
					array( 'field' => 'tab_title', 'value' => rand_str( 10 ), 'type' => 'Tabs: Title', 'editor_type' => 'LINE' ),
					array( 'field' => 'tab_content', 'value' => rand_str( 10 ), 'type' => 'Tabs: Content', 'editor_type' => 'VISUAL' ),
				),
			),
			'Price List' => array( 'price-list',
				array(),
				'price_list',
				array(
					array( 'field' => 'title', 'value' => rand_str( 10 ), 'type' => 'Price list: title', 'editor_type' => 'LINE' ),
					array( 'field' => 'item_description', 'value' => rand_str( 10 ), 'type' => 'Pricing list: description', 'editor_type' => 'VISUAL' ),
					'link' => array( 'field' => 'url', 'value' => rand_str( 10 ), 'type' => 'Pricing list: link', 'editor_type' => 'LINK' ),

				),
			),
			'Icon list' => array( 'icon-list',
				array(),
				'icon_list',
				array(
					array( 'field' => 'text', 'value' => rand_str( 10 ), 'type' => 'Icon List: Text', 'editor_type' => 'LINE' ),
					'link' => array( 'field' => 'url', 'value' => rand_str( 10 ), 'type' => 'Icon List: Link URL', 'editor_type' => 'LINK' ),
				),
			),
			'Slides' => array( 'slides',
				array(),
				'slides',
				array(
					array( 'field' => 'heading', 'value' => rand_str( 10 ), 'type' => 'Slides: heading', 'editor_type' => 'LINE' ),
					array( 'field' => 'description', 'value' => rand_str( 10 ), 'type' => 'Slides: description', 'editor_type' => 'VISUAL' ),
					array( 'field' => 'button_text', 'value' => rand_str( 10 ), 'type' => 'Slides: button text', 'editor_type' => 'LINE' ),
					'link' => array( 'field' => 'url', 'value' => rand_str( 10 ), 'type' => 'Slides: link URL', 'editor_type' => 'LINK' ),
				),
			),
			'Price table' => array( 'price-table',
				array(
					array( 'field' => 'heading', 'type' => 'Price Table: Heading', 'editor_type' => 'LINE' ),
					array( 'field' => 'sub_heading', 'type' => 'Price Table: Sub heading', 'editor_type' => 'LINE' ),
					array( 'field' => 'period', 'type' => 'Price Table: Period', 'editor_type' => 'LINE' ),
					array( 'field' => 'button_text', 'type' => 'Price Table: Button text', 'editor_type' => 'LINE' ),
					array( 'field' => 'footer_additional_info', 'type' => 'Price Table: Footer additional info', 'editor_type' => 'LINE' ),
					array( 'field' => 'ribbon_title', 'type' => 'Price Table: Ribbon title', 'editor_type' => 'LINE' ),
					'link' => array( 'field' => 'url', 'type' => 'Price Table: Button link', 'editor_type' => 'LINK' ) ),
				'features_list',
				array(
					array( 'field' => 'item_text', 'value' => rand_str( 10 ), 'type' => 'Price table: text', 'editor_type' => 'LINE' ),
				),
			),
			'Form' => array( 'form',
				array(
					array( 'field' => 'form_name', 'type' => 'Form: name', 'editor_type' => 'LINE' ),
					array( 'field' => 'button_text', 'type' => 'Form: Button text', 'editor_type' => 'LINE' ),
					array( 'field' => 'email_subject', 'type' => 'Form: Email subject', 'editor_type' => 'LINE' ),
					array( 'field' => 'email_from_name', 'type' => 'Form: Email from name', 'editor_type' => 'LINE' ),
					array( 'field' => 'success_message', 'type' => 'Form: Success message', 'editor_type' => 'LINE' ),
					array( 'field' => 'error_message', 'type' => 'Form: Error message', 'editor_type' => 'LINE' ),
					array( 'field' => 'required_message', 'type' => 'Form: Required message', 'editor_type' => 'LINE' ),
					array( 'field' => 'invalid_message', 'type' => 'Form: Invalid message', 'editor_type' => 'LINE' ) ),
				'form',
				array(
					array( 'field' => 'field_label', 'value' => rand_str( 10 ), 'type' => 'Form: Field label', 'editor_type' => 'LINE' ),
					array( 'field' => 'placeholder', 'value' => rand_str( 10 ), 'type' => 'Form: Field placeholder', 'editor_type' => 'LINE' ),
				),
			),
			'Posts' => array( 'posts',
				array(
					array( 'field' => 'classic_read_more_text', 'type' => 'Posts: Read more text', 'editor_type' => 'LINE' ),
				),
				'',
				array(),
			),
			'Menu Anchor' => array(
				'menu-anchor',
				array(
					array(
						'field'       => 'anchor',
						'type'        => 'Menu Anchor',
						'editor_type' => 'LINE'
					)
				),
				'',
				array(),
			),
		);
	}

	/**
	 * @test
	 */
	public function it_updates() {

		$node_id = mt_rand();
		$element = array( 'widgetType' => 'text-editor', 'editor' => rand_str() );
		$translation = rand_str();

		$string = new WPML_PB_String( $translation, 'editor-text-editor-' . $node_id, 'anything', 'anything' );

		$subject = new WPML_Elementor_Translatable_Nodes();
		$element = $subject->update( $node_id, $element, $string );

		$this->assertEquals( $translation, $element['settings']['editor'] );
	}

	/**
	 * @test
	 * @group wpmlcore-5803
	 */
	public function it_gets_single_repeater_field() {
		$subject = new WPML_Elementor_Translatable_Nodes();
		$field_id = 1;

		$nodes = array(
			'my-custom-module'      => array(
				'conditions'        => array( 'widgetType' => 'my-custom-module' ),
				'fields'            => array(),
				'integration-class' => 'WPML_PB_My_Custom_Module_With_A_Single_Repeater_Field',
			),
		);

		WP_Mock::onFilter( 'wpml_elementor_widgets_to_translate' )
		       ->with( $this->get_translatable_nodes() )
		       ->reply( $nodes );

		$element_data = array(
			'widgetType' => 'my-custom-module',
			'settings' => array(
				'items' => array(
					array(
						'text' => 'my_text',
						'_id' => $field_id,
					),
				),
			),
		);

		$node_id = 123;

		$string = new WPML_PB_String( 'my_text', 'my-custom-module-text-123-1', 'title', 'LINE' );

		$this->assertEquals( array( $string ), $subject->get( $node_id, $element_data ) );
	}

	/**
	 * @test
	 * @group wpmlcore-5803
	 */
	public function it_gets_multiple_repeater_field() {
		$subject = new WPML_Elementor_Translatable_Nodes();
		$field_id = 1;

		$nodes = array(
			'my-custom-module'      => array(
				'conditions'        => array( 'widgetType' => 'my-custom-module' ),
				'fields'            => array(),
				'integration-class' => array( 'WPML_PB_My_Custom_Module_With_A_Single_Repeater_Field', 'WPML_PB_My_Custom_Module_With_A_Single_Repeater_Field_2' ),
			),
		);

		WP_Mock::onFilter( 'wpml_elementor_widgets_to_translate' )
		       ->with( $this->get_translatable_nodes() )
		       ->reply( $nodes );

		$element_data = array(
			'widgetType' => 'my-custom-module',
			'settings' => array(
				'items' => array(
					array(
						'text' => 'my_text',
						'_id' => $field_id,
					),
				),
				'items_2' => array(
					array(
						'text' => 'my_text_2',
						'_id' => $field_id,
					),
				),
			),
		);

		$node_id = 123;

		$string = new WPML_PB_String( 'my_text', 'my-custom-module-text-123-1', 'title', 'LINE' );
		$string2 = new WPML_PB_String( 'my_text_2', 'my-custom-module-text-123-1', 'title', 'LINE' );

		$this->assertEquals( array( $string, $string2 ), $subject->get( $node_id, $element_data ) );
	}

	/**
	 * @test
	 * @group wpmlcore-5803
	 */
	public function it_updates_single_repeater_field() {
		$subject = new WPML_Elementor_Translatable_Nodes();
		$field_id = 1;

		$nodes = array(
			'my-custom-module'      => array(
				'conditions'        => array( 'widgetType' => 'my-custom-module' ),
				'fields'            => array(),
				'integration-class' => 'WPML_PB_My_Custom_Module_With_A_Single_Repeater_Field',
			),
		);

		WP_Mock::onFilter( 'wpml_elementor_widgets_to_translate' )
		       ->with( $this->get_translatable_nodes() )
		       ->reply( $nodes );

		$element_data = array(
			'widgetType' => 'my-custom-module',
			'settings' => array(
				'items' => array(
					array(
						'text' => 'my_text',
						'_id' => $field_id,
					),
				),
			),
		);

		$node_id = 123;
		$new_string_value = 'the_value';

		$expected_element_data = array(
			'widgetType' => 'my-custom-module',
			'settings' => array(
				'items' => array(
					array(
						'text' => $new_string_value,
						'_id' => $field_id,
						'index' => 0,
					),
				),
			),
		);

		$string = new WPML_PB_String( 'my_text', 'my-custom-module-text-123-1', 'title', 'LINE' );
		$string->set_value( $new_string_value );

		$this->assertEquals( $expected_element_data, $subject->update( $node_id, $element_data, $string ) );
	}

	/**
	 * @test
	 * @group wpmlcore-5803
	 */
	public function it_updates_multiple_repeater_field() {
		$subject    = new WPML_Elementor_Translatable_Nodes();
		$field_id   = 1;
		$field_id_2 = 2;

		$nodes = array(
			'my-custom-module' => array(
				'conditions'        => array( 'widgetType' => 'my-custom-module' ),
				'fields'            => array(),
				'integration-class' => array(
					'WPML_PB_My_Custom_Module_With_A_Single_Repeater_Field',
					'WPML_PB_My_Custom_Module_With_A_Single_Repeater_Field_2'
				),
			),
		);

		WP_Mock::onFilter( 'wpml_elementor_widgets_to_translate' )
		       ->with( $this->get_translatable_nodes() )
		       ->reply( $nodes );

		$element_data = array(
			'widgetType' => 'my-custom-module',
			'settings'   => array(
				'items'   => array(
					array(
						'text' => 'my_text',
						'_id'  => $field_id,
					),
				),
				'items_2' => array(
					array(
						'text' => 'my_text_2',
						'_id'  => $field_id_2,
					),
				),
			),
		);

		$new_string_text   = 'first_string';

		$expected_element_data = array(
			'widgetType' => 'my-custom-module',
			'settings'   => array(
				'items'   => array(
					array(
						'text'  => $new_string_text,
						'_id'   => $field_id,
						'index' => 0,
					),
				),
				'items_2' => array(
					array(
						'text'  => 'my_text_2',
						'_id'   => $field_id_2,
					),
				),
			),
		);

		$node_id = 123;

		$string = new WPML_PB_String( 'my_text', 'my-custom-module-text-123-1', 'title', 'LINE' );
		$string->set_value( $new_string_text );

		$this->assertEquals( $expected_element_data, $subject->update( $node_id, $element_data, $string ) );
	}

	private function get_translatable_nodes() {
		return array(
			'heading'     => array(
				'conditions' => array( 'widgetType' => 'heading' ),
				'fields'     => array(
					array(
						'field'       => 'title',
						'type'        => __( 'Heading', 'sitepress' ),
						'editor_type' => 'LINE'
					),
				),
			),
			'text-editor' => array(
				'conditions' => array( 'widgetType' => 'text-editor' ),
				'fields'     => array(
					array(
						'field'       => 'editor',
						'type'        => __( 'Text editor', 'sitepress' ),
						'editor_type' => 'VISUAL'
					),
				),
			),
			'icon'        => array(
				'conditions' => array( 'widgetType' => 'icon' ),
				'fields'     => array(
					'link' => array(
						'field'       => 'url',
						'type'        => __( 'Icon: Link URL', 'sitepress' ),
						'editor_type' => 'LINK'
					),
				),
			),
			'video'       => array(
				'conditions' => array( 'widgetType' => 'video' ),
				'fields'     => array(
					array(
						'field'       => 'link',
						'type'        => __( 'Video: Link', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'vimeo_link',
						'type'        => __( 'Video: Vimeo link', 'sitepress' ),
						'editor_type' => 'LINE'
					),
				),
			),
			'login'       => array(
				'conditions' => array( 'widgetType' => 'login' ),
				'fields'     => array(
					array(
						'field'       => 'button_text',
						'type'        => __( 'Login: Button text', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'user_label',
						'type'        => __( 'Login: User label', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'user_placeholder',
						'type'        => __( 'Login: User placeholder', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'password_label',
						'type'        => __( 'Login: Password label', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'password_placeholder',
						'type'        => __( 'Login: Password placeholder', 'sitepress' ),
						'editor_type' => 'LINE'
					),
				),
			),
			'button'      => array(
				'conditions' => array( 'widgetType' => 'button' ),
				'fields'     => array(
					array(
						'field'       => 'text',
						'type'        => __( 'Button', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					'link' => array(
						'field'       => 'url',
						'type'        => __( 'Button: Link URL', 'sitepress' ),
						'editor_type' => 'LINK'
					),
				),
			),
			'html'        => array(
				'conditions' => array( 'widgetType' => 'html' ),
				'fields'     => array(
					array(
						'field'       => 'html',
						'type'        => __( 'HTML', 'sitepress' ),
						'editor_type' => 'AREA'
					),
				),
			),
			'image'       => array(
				'conditions' => array( 'widgetType' => 'image' ),
				'fields'     => array(
					array(
						'field'       => 'caption',
						'type'        => __( 'Image: Caption', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					'link' => array(
						'field'       => 'url',
						'type'        => __( 'Image: Link URL', 'sitepress' ),
						'editor_type' => 'LINK'
					),
				),
			),
			'alert'       => array(
				'conditions' => array( 'widgetType' => 'alert' ),
				'fields'     => array(
					array(
						'field'       => 'alert_title',
						'type'        => __( 'Alert title', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'alert_description',
						'type'        => __( 'Alert description', 'sitepress' ),
						'editor_type' => 'VISUAL'
					),
				),
			),
			'blockquote'       => array(
				'conditions' => array( 'widgetType' => 'blockquote' ),
				'fields'     => array(
					array(
						'field'       => 'blockquote_content',
						'type'        => __( 'Blockquote: Content', 'sitepress' ),
						'editor_type' => 'VISUAL'
					),
					array(
						'field'       => 'tweet_button_label',
						'type'        => __( 'Blockquote: Tweet button label', 'sitepress' ),
						'editor_type' => 'LINE'
					),
				),
			),
			'testimonial' => array(
				'conditions' => array( 'widgetType' => 'testimonial' ),
				'fields'     => array(
					array(
						'field'       => 'testimonial_content',
						'type'        => __( 'Testimonial content', 'sitepress' ),
						'editor_type' => 'VISUAL'
					),
					array(
						'field'       => 'testimonial_name',
						'type'        => __( 'Testimonial name', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'testimonial_job',
						'type'        => __( 'Testimonial job', 'sitepress' ),
						'editor_type' => 'LINE'
					),
				),
			),
			'progress'    => array(
				'conditions' => array( 'widgetType' => 'progress' ),
				'fields'     => array(
					array(
						'field'       => 'title',
						'type'        => __( 'Progress: Title', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'inner_text',
						'type'        => __( 'Progress: Inner text', 'sitepress' ),
						'editor_type' => 'LINE'
					),
				),
			),
			'counter'     => array(
				'conditions' => array( 'widgetType' => 'counter' ),
				'fields'     => array(
					array(
						'field'       => 'starting_number',
						'type'        => __( 'Starting number', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'title',
						'type'        => __( 'Title', 'sitepress' ),
						'editor_type' => 'LINE'
					),
				),
			),
			'countdown'     => array(
				'conditions' => array( 'widgetType' => 'countdown' ),
				'fields'     => array(
					array(
						'field'       => 'label_days',
						'type'        => __( 'Countdown: Label days', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'label_hours',
						'type'        => __( 'Countdown: Label hours', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'label_minutes',
						'type'        => __( 'Countdown: Label minutes', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'label_seconds',
						'type'        => __( 'Countdown: Label seconds', 'sitepress' ),
						'editor_type' => 'LINE'
					),
				),
			),
			'icon-box'    => array(
				'conditions' => array( 'widgetType' => 'icon-box' ),
				'fields'     => array(
					array(
						'field'       => 'title_text',
						'type'        => __( 'Icon Box: Title text', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'description_text',
						'type'        => __( 'Icon Box: Description text', 'sitepress' ),
						'editor_type' => 'VISUAL'
					),
					'link' => array(
						'field'       => 'url',
						'type'        => __( 'Icon Box: Link', 'sitepress' ),
						'editor_type' => 'LINK'
					),
				),
			),
			'image-box'   => array(
				'conditions' => array( 'widgetType' => 'image-box' ),
				'fields'     => array(
					array(
						'field'       => 'title_text',
						'type'        => __( 'Image Box: Title text', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'description_text',
						'type'        => __( 'Image Box: Description text', 'sitepress' ),
						'editor_type' => 'LINE'
					),
				),
			),
			'animated-headline'   => array(
				'conditions' => array( 'widgetType' => 'animated-headline' ),
				'fields'     => array(
					array(
						'field'       => 'before_text',
						'type'        => __( 'Animated Headline: Before text', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'highlighted_text',
						'type'        => __( 'Animated Headline: Highlighted text', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'rotating_text',
						'type'        => __( 'Animated Headline: Rotating text', 'sitepress' ),
						'editor_type' => 'AREA'
					),
					array(
						'field'       => 'after_text',
						'type'        => __( 'Animated Headline: After text', 'sitepress' ),
						'editor_type' => 'LINE'
					),
				),
			),
			'flip-box'    => array(
				'conditions' => array( 'widgetType' => 'flip-box' ),
				'fields'     => array(
					array(
						'field'       => 'title_text_a',
						'type'        => __( 'Flip Box: Title text side A', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'description_text_a',
						'type'        => __( 'Flip Box: Description text side A', 'sitepress' ),
						'editor_type' => 'VISUAL'
					),
					array(
						'field'       => 'title_text_b',
						'type'        => __( 'Flip Box: Title text side B', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'description_text_b',
						'type'        => __( 'Flip Box: Description text side B', 'sitepress' ),
						'editor_type' => 'VISUAL'
					),
					array(
						'field'       => 'button_text',
						'type'        => __( 'Flip Box: Button text', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					'link' => array(
						'field'       => 'url',
						'type'        => __( 'Flip Box: Button link', 'sitepress' ),
						'editor_type' => 'LINK'
					),
				),
			),
			'call-to-action'    => array(
				'conditions' => array( 'widgetType' => 'call-to-action' ),
				'fields'     => array(
					array(
						'field'       => 'title',
						'type'        => __( 'Call to action: title', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'description',
						'type'        => __( 'Call to action: description', 'sitepress' ),
						'editor_type' => 'VISUAL'
					),
					array(
						'field'       => 'button',
						'type'        => __( 'Call to action: button', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'ribbon_title',
						'type'        => __( 'Call to action: ribbon title', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					'link' => array(
						'field'       => 'url',
						'type'        => __( 'Call to action: link', 'sitepress' ),
						'editor_type' => 'LINK'
					),
				),
			),
			'toggle'      => array(
				'conditions'        => array( 'widgetType' => 'toggle' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Elementor_Toggle',
			),
			'accordion'   => array(
				'conditions'        => array( 'widgetType' => 'accordion' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Elementor_Accordion',
			),
			'testimonial-carousel'   => array(
				'conditions'        => array( 'widgetType' => 'testimonial-carousel' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Elementor_Testimonial_Carousel',
			),
			'tabs'        => array(
				'conditions'        => array( 'widgetType' => 'tabs' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Elementor_Tabs',
			),
			'price-list'  => array(
				'conditions'        => array( 'widgetType' => 'price-list' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Elementor_Price_List',
			),
			'icon-list'   => array(
				'conditions'        => array( 'widgetType' => 'icon-list' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Elementor_Icon_List',
			),
			'slides'      => array(
				'conditions'        => array( 'widgetType' => 'slides' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Elementor_Slides',
			),
			'price-table' => array(
				'conditions'        => array( 'widgetType' => 'price-table' ),
				'fields'            => array(
					array(
						'field'       => 'heading',
						'type'        => __( 'Price Table: Heading', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'sub_heading',
						'type'        => __( 'Price Table: Sub heading', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'period',
						'type'        => __( 'Price Table: Period', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'button_text',
						'type'        => __( 'Price Table: Button text', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'footer_additional_info',
						'type'        => __( 'Price Table: Footer additional info', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'ribbon_title',
						'type'        => __( 'Price Table: Ribbon title', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					'link' => array(
						'field'       => 'url',
						'type'        => __( 'Price Table: Button link', 'sitepress' ),
						'editor_type' => 'LINK'
					),
				),
				'integration-class' => 'WPML_Elementor_Price_Table',
			),
			'form'        => array(
				'conditions'        => array( 'widgetType' => 'form' ),
				'fields'            => array(
					array(
						'field'       => 'form_name',
						'type'        => __( 'Form: name', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'button_text',
						'type'        => __( 'Form: Button text', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'email_subject',
						'type'        => __( 'Form: Email subject', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'email_from_name',
						'type'        => __( 'Form: Email from name', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'success_message',
						'type'        => __( 'Form: Success message', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'error_message',
						'type'        => __( 'Form: Error message', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'required_message',
						'type'        => __( 'Form: Required message', 'sitepress' ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'invalid_message',
						'type'        => __( 'Form: Invalid message', 'sitepress' ),
						'editor_type' => 'LINE'
					),
				),
				'integration-class' => 'WPML_Elementor_Form',
			),
			'posts'       => array(
				'conditions' => array( 'widgetType' => 'posts' ),
				'fields'     => array(
					array(
						'field'       => 'classic_read_more_text',
						'type'        => __( 'Posts: Read more text', 'sitepress' ),
						'editor_type' => 'LINE'
					),
				),
			),
			'menu-anchor' => array(
				'conditions' => array( 'widgetType' => 'menu-anchor' ),
				'fields'     => array(
					array(
						'field'       => 'anchor',
						'type'        => __( 'Menu Anchor', 'sitepress' ),
						'editor_type' => 'LINE'
					),
				),
			),
		);
	}
}

if ( ! class_exists( 'WPML_PB_My_Custom_Module_With_A_Single_Repeater_Field' ) ) {
	class WPML_PB_My_Custom_Module_With_A_Single_Repeater_Field extends WPML_Elementor_Module_With_Items {

		/**
		 * @return string
		 */
		public function get_items_field() {
			return 'items';
		}

		/**
		 * @return array
		 */
		public function get_fields() {
			return array( 'text' );
		}

		/**
		 * @param string $field
		 *
		 * @return string
		 */
		protected function get_title( $field ) {
			$title = '';

			if ( 'text' === $field ) {
				$title = 'title';
			}

			return $title;
		}

		/**
		 * @param string $field
		 *
		 * @return string
		 */
		protected function get_editor_type( $field ) {
			$editor = '';

			if ( 'text' === $field ) {
				$editor = 'LINE';
			}

			return $editor;
		}
	}
}

if ( ! class_exists( 'WPML_PB_My_Custom_Module_With_A_Single_Repeater_Field_2' ) ) {
	class WPML_PB_My_Custom_Module_With_A_Single_Repeater_Field_2 extends WPML_Elementor_Module_With_Items {

		/**
		 * @return string
		 */
		public function get_items_field() {
			return 'items_2';
		}

		/**
		 * @return array
		 */
		public function get_fields() {
			return array( 'text' );
		}

		/**
		 * @param string $field
		 *
		 * @return string
		 */
		protected function get_title( $field ) {
			$title = '';

			if ( 'text' === $field ) {
				$title = 'title';
			}

			return $title;
		}

		/**
		 * @param string $field
		 *
		 * @return string
		 */
		protected function get_editor_type( $field ) {
			$editor = '';

			if ( 'text' === $field ) {
				$editor = 'LINE';
			}

			return $editor;
		}
	}
}