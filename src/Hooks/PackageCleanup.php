<?php

namespace WPML\PB\Elementor\Hooks;

use WPML\FP\Fns;
use WPML\FP\Maybe;
use WPML\FP\Obj;
use WPML\FP\Relation;
use function WPML\FP\pipe;

class PackageCleanup implements \IWPML_Backend_Action, \IWPML_Frontend_Action, \IWPML_DIC_Action {

	/** @see \WPML\PB\Shutdown\Hooks::PRIORITY_REGISTER_STRINGS */
	const PRIORITY_BEFORE_REGISTER_STRINGS = 5;

	/** @var \WPML\Collect\Support\Collection */
	private $savedPosts;

	public function __construct() {
		$this->savedPosts = wpml_collect();
	}

	public function add_hooks() {
		add_action( 'save_post', [ $this, 'addSavedPost' ], 10, 2 );
		add_action( 'shutdown', [ $this, 'cleanupGutenbergFootprint' ], self::PRIORITY_BEFORE_REGISTER_STRINGS );
	}

	/**
	 * @param int      $postId
	 * @param \WP_Post $post
	 */
	public function addSavedPost( $postId, $post ) {
		if ( ! wp_is_post_revision( $post ) ) {
			$this->savedPosts->put( $postId, $post );
		}
	}

	public function cleanupGutenbergFootprint() {
		remove_action( 'save_post', [ $this, 'addSavedPost' ], 10, 2 );
		$this->savedPosts->each( [ $this, 'cleanupGutenbergFootprintInPost' ] );
	}

	public function cleanupGutenbergFootprintInPost( $post ) {
		Maybe::of( $post )
			->filter( [ self::class, 'isEditedWithElementor' ] )
			->filter( [ self::class, 'hasGutenbergMarkup' ] )
			->map( Fns::tap( [ self::class, 'removeGutenbergMarkup' ] ) )
			->map( [ self::class, 'getGutenbergPackage' ] )
			->filter( Fns::identity() )
			->map( [ self::class, 'deletePackage' ] );
	}

	/**
	 * @param \WP_Post $post
	 */
	public static function isEditedWithElementor( $post ) {
		return 'builder' === get_post_meta( $post->ID, '_elementor_edit_mode', true );
	}

	/**
	 * @param \WP_Post $post
	 */
	public static function hasGutenbergMarkup( $post ) {
		return false !== strpos( $post->post_content, '<!-- wp:' );
	}

	/**
	 * @param \WP_Post $post
	 */
	public static function removeGutenbergMarkup( $post ) {
		$post->post_content = preg_replace( '(<!--[^<]*-->)', '', $post->post_content );
		wpml_update_escaped_post( (array) $post );
	}

	/**
	 * @param \WP_Post $post
	 */
	public static function getGutenbergPackage( $post ) {
		// $isGbPackage :: \WPML_Package -> bool
		$isGbPackage = pipe( Obj::prop( 'kind_slug' ), Relation::equals( 'gutenberg' ) );

		return wpml_collect( apply_filters( 'wpml_st_get_post_string_packages', [], $post->ID ) )
			->filter( $isGbPackage )
			->first();
	}

	/**
	 * @param \WPML_Package $package
	 */
	public static function deletePackage( $package ) {
		do_action( 'wpml_delete_package', $package->name, $package->kind );
	}
}
