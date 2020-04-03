<?php

namespace WPML\PB\Elementor\DynamicContent;

use WPML_PB_String;

/**
 * @group dynamic-content
 * @group wpmlcore-6244
 */
class TestStrings extends \OTGS_TestCase {

	/**
	 * @test
	 * @dataProvider dpElementWithoutDynamicContent
	 *
	 * @param array $elementWithoutDynamicContent
	 */
	public function itShouldNotFilterIfElementDoesNotHaveDynamicContent( array $elementWithoutDynamicContent ) {
		$strings = [
			new WPML_PB_String( 'the value', 'the name', 'the title', 'LINE' ),
		];

		$nodeId = 'ef89gl32';

		$this->assertSame( $strings, Strings::filter( $strings, $nodeId, $elementWithoutDynamicContent ) );
	}

	public function dpElementWithoutDynamicContent() {
		return [
			[ [] ],
			[ [ 'settings' => [ 'text' => [] ] ] ],
		];
	}

	/**
	 * @test
	 */
	public function itShouldAddDynamicContentStringsAndRemoveStaticOnes() {
		$fieldName1         = 'title';
		$fieldName2         = 'content';
		$staticStringValue1 = 'the static text 1';
		$staticStringValue2 = 'the static text 2';
		$before             = 'my before';
		$after              = 'my after';
		$fallback           = 'my fallback';
		$before2            = 'my before 2';

		$nodeId = 'ef89gl32';

		$settings1 = [
			'before'   => $before,
			'after'    => $after,
			'fallback' => $fallback,
			'foo'      => 'bar', // Filter out because not in the translatable whitelist
		];

		$settings2 = [
			'before' => $before2,
		];

		$element = [
			'id'       => $nodeId,
			'settings' => [
				$fieldName1 => $staticStringValue1,
				'foo1'      => 'bar1',
				'foo2'      => 'bar2',
				$fieldName2 => $staticStringValue2, // This field is not translatable (no static string) but has dynamic settings.
				'__dynamic__' => [
					$fieldName1   => '[elementor-tag id="cc0b6c6" name="post-title" settings="' . urlencode( json_encode( $settings1 ) ) . '"]',
					$fieldName2 => '[elementor-tag id="gf45gg9" name="post-content" settings="' . urlencode( json_encode( $settings2 ) ) . '"]',
				],
			]
		];

		$pbStringTitle = new WPML_PB_String( $staticStringValue1, self::getStaticStringName( $fieldName1, $nodeId ), 'the title', 'LINE' );
		$pbStringFoo1  = new WPML_PB_String( 'bar1', self::getStaticStringName( 'foo1', $nodeId ), 'the foo1', 'LINE' );
		$pbStringFoo2  = new WPML_PB_String( 'bar2', self::getStaticStringName( 'foo2', $nodeId ), 'the foo2', 'LINE' );

		$originalStrings = [
			$pbStringFoo1,
			$pbStringTitle,
			$pbStringFoo2,
		];

		$expectedStrings = [
			$pbStringFoo1,
			self::getPbString( $before, $nodeId, '', $fieldName1, 'before' ),
			self::getPbString( $after, $nodeId, '', $fieldName1, 'after' ),
			self::getPbString( $fallback, $nodeId, '', $fieldName1, 'fallback' ),
			$pbStringFoo2,
			self::getPbString( $before2, $nodeId, '', $fieldName2, 'before' ),
		];

		$this->assertEquals( $expectedStrings, Strings::filter( $originalStrings, $nodeId, $element ) );
	}

	/**
	 * @test
	 * @group wpmlcore-7194
	 */
	public function itShouldAddDynamicContentStringsAndRemoveStaticOnesForModuleWithItems() {
		$fieldName1         = 'title';
		$staticStringValue1 = 'the static text 1';
		$staticStringValue2 = 'the static text 2';
		$before             = 'my before';
		$after              = 'my after';
		$fallback           = 'my fallback';

		$nodeId  = 'ef89gl32';
		$itemId1 = 'ag44kl55';
		$itemId2 = 'kk88ll77';

		$settings1 = [
			'before'   => $before,
			'after'    => $after,
			'fallback' => $fallback,
		];

		$element = [
			'id'       => $nodeId,
			'settings' => [
				'price-list' => [
					[
						'_id'         => $itemId1,
						$fieldName1   => $staticStringValue1,
						'__dynamic__' => [
							$fieldName1   => '[elementor-tag id="cc0b6c6" name="post-title" settings="' . urlencode( json_encode( $settings1 ) ) . '"]',
						],
					],
					[
						'_id'         => $itemId2,
						$fieldName1   => $staticStringValue2,
					],
				],
			],
		];

		$pbStringTitle1 = new WPML_PB_String( $staticStringValue1, self::getStaticStringName( $fieldName1, $nodeId, $itemId1 ), 'the title', 'LINE' );
		$pbStringTitle2 = new WPML_PB_String( $staticStringValue2, self::getStaticStringName( $fieldName1, $nodeId, $itemId2 ), 'the title', 'LINE' );

		$originalStrings = [
			$pbStringTitle1,
			$pbStringTitle2,
		];

		$expectedStrings = [
			self::getPbString( $before, $nodeId, $itemId1, $fieldName1, 'before' ),
			self::getPbString( $after, $nodeId, $itemId1, $fieldName1, 'after' ),
			self::getPbString( $fallback, $nodeId, $itemId1, $fieldName1, 'fallback' ),
			$pbStringTitle2,
		];

		$this->assertEquals( $expectedStrings, Strings::filter( $originalStrings, $nodeId, $element ) );
	}

	/**
	 * @test
	 * @dataProvider dpNotDynamicStringName
	 *
	 * @param string $stringName
	 */
	public function itShouldNotUpdateNodeIfNotDynamicContentString( $stringName ) {
		$before   = 'my before';
		$after    = 'my after';
		$fallback = 'my fallback';

		$settings = [
			'before'   => $before,
			'after'    => $after,
			'fallback' => $fallback,
		];

		$element = [
			'settings' => [
				'title' => 'the static title',
				'__dynamic__' => [
					'title' => '[elementor-tag id="cc0b6c6" name="post-title" settings="' . urlencode( json_encode( $settings ) ) . '"]'
				],
			]
		];

		$pbString = new WPML_PB_String( 'some value', $stringName, 'some title', 'LINE' );

		$this->assertSame( $element, Strings::updateNode( $element, $pbString ) );
	}

	public function dpNotDynamicStringName() {
		return [
			'no delimiter'             => [ 'string without any delimiter' ],
			'less than 4 parts'        => [ Strings::NAME_PREFIX . '-3-parts' ],
			'more than 4 parts'        => [ Strings::NAME_PREFIX . '-name-with-more-than-4-parts' ],
			'not starting with prefix' => [ 'non-' . Strings::NAME_PREFIX . '-string-name' ],
		];
	}

	/**
	 * @test
	 * @dataProvider dpElementWithoutDynamicContent
	 *
	 * @param array $elementWithoutDynamicContent
	 */
	public function itShouldNotUpdateNodeIfNoDynamicContentField( array $elementWithoutDynamicContent ) {
		$validDynamicContentPbString = self::getPbString( 'some value', '45gh69ee', '', 'title', 'before');

		$this->assertSame(
			$elementWithoutDynamicContent,
			Strings::updateNode( $elementWithoutDynamicContent, $validDynamicContentPbString )
		);
	}

	/**
	 * @test
	 */
	public function itShouldUpdateNodeWithDynamicContentString() {
		$originalBefore   = 'my before';
		$translatedBefore = 'TRANSLATED my before';
		$fieldName        = 'title';

		$string = self::getPbString( $translatedBefore, '45gh69ee', '', $fieldName, 'before');

		$getElement = function( $beforeText ) use ( $fieldName ) {
			$settings = [
				'before'   => $beforeText,
				'after'    => 'my after',
				'fallback' => 'my fallback',
			];

			return [
				'settings' => [
					$fieldName => 'the static title',
					'__dynamic__' => [
						$fieldName => '[elementor-tag id="cc0b6c6" name="post-title" settings="' . urlencode( json_encode( $settings ) ) . '"]'
					],
				]
			];
		};

		$originalElement = $getElement( $originalBefore );
		$expectedElement = $getElement( $translatedBefore );

		$this->assertSame(
			$expectedElement,
			Strings::updateNode( $originalElement, $string )
		);
	}

	/**
	 * @test
	 * @group wpmlcore-7194
	 */
	public function itShouldUpdateNodeWithDynamicContentStringForModuleWithItems() {
		$originalBefore   = 'my before';
		$translatedBefore = 'TRANSLATED my before';
		$fieldName        = 'title';
		$itemId           = 'ag44kl55';

		$string = self::getPbString( $translatedBefore, '45gh69ee', $itemId, $fieldName, 'before');

		$getElement = function( $beforeText ) use ( $fieldName, $itemId ) {
			$settings = [
				'before'   => $beforeText,
				'after'    => 'my after',
				'fallback' => 'my fallback',
			];

			$unchangedSettings = [
				'before'   => 'my before unchanged',
				'after'    => 'my after unchanged',
				'fallback' => 'my fallback unchanged',
			];

			return [
				'settings' => [
					'price-list' => [
						[
							'_id'         => 'not-' . $itemId,
							$fieldName    => 'the static title',
							'__dynamic__' => [
								$fieldName => '[elementor-tag id="cc0b6c6" name="post-title" settings="' . urlencode( json_encode( $unchangedSettings ) ) . '"]'
							],
						],
						[
							'_id'         => $itemId,
							$fieldName    => 'the static title',
							'__dynamic__' => [
								$fieldName => '[elementor-tag id="cc0b6c6" name="post-title" settings="' . urlencode( json_encode( $settings ) ) . '"]'
							],
						],
					],
				],
			];
		};

		$originalElement = $getElement( $originalBefore );
		$expectedElement = $getElement( $translatedBefore );

		$this->assertSame(
			$expectedElement,
			Strings::updateNode( $originalElement, $string )
		);
	}

	/**
	 * @see \WPML_Elementor_Translatable_Nodes::get_string_name()
	 * @see \WPML_Elementor_Module_With_Items::get_string_name()
	 *
	 * @param string $fieldName
	 * @param string $nodeId
	 * @param string $itemId
	 *
	 * @return string
	 */
	private static function getStaticStringName( $fieldName, $nodeId, $itemId = '' ) {
		$name = $fieldName . '-someWidgetType-' . $nodeId;

		if ( $itemId ) {
			$name .= '-' . $itemId;
		}

		return $name;
	}

	/**
	 * @param string $value
	 * @param string $nodeId
	 * @param string $îtemId
	 * @param string $fieldName
	 * @param string $settingName
	 *
	 * @return WPML_PB_String
	 */
	private static function getPbString( $value, $nodeId, $îtemId, $fieldName, $settingName ) {
		return new WPML_PB_String( $value, Strings::getStringName( $nodeId, $îtemId, $fieldName, $settingName ), "Dynamic content string: $fieldName", 'LINE' );
	}
}