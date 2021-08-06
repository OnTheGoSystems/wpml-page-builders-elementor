<?php

namespace WPML\PB\Elementor\Hooks;

use tad\FunctionMocker\FunctionMocker;
use WPML\FP\Fns;
use WPML\LIB\WP\PostMock;
use WPML\LIB\WP\Post;
use WPML\LIB\WP\WPDBMock;
use WPML_Elementor_Data_Settings;

/**
 * @group hooks
 * @group gutenberg-cleanup
 */
class TestGutenbergCleanup extends \OTGS_TestCase {

	use PostMock;
	use WPDBMock;

	public function setUp() {
		parent::setUp();

		$this->setUpPostMock();
		$this->setUpWPDBMock();

		\WP_Mock::passthruFunction( 'wp_slash' );
		\WP_Mock::userFunction( 'wp_json_encode', [
			'return' => function( $value ) {
				return json_encode( $value );
			},
		] );
	}

	/**
	 * @test
	 */
	public function itShouldAddHooks() {
		$subject = new GutenbergCleanup();

		\WP_Mock::expectFilterAdded(
			'update_post_metadata',
			Fns::withoutRecursion( Fns::identity(), [ $subject, 'removeGutenbergFootprint' ] ),
			10,
			4
		);

		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function itShouldNOTRemoveGbFootprintIfNotElementorDataMeta() {
		$check     = 'some boolean';
		$postId    = 123;
		$metaKey   = 'some key';
		$metaValue = 'some value';

		$subject = new GutenbergCleanup();

		$this->assertSame(
			$check,
			$subject->removeGutenbergFootprint( $check, $postId, $metaKey, $metaValue )
		);
	}

	/**
	 * @test
	 */
	public function itShouldNOTRemoveGbFootprintIfPostNOTEditedWithElementor() {
		$check     = 'some boolean';
		$postId    = 123;
		$metaKey   = WPML_Elementor_Data_Settings::META_KEY_DATA;
		$metaValue = 'some value';

		\WP_Mock::userFunction( 'update_metadata', [
			'times' => 0
		]);

		$isEditedWithElementor = FunctionMocker::replace( WPML_Elementor_Data_Settings::class . '::is_edited_with_elementor', false );

		$subject = new GutenbergCleanup();

		$this->assertSame(
			$check,
			$subject->removeGutenbergFootprint( $check, $postId, $metaKey, $metaValue )
		);

		$isEditedWithElementor->wasCalledWithOnce( [ $postId ] );
	}

	/**
	 * @test
	 */
	public function itShouldNOTRemoveGbFootprintIfNOBlockIsFound() {
		$check     = 'some boolean';
		$postId    = 123;
		$metaKey   = WPML_Elementor_Data_Settings::META_KEY_DATA;
		$metaValue = self::getMetaValue( '<p><\/p>\n<p>Something sure!<\/p>\n<p><\/p>' );

		\WP_Mock::userFunction( 'update_metadata', [
			'times' => 0
		]);

		$isEditedWithElementor = FunctionMocker::replace( WPML_Elementor_Data_Settings::class . '::is_edited_with_elementor', true );

		\WP_Mock::userFunction( 'update_post_metadata' )->never();

		$subject = new GutenbergCleanup();

		$this->assertSame(
			$check,
			$subject->removeGutenbergFootprint( $check, $postId, $metaKey, $metaValue )
		);

		$isEditedWithElementor->wasCalledWithOnce( [ $postId ] );
	}

	/**
	 * @test
	 */
	public function itShouldRemoveGbFootprintIfOneBlockIsFound() {
		$check        = 'some boolean';
		$postId       = 123;
		$metaKey      = WPML_Elementor_Data_Settings::META_KEY_DATA;
		$metaValue    = self::getMetaValue( '<p><!-- wp:paragraph --><\/p><!-- /wp:paragraph -->\n<p>Something sure!<\/p>\n<p><\/p>' );
		$newMetaValue = self::getMetaValue( '<p><\/p>\n<p>Something sure!<\/p>\n<p><\/p>' );

		\WP_Mock::userFunction( 'update_metadata', [
			'args' => [ 'post', $postId, WPML_Elementor_Data_Settings::META_KEY_DATA, $newMetaValue ],
			'times' => 1
		]);

		$elementorPackage = $this->getPackage( 'elementor', $postId );
		$gutenbergPackage = $this->getPackage( 'gutenberg', $postId );
		$unknownPackage   = $this->getPackage( 'unknown', $postId );

		$isEditedWithElementor = FunctionMocker::replace( WPML_Elementor_Data_Settings::class . '::is_edited_with_elementor', true );

		\WP_Mock::onFilter( 'wpml_st_get_post_string_packages' )
			->with( [], $postId )
			->reply( [ $elementorPackage, $gutenbergPackage, $unknownPackage ] );

		$deleteAction = FunctionMocker::replace( 'do_action' );

		$subject = new GutenbergCleanup();

		$this->assertTrue(
			$subject->removeGutenbergFootprint( $check, $postId, $metaKey, $metaValue )
		);

		$isEditedWithElementor->wasCalledWithOnce( [ $postId ] );
		$deleteAction->wasCalledWithOnce( [ 'wpml_delete_package', $gutenbergPackage->name, $gutenbergPackage->kind ] );
	}

	private static function getMetaValue( $editorContent ) {
		return '[{"id":"dc45eec","elType":"section","settings":[],"elements":[{"id":"761adbdf","elType":"column","settings":{"_column_size":100},"elements":[{"id":"88004c2","elType":"widget","settings":{"title":"Add Your Heading Text Here"},"elements":[],"widgetType":"heading"},{"id":"888e41","elType":"widget","settings":{"editor":"' . $editorContent . '","__globals__":{"text_color":"globals\/colors?id=accent"}},"elements":[],"widgetType":"text-editor"}],"isInner":false}],"isInner":false}]';
	}

	private function getPackage( $kind, $name ) {
		$package = $this->getMockBuilder( '\WPML_Package' )->getMock();
		$package->kind_slug = strtolower( $kind );
		$package->kind      = strtoupper( $kind );
		$package->name      = $name;

		return $package;
	}
}
