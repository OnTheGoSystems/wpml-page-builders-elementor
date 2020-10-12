<?php

class Test_WPML_Elementor_URLs extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_adds_hooks() {

		$subject = new WPML_Elementor_URLs(
			\Mockery::mock( 'WPML_Translation_Element_Factory' ),
			\Mockery::mock( 'IWPML_URL_Converter_Strategy' ),
			\Mockery::mock( 'IWPML_Current_Language' )
		);

		$this->expectFilterAdded( 'elementor/document/urls/edit', array(
			$subject,
			'adjust_edit_with_elementor_url'
		), 10, 2 );

		$this->expectFilterAdded( 'wpml_is_pagination_url_in_post', [
			$subject,
			'is_pagination_url'
		], 10, 3 );

		$this->expectFilterAdded( 'paginate_links', [
			$subject,
			'fix_pagination_link_with_language_param'
		], 10, 1 );

		$subject->add_hooks();

	}

	/**
	 * @test
	 */
	public function it_adjusts_url_for_domain() {

		$original_url   = 'http://my-site.com/wp-admin/post.php?post=6&action=elementor';
		$translated_url = 'http://fr.my-site.com/wp-admin/post.php?post=6&action=elementor';

		$post_language = 'fr';

		$post = (object) array( 'ID' => 123 );

		$post_element = \Mockery::mock( 'WPML_Post_Element' );
		$post_element->shouldReceive( 'get_language_code' )->andReturn( $post_language );

		$element_factory = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$element_factory->shouldReceive( 'create_post' )
		                ->with( $post->ID )
		                ->andReturn( $post_element );

		$language_converter = \Mockery::mock( 'IWPML_URL_Converter_Strategy' );
		$language_converter->shouldReceive( 'convert_admin_url_string' )
		                   ->with( $original_url, $post_language )
		                   ->andReturn( $translated_url );

		$current_language = \Mockery::mock( 'IWPML_Current_Language' );

		$subject = new WPML_Elementor_URLs(
			$element_factory,
			$language_converter,
			$current_language
		);

		$elementor_document = \Mockery::mock( 'Elementor_Document' ); // Note: this is not the real class name
		$elementor_document->shouldReceive( 'get_main_post' )->andReturn( $post );

		$this->assertEquals( $translated_url, $subject->adjust_edit_with_elementor_url( $original_url, $elementor_document ) );
	}

	/**
	 * @test
	 */
	public function it_adjusts_url_for_domain_using_current_language_if_element_has_no_language() {
		$original_url   = 'http://my-site.com/wp-admin/post.php?post=6&action=elementor';
		$translated_url = 'http://fr.my-site.com/wp-admin/post.php?post=6&action=elementor';

		$site_language = 'fr';

		$post = (object) array( 'ID' => 123 );

		$post_element = \Mockery::mock( 'WPML_Post_Element' );
		$post_element->shouldReceive( 'get_language_code' )->andReturn( '' );

		$element_factory = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$element_factory->shouldReceive( 'create_post' )
		                ->with( $post->ID )
		                ->andReturn( $post_element );

		$language_converter = \Mockery::mock( 'IWPML_URL_Converter_Strategy' );
		$language_converter->shouldReceive( 'convert_admin_url_string' )
		                   ->with( $original_url, $site_language )
		                   ->andReturn( $translated_url );

		$current_language = \Mockery::mock( 'IWPML_Current_Language' );
		$current_language->shouldReceive( 'get_current_language' )->andReturn( $site_language );

		$subject = $this->get_subject(
			$element_factory,
			$language_converter,
			$current_language
		);

		$elementor_document = \Mockery::mock( 'Elementor_Document' ); // Note: this is not the real class name
		$elementor_document->shouldReceive( 'get_main_post' )->andReturn( $post );

		$this->assertEquals( $translated_url, $subject->adjust_edit_with_elementor_url( $original_url, $elementor_document ) );
	}

	/**
	 * @test
	 */
	public function it_filters_pagination_in_post() {
		$path      = 'index.php/elementor-post/2/';
		$post_name = 'elementor-post';

		$element_factory    = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$language_converter = \Mockery::mock( 'IWPML_URL_Converter_Strategy' );
		$current_language   = \Mockery::mock( 'IWPML_Current_Language' );

		WP_Mock::userFunction( 'get_post_meta', [ 'return' => 'builder' ] );
		WP_Mock::userFunction( 'get_the_ID', [ 'return' => 1 ] );

		$subject = $this->get_subject(
			$element_factory,
			$language_converter,
			$current_language
		);

		$this->assertSame(
			true,
			$subject->is_pagination_url( false, $path, $post_name )
		);
	}

	/**
	 * @test
	 */
	public function it_fixes_pagination_link_with_langauge_param() {
		$post_name         = 'elementor-post';
		$lang              = 'de';
		$link_without_lang = "http://example.com/$post_name/2/";
		$link              = "http://example.com/$post_name/?lang=$lang/2/";
		$proper_link       = "http://example.com/$post_name/2/?lang=$lang";

		$element_factory    = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$language_converter = \Mockery::mock( 'IWPML_URL_Converter_Strategy' );
		$current_language   = \Mockery::mock( 'IWPML_Current_Language' );

		$current_language->shouldReceive( 'get_current_language' )->andReturn( $lang );

		$language_converter->shouldReceive( 'convert_url_string' )
		                   ->with( $link_without_lang, $lang )
		                   ->andReturn( $proper_link );

		WP_Mock::userFunction( 'get_post_meta', [ 'return' => 'builder' ] );
		WP_Mock::userFunction( 'get_the_ID', [ 'return' => 1 ] );
		WP_Mock::userFunction( 'get_post', [
			'return' => (object) [
				'ID'   => 1,
				'post_name' => $post_name
			]
		] );

		$subject = $this->get_subject(
			$element_factory,
			$language_converter,
			$current_language
		);

		$this->assertSame(
			$proper_link,
			$subject->fix_pagination_link_with_language_param( $link )
		);
	}

	private function get_subject( $element_factory, $language_converter, $current_language ) {
		return new WPML_Elementor_URLs(
			$element_factory,
			$language_converter,
			$current_language
		);
	}
}