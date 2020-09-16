<?php

namespace WPML\PB\Elementor\Modules;

/**
 * @group modules
 */
class TestModuleWithItemsFromConfig extends \OTGS_TestCase {

	/**
	 * @test
	 */
	public function itShouldGetData() {
		$itemsField = 'slides';

		$config = [
			[
				'field'       => 'title',
				'type'        => 'The slide title',
				'editor_type' => 'LINE',
			],
			'link' => [
				'field'       => 'url',
				'type'        => 'The slide url',
				'editor_type' => 'LINK',
			],
		];


		$subject = new ModuleWithItemsFromConfig( $itemsField, $config );

		$this->assertEquals( $itemsField, $subject->get_items_field() );

		$this->assertEquals(
			[ 'title', 'link' => [ 'url' ] ],
			$subject->get_fields()
		);

		// Title field
		$this->assertEquals( 'The slide title', $subject->get_title( 'title' ) );
		$this->assertEquals( 'LINE', $subject->get_editor_type( 'title' ) );

		// Link field
		$this->assertEquals( 'The slide url', $subject->get_title( 'url' ) );
		$this->assertEquals( 'LINK', $subject->get_editor_type( 'url' ) );
	}
}
