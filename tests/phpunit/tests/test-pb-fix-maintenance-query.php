<?php

/**
 * Class Test_WPML_PB_Fix_Maintenance_Query
 *
 * @group wpmlcore-5239
 */
class Test_WPML_PB_Fix_Maintenance_Query extends OTGS_TestCase {

	/**
	 * @test
	 * @group wpmlcore-6425
	 */
	public function it_should_load_on_frontend() {
		$subject = new WPML_PB_Fix_Maintenance_Query();
		$this->assertInstanceOf( 'IWPML_Frontend_Action', $subject );
	}

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		$subject = new WPML_PB_Fix_Maintenance_Query();
		\WP_Mock::expectActionAdded( 'template_redirect', array( $subject, 'fix_global_query' ), 12 );
		$subject->add_hooks();
	}

	/**
	 * @test
	 * @group wpmlcore-6218
	 */
	public function it_does_not_fix_global_query_if_post_is_not_set() {
		$original_post = new stdClass();
		$template_post = new stdClass();
		$template_id   = 3;

		$post_backup      = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;
		$the_query_backup = isset( $GLOBALS['wp_the_query'] ) ? $GLOBALS['wp_the_query'] : null;
		$query_backup     = isset( $GLOBALS['wp_query'] ) ? $GLOBALS['wp_query'] : null;

		$GLOBALS['post']         = null;
		$GLOBALS['wp_the_query'] = $original_post;
		$GLOBALS['wp_query']     = $template_post;

		$subject = new WPML_PB_Fix_Maintenance_Query();

		\mockery::mock( 'overload:' . \Elementor\Maintenance_Mode::class )
		        ->shouldReceive( 'get' )
		        ->with( 'template_id' )
		        ->andReturn( $template_id );

		$subject->fix_global_query();
		$this->assertEquals( $original_post, $GLOBALS['wp_the_query'] );
		$this->assertEquals( $template_post, $GLOBALS['wp_query'] );

		$GLOBALS['post']         = $post_backup;
		$GLOBALS['wp_the_query'] = $the_query_backup;
		$GLOBALS['post']         = $post_backup;
		$GLOBALS['wp_query']     = $query_backup;
	}

	/**
	 * @test
	 */
	public function it_fixes_global_query() {
		$original_post = new stdClass();
		$template_post = new stdClass();
		$template_id   = 3;

		$post_backup      = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;
		$the_query_backup = isset( $GLOBALS['wp_the_query'] ) ? $GLOBALS['wp_the_query'] : null;
		$query_backup     = isset( $GLOBALS['wp_query'] ) ? $GLOBALS['wp_query'] : null;

		$GLOBALS['post']         = $template_post;
		$GLOBALS['post']->ID     = $template_id;
		$GLOBALS['wp_the_query'] = $original_post;
		$GLOBALS['wp_query']     = $template_post;

		$subject = new WPML_PB_Fix_Maintenance_Query();

		\mockery::mock( 'overload:' . \Elementor\Maintenance_Mode::class )
		        ->shouldReceive( 'get' )
		        ->with( 'template_id' )
		        ->andReturn( $template_id );

		$subject->fix_global_query();
		$this->assertEquals( $GLOBALS['wp_query'], $GLOBALS['wp_the_query'] );

		$GLOBALS['post']         = $post_backup;
		$GLOBALS['wp_the_query'] = $the_query_backup;
		$GLOBALS['post']         = $post_backup;
		$GLOBALS['wp_query']     = $query_backup;
	}
}
