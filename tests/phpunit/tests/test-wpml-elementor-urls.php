<?php

class Test_WPML_Elementor_URLs extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_adds_hooks_when_domains_exist() {

		$subject = new WPML_Elementor_URLs(
			\Mockery::mock( 'WPML_Translation_Element_Factory' ),
			\Mockery::mock( 'WPML_Language_Domains' ),
			$this->get_sitepress_mock()
		);

		$this->expectFilterAdded( 'elementor/document/urls/edit', array(
			$subject,
			'adjust_edit_with_elementor_url'
		), 10, 2 );

		$subject->add_hooks();

	}

	/**
	 * @test
	 */
	public function it_does_not_add_hooks_when_domains_do_not_exist() {

		$subject = new WPML_Elementor_URLs(
			\Mockery::mock( 'WPML_Translation_Element_Factory' ),
			null,
			$this->get_sitepress_mock()
		);

		$this->expectFilterAdded( 'elementor/document/urls/edit', array(
			$subject,
			'adjust_edit_with_elementor_url'
		), 10, 2, 0 );

		$subject->add_hooks();

	}

	/**
	 * @test
	 */
	public function it_adjusts_url_for_domain() {

		$original_url   = 'http://my-site.com/wp-admin/post.php?post=6&action=elementor';
		$translated_url = 'http://fr.my-site.com/wp-admin/post.php?post=6&action=elementor';

		$post = (object) array( 'ID' => 123 );

		$post_element = \Mockery::mock( 'WPML_Post_Element' );
		$post_element->shouldReceive( 'get_language_code' )->andReturn( 'fr' );

		$element_factory = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$element_factory->shouldReceive( 'create_post' )
		                ->with( $post->ID )
		                ->andReturn( $post_element );

		$domains = \Mockery::mock( 'WPML_Language_Domains' );
		$domains->shouldReceive( 'get' )->with( 'fr' )->andReturn( 'fr.my-site.com' );

		$subject = new WPML_Elementor_URLs(
			$element_factory,
			$domains,
			$this->get_sitepress_mock()
		);

		$elementor_document = \Mockery::mock( 'Elementor_Document' ); // Note: this is not the real class name
		$elementor_document->shouldReceive( 'get_main_post' )->andReturn( $post );

		\WP_Mock::userFunction( 'wpml_parse_url',
			array(
				'args'   => $original_url,
				'return' => parse_url( $original_url ),
			)
		);
		$this->assertEquals( $translated_url, $subject->adjust_edit_with_elementor_url( $original_url, $elementor_document ) );
	}

	/**
	 * @test
	 */
	public function it_adjusts_url_for_domain_using_current_language_if_element_has_no_language() {
		$original_url   = 'http://my-site.com/wp-admin/post.php?post=6&action=elementor';
		$translated_url = 'http://fr.my-site.com/wp-admin/post.php?post=6&action=elementor';

		$post = (object) array( 'ID' => 123 );

		$post_element = \Mockery::mock( 'WPML_Post_Element' );
		$post_element->shouldReceive( 'get_language_code' )->andReturn( '' );

		$sitepress = $this->get_sitepress_mock();
		$sitepress->method( 'get_current_language' )
			->willReturn( 'fr' );

		$element_factory = \Mockery::mock( 'WPML_Translation_Element_Factory' );
		$element_factory->shouldReceive( 'create_post' )
		                ->with( $post->ID )
		                ->andReturn( $post_element );

		$domains = \Mockery::mock( 'WPML_Language_Domains' );
		$domains->shouldReceive( 'get' )->with( 'fr' )->andReturn( 'fr.my-site.com' );

		$subject = new WPML_Elementor_URLs(
			$element_factory,
			$domains,
			$sitepress
		);

		$elementor_document = \Mockery::mock( 'Elementor_Document' ); // Note: this is not the real class name
		$elementor_document->shouldReceive( 'get_main_post' )->andReturn( $post );

		\WP_Mock::userFunction( 'wpml_parse_url',
			array(
				'args'   => $original_url,
				'return' => parse_url( $original_url ),
			)
		);
		$this->assertEquals( $translated_url, $subject->adjust_edit_with_elementor_url( $original_url, $elementor_document ) );
	}

	private function get_sitepress_mock() {
		return $this->getMockBuilder( 'SitePress' )
			->setMethods( array( 'get_current_language' ) )
			->disableOriginalConstructor()
			->getMock();
	}
}