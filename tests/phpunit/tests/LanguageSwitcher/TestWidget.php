<?php

namespace WPML\PB\Elementor\LanguageSwitcher;

/**
 * @group language-switcher
 */
class TestWidget extends \OTGS_TestCase {

	/**
	 * @see \Elementor\Elements_Manager::create_element_instance
	 *
	 * @test
	 */
	public function itShouldBeInstantiatedWithSameArgumentsAsBaseClass() {
		new Widget( [], [] );
	}

	/**
	 * @test
	 */
	public function itShouldGetName() {
		$adaptor = $this->getAdaptor();
		$adaptor->method( 'getName' )->willReturn( 'my name' );

		$subject = $this->getSubject( $adaptor );

		$this->assertEquals( 'my name', $subject->get_name() );
	}

	/**
	 * @test
	 */
	public function itShouldGetTitle() {
		$adaptor = $this->getAdaptor();
		$adaptor->method( 'getTitle' )->willReturn( 'my title' );

		$subject = $this->getSubject( $adaptor );

		$this->assertEquals( 'my title', $subject->get_title() );
	}

	/**
	 * @test
	 */
	public function itShouldGetIcon() {
		$adaptor = $this->getAdaptor();
		$adaptor->method( 'getIcon' )->willReturn( 'my icon' );

		$subject = $this->getSubject( $adaptor );

		$this->assertEquals( 'my icon', $subject->get_icon() );
	}

	/**
	 * @test
	 */
	public function itShouldGetCategories() {
		$categories = [ 'my category' ];

		$adaptor = $this->getAdaptor();
		$adaptor->method( 'getCategories' )->willReturn( $categories );

		$subject = $this->getSubject( $adaptor );

		$this->assertEquals( $categories, $subject->get_categories() );
	}

	private function getSubject( $adaptor ) {
		return new Widget( [], null, $adaptor );
	}

	private function getAdaptor() {
		return $this->getMockBuilder( WidgetAdaptor::class )
			->setMethods(
				[
					'getName',
					'getTitle',
					'getIcon',
					'getCategories',
				]
			)->disableOriginalConstructor()->getMock();
	}
}
