<?php

/**
 * Class Test_WPML_Elementor_WooCommerce_Hooks
 *
 * @group wpmlcore-6209
 */
class Test_WPML_Elementor_WooCommerce_Hooks extends OTGS_TestCase {

	public function tearDown() {
		parent::tearDown();

		unset( $_POST['action'] );
	}

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		$subject = new WPML_Elementor_WooCommerce_Hooks();
		\WP_Mock::expectFilterAdded( 'pre_get_posts', array( $subject, 'do_not_suppress_filters_on_product_widget' ) );
		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function it_should_not_change_if_post_type_is_NOT_in_query_vars() {
		$subject = new WPML_Elementor_WooCommerce_Hooks();

		$query = $this->get_wp_query();

		$_POST['action'] = 'elementor_ajax';

		$query->query_vars[ 'suppress_filters' ] = true;

		$filtered_query = $subject->do_not_suppress_filters_on_product_widget( $query );

		$this->assertTrue( $filtered_query->query_vars['suppress_filters'] );
	}

	/**
	 * @test
	 */
	public function it_should_not_change_if_post_type_is_not_product() {
		$subject = new WPML_Elementor_WooCommerce_Hooks();

		$query = $this->get_wp_query();

		$_POST['action'] = 'elementor_ajax';

		$query->query_vars[ 'suppress_filters' ] = true;
		$query->query_vars[ 'post_type' ] = 'something';

		$filtered_query = $subject->do_not_suppress_filters_on_product_widget( $query );

		$this->assertTrue( $filtered_query->query_vars['suppress_filters'] );
	}

	/**
	 * @test
	 */
	public function it_should_not_change_if_action_global_is_not_set() {
		$subject = new WPML_Elementor_WooCommerce_Hooks();

		$query = $this->get_wp_query();

		$query->query_vars[ 'suppress_filters' ] = true;
		$query->query_vars[ 'post_type' ] = 'product';

		$filtered_query = $subject->do_not_suppress_filters_on_product_widget( $query );

		$this->assertTrue( $filtered_query->query_vars['suppress_filters'] );
	}

	/**
	 * @test
	 */
	public function it_should_not_change_if_action_global_is_different_than_the_one_for_adding_widgets() {
		$subject = new WPML_Elementor_WooCommerce_Hooks();

		$query = $this->get_wp_query();

		$query->query_vars[ 'suppress_filters' ] = true;
		$query->query_vars[ 'post_type' ] = 'product';

		$_POST['action'] = 'some-other-action';

		$filtered_query = $subject->do_not_suppress_filters_on_product_widget( $query );

		$this->assertTrue( $filtered_query->query_vars['suppress_filters'] );
	}

	/**
	 * @test
	 */
	public function it_should_set_suppress_filters_to_false() {
		$subject = new WPML_Elementor_WooCommerce_Hooks();

		$query = $this->get_wp_query();

		$query->query_vars[ 'suppress_filters' ] = true;
		$query->query_vars[ 'post_type' ] = 'product';

		$_POST['action'] = 'elementor_ajax';

		$filtered_query = $subject->do_not_suppress_filters_on_product_widget( $query );

		$this->assertFalse( $filtered_query->query_vars['suppress_filters'] );
	}

	/**
	 * @return \WP_Query|\PHPUnit_Framework_MockObject_MockObject
	 */
	private function get_wp_query() {
		return $this->getMockBuilder( 'WP_Query' )
					->disableOriginalConstructor()
					->getMock();
	}
}
