<?php

/**
 * Class Test_WPML_Elementor_DB_Factory
 *
 * @group elementor-third-party
 * @group wpmlst-1535
 * @group elementor
 */
class Test_WPML_Elementor_DB_Factory extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_creates_instance_of_elementor_db() {
		$subject = new WPML_Elementor_DB_Factory();
		$this->assertInstanceOf( 'WPML_Elementor_DB', $subject->create() );
	}
}
