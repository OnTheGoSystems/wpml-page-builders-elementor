<?php

/**
 * Class Test_WPML_Elementor_Media_Translation_Factory
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 *
 * @group wpmlmedia-511
 */
class Test_WPML_Elementor_Media_Translation_Factory extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_creates_instance_of_media_translation_when_media_classes_are_present() {
		$subject = new WPML_Elementor_Media_Translation_Factory();

		\Mockery::mock( 'overload:WPML_Media_Image_Translate' );
		\Mockery::mock( 'overload:WPML_Media_Attachment_By_URL_Factory' );
		\Mockery::mock( 'overload:WPML_Translation_Element_Factory' );

		$this->assertInstanceOf( 'WPML_Elementor_Media_Translation', $subject->create() );
	}

	/**
	 * @test
	 */
	public function it_returns_null_when_media_classes_are_not_present() {
		$subject = new WPML_Elementor_Media_Translation_Factory();

		$this->assertNull( $subject->create() );
	}
}


if ( ! interface_exists( 'IWPML_Backend_Action_Loader' ) ) {
	interface IWPML_Backend_Action_Loader{
		
	}
}