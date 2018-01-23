<?php

/**
 * Class Test_WPML_Elementor_DB
 *
 * @group elementor-third-party
 * @group wpmlst-1535
 * @group elementor
 */
class Test_WPML_Elementor_DB extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_saves_plain_text() {
		$elementor_db = $this->getMockBuilder( '\Elementor\DB' )
		                     ->setMethods( array( 'save_plain_text' ) )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$post_id = mt_rand( 1, 10 );

		$elementor_db->expects( $this->once() )
		             ->method( 'save_plain_text' )
		             ->with( $post_id );

		$subject = new WPML_Elementor_DB( $elementor_db );
		$subject->save_plain_text( $post_id );
	}
}
