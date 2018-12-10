<?php

/**
 * Class Test_WPML_PB_Fix_Maintenance_Query_Factory
 *
 * @group wpmlcore-5239
 */
class Test_WPML_PB_Fix_Maintenance_Query_Factory extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_runs_on_front_end_requests() {
		$subject = new WPML_PB_Fix_Maintenance_Query_Factory();
		$this->assertInstanceOf( 'IWPML_Frontend_Action_Loader', $subject );
	}

	/**
	 * @test
	 */
	public function it_returns_instance_of_fix_maintenance_query() {
		$subject = new WPML_PB_Fix_Maintenance_Query_Factory();
		$this->assertInstanceOf( 'WPML_PB_Fix_Maintenance_Query', $subject->create() );
	}
}