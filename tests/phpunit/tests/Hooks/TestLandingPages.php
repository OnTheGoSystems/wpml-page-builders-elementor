<?php

namespace WPML\PB\Elementor\Hooks;

/**
 * @group hooks
 * @group landing-pages
 */
class TestLandingPages extends \OTGS_TestCase {

	/**
	 * @test
	 */
	public function itShouldAddHooksWithDefaultPermalinks() {
		\WP_Mock::userFunction( 'get_option' )->with( 'permalink_structure' )->andReturn( '' );

		$subject = $this->getSubject();
		\WP_Mock::expectFilterNotAdded( 'post_type_link', [ $subject, 'adjustLink' ], PHP_INT_MAX, 2 );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function itShouldAddHooksWithCustomPermalinks() {
		\WP_Mock::userFunction( 'get_option' )->with( 'permalink_structure' )->andReturn( 'some custom permalinks' );

		$subject = $this->getSubject();
		\WP_Mock::expectFilterAdded( 'post_type_link', [ $subject, 'adjustLink' ], PHP_INT_MAX, 2 );
		$subject->add_hooks();
	}

	/**
	 * @test
	 * @dataProvider dpShouldNotFilterLinks
	 *
	 * @param \WP_Post $post
	 */
	public function itShouldNotFilterLinks( $post ) {
		$postUrl = 'https://example.com/city/london/';

		$this->assertEquals(
			$postUrl,
			$this->getSubject()->adjustLink( $postUrl, $post )
		);
	}

	public function dpShouldNotFilterLinks() {
		return [
			'not a landing page' => [ $this->getPost( 123, 'city', 'publish' ) ],
			'not published'      => [ $this->getPost( 123, LandingPages::POST_TYPE, 'draft' ) ],
		];
	}

	/**
	 * @test
	 */
	public function itShouldFilterLinks() {
		$homeUrl       = 'https://example.com';
		$postUrl       = $homeUrl . '/my-landing-page/';
		$post          = $this->getPost( 123, LandingPages::POST_TYPE, 'publish','translated-landing-page' );
		$postLang      = 'fr';
		$translatedUrl = $homeUrl . '/' . $postLang . '/' . $post->post_name;

		\WP_Mock::userFunction( 'get_home_url' )->andReturn( $homeUrl );
		\WP_Mock::userFunction( 'wp_parse_url', [
			'return' => function( $url ) { return parse_url( $url ); },
		] );
		\WP_Mock::userFunction( 'trailingslashit', [
			'return' => function( $value ) { return $value . '/'; },
		] );

		$sitepress = $this->getSitepress();
		$sitepress->method( 'get_language_for_element' )
			->with( $post->ID, 'post_' . LandingPages::POST_TYPE )
			->willReturn( $postLang );
		$sitepress->method( 'convert_url' )
			->with( $homeUrl . '/' . $post->post_name . '/' )
			->willReturn( $translatedUrl );

		$subject = $this->getSubject( $sitepress );

		$this->assertEquals(
			$translatedUrl,
			$subject->adjustLink( $postUrl, $post )
		);
	}

	private function getSubject( $sitepress = null ) {
		$sitepress = $sitepress ?: $this->getSitepress();
		return new LandingPages( $sitepress );
	}

	private function getSitepress() {
		return $this->getMockBuilder( \SitePress::class )
			->setMethods( [
				'get_language_for_element',
				'convert_url',
			] )
			->getMock();
	}

	private function getPost( $id, $type, $status = 'publish', $name = 'something' ) {
		$post              = $this->getMockBuilder( \WP_Post::class )->getMock();
		$post->ID          = $id;
		$post->post_type   = $type;
		$post->post_status = $status;
		$post->post_name   = $name;

		return $post;
	}
}
