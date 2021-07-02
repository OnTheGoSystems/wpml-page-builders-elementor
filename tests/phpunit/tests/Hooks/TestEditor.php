<?php

namespace WPML\PB\Elementor\Hooks;

use WPML\LIB\WP\OnActionMock;

/**
 * @group hooks
 * @group editor
 */
class TestEditor extends \OTGS_TestCase {

	use OnActionMock;

	public function setUp() {
		parent::setUp();
		$this->setUpOnAction();
	}

	public function tearDown() {
		unset( $_POST['editor_post_id'], $_POST['elementor_ajax'] );
		$this->tearDownOnAction();
		parent::tearDown();
	}

	/**
	 * @test
	 */
	public function itReturnsTrueIfAlreadyTranslatingWithNativeEditor() {
		$subject = new Editor();
		$subject->add_hooks();

		$this->assertTrue( $this->runFilter( 'wpml_pb_is_editing_translation_with_native_editor', true ) );
	}

	/**
	 * @test
	 */
	public function itReturnsTrueIfTranslatingWithElementorNativeEditor() {
		$_POST = [
			'editor_post_id' => 123,
			'action'         => 'elementor_ajax',
		];

		$subject = new Editor();
		$subject->add_hooks();

		$this->assertTrue( $this->runFilter( 'wpml_pb_is_editing_translation_with_native_editor', false ) );
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

		$this->assertFalse( $this->runFilter( 'wpml_pb_is_editing_translation_with_native_editor', false ) );
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
					'editor_post_id' => 123,
					'action'         => 'not_the_right_action',
				],
			],
		];
	}
}
