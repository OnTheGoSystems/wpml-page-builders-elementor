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
				} else {
					$this->assertEquals( $element['settings'][ $items_field ][0][ $item_key ][ $item['field'] ], $string->get_value() );
				}
				$this->assertEquals( md5( $item['value'] ) . '-' . $item['field'] . '-' . $node_id, $string->get_name() );
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
				array( 'field' => 'html', 'type' => 'HTML', 'editor_type' => 'VISUAL' ) ),
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
			'Icon box' => array( 'icon-box', array(
				array( 'field' => 'title_text', 'type' => 'Icon Box: Title text', 'editor_type' => 'LINE' ),
				array( 'field' => 'description_text', 'type' => 'Icon Box: Description text', 'editor_type' => 'VISUAL' ) ),
				'',
				array(),
			),
			'Image box' => array( 'image-box', array(
				array( 'field' => 'title_text', 'type' => 'Image Box: Title text', 'editor_type' => 'LINE' ),
				array( 'field' => 'description_text', 'type' => 'Image Box: Description text', 'editor_type' => 'VISUAL' ) ),
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
}