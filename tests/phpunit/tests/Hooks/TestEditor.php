<?php

namespace WPML\PB\Elementor\Hooks;

use WPML\LIB\WP\OnActionMock;

/**
 * @group hooks
 * @group editor
 */
class TestEditor extends \OTGS_TestCase {

	use OnActionMock;

	const ORIGINAL_ID    = 123;
	const TRANSLATION_ID = 456;

	public function setUp() {
		parent::setUp();
		$this->setUpOnAction();
	}

	public function tearDown() {
		unset( $_POST['editor_post_id'], $_POST['action'] );
		$this->tearDownOnAction();
		parent::tearDown();
	}

	/**
	 * @test
	 */
	public function itReturnsTrueIfAlreadyTranslatingWithNativeEditor() {
		$subject = new Editor();
		$subject->add_hooks();

		$this->assertTrue( $this->runFilter( 'wpml_pb_is_editing_translation_with_native_editor', true, self::TRANSLATION_ID ) );
	}

	/**
	 * @test
	 */
	public function itReturnsTrueIfTranslatingWithElementorNativeEditor() {
		$_POST = [
			'editor_post_id' => (string) self::TRANSLATION_ID,
			'action'         => 'elementor_ajax',
		];

		$subject = new Editor();
		$subject->add_hooks();

		$this->assertTrue( $this->runFilter( 'wpml_pb_is_editing_translation_with_native_editor', false, self::TRANSLATION_ID ) );
	}

	/**
	 * @test
	 * @dataProvider dpNotTranslatingWithElementorNativeEditor
	 *
	 * @param array $postData
	 */
	public function itReturnsFalseIfNotTranslatingWithElementorNativeEditor( $postData ) {
		$_POST = $postData;

		$subject = new Editor();
		$subject->add_hooks();

		$this->assertFalse( $this->runFilter( 'wpml_pb_is_editing_translation_with_native_editor', false, self::TRANSLATION_ID ) );
	}

	public function dpNotTranslatingWithElementorNativeEditor() {
		return [
			[
				[],
			],
			[
				[
					'action'         => 'elementor_ajax',
				],
			],
			[
				[
					'editor_post_id' => (string) self::TRANSLATION_ID,
					'action'         => 'not_the_right_action',
				],
			],
			// wpmlcore-8858 - globally editing the original
			[
				[
					'editor_post_id' => (string) self::ORIGINAL_ID,
					'action'         => 'elementor_ajax',
				],
			],
		];
	}
}
