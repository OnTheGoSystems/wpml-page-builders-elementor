<?php

abstract class WPML_PB_TestCase2 extends OTGS_TestCase {

	function setUp() {
		parent::setUp();

		if ( ! defined( 'ICL_TM_COMPLETE' ) ) {
			define( 'ICL_TM_COMPLETE', 10 );
		}
	}

	public function test_dummy() {

	}

	protected function get_factory( $wpdb, $sitepress ) {
		$factory = new WPML_PB_Factory( $wpdb, $sitepress );

		return $factory;
	}

	protected function get_shortcode_strategy( WPML_PB_Factory $factory, $encoding = '' ) {
		$strategy = new WPML_PB_Shortcode_Strategy();
		$strategy->add_shortcodes(
			array(
				array(
					'tag'        => array(
						'value'    => 'vc_column_text',
						'encoding' => $encoding,
					),
					'attributes' => array(
						array( 'value' => 'text', 'encoding' => $encoding ),
						array( 'value' => 'heading', 'encoding' => $encoding ),
						array( 'value' => 'title', 'encoding' => $encoding ),
					),
				),
				array(
					'tag'        => array(
						'value'    => 'vc_text_separator',
						'encoding' => $encoding,
					),
					'attributes' => array(
						array( 'value' => 'text', 'encoding' => $encoding ),
						array( 'value' => 'heading', 'encoding' => $encoding ),
						array( 'value' => 'title', 'encoding' => $encoding ),
					),
				),
				array(
					'tag' => array(
						'value'    => 'vc_message',
						'encoding' => $encoding,
					),
				),
			)
		);

		$strategy->set_factory( $factory );
		return $strategy;
	}

	protected function get_api_hooks_strategy( WPML_PB_Factory $factory ) {
		$strategy = new WPML_PB_API_Hooks_Strategy( 'Layout' );
		$strategy->set_factory( $factory );
		return $strategy;
	}

	protected function get_post_and_package( $name = '' ) {
		if ( ! $name ) {
			$name = rand_str();
		}
		$post_id = rand();

		$post = $this->get_post_stub();
		$post->ID = $post_id;

		$package = array(
			'kind'    => $name,
			'name'    => $post_id,
			'title'   => 'Page Builder Page ' . $post_id,
			'post_id' => $post_id,
		);

		return array( $name, $post, $package );
	}

	private function get_post_stub() {
		return $this->getMockBuilder( 'WP_Post' )
					->disableOriginalConstructor()
					->getMock();
	}

	/**
	 * @return array
	 */
	protected function get_dummy_ls_languages() {
		return array(
			'en' => array(
				'code'             => 'en',
				'id'               => '1',
				'native_name'      => 'English',
				'major'            => '1',
				'active'           => '1',
				'default_locale'   => 'en_US',
				'encode_url'       => '0',
				'tag'              => 'en',
				'translated_name'  => 'English',
				'display_name'     => 'English',
				'url'              => 'http://example.org',
				'country_flag_url' => 'http://example.org/wp-content/plugins/sitepress-multilingual-cms/res/flags/en.png',
				'language_code'    => 'en',
			),
			'fr' => array(
				'code'             => 'fr',
				'id'               => '4',
				'native_name'      => 'Français',
				'major'            => '1',
				'active'           => 0,
				'default_locale'   => 'fr_FR',
				'encode_url'       => '0',
				'tag'              => 'fr',
				'translated_name'  => 'French',
				'display_name'     => 'French',
				'url'              => 'http://example.org?lang=fr',
				'country_flag_url' => 'http://example.org/wp-content/plugins/sitepress-multilingual-cms/res/flags/fr.png',
				'language_code'    => 'fr',
			),
			'de' => array(
				'code'             => 'de',
				'id'               => '3',
				'native_name'      => 'Deutsch',
				'major'            => '1',
				'active'           => 0,
				'default_locale'   => 'de_DE',
				'encode_url'       => '0',
				'tag'              => 'de',
				'translated_name'  => 'German',
				'display_name'     => 'German',
				'url'              => 'http://example.org?lang=de',
				'country_flag_url' => 'http://example.org/wp-content/plugins/sitepress-multilingual-cms/res/flags/de.png',
				'language_code'    => 'de',
			),
			'it' => array(
				'code'             => 'it',
				'id'               => '27',
				'native_name'      => 'Italiano',
				'major'            => '1',
				'active'           => 0,
				'default_locale'   => 'it_IT',
				'encode_url'       => '0',
				'tag'              => 'it',
				'translated_name'  => 'Italian',
				'display_name'     => 'Italian',
				'url'              => 'http://example.org?lang=it',
				'country_flag_url' => 'http://example.org/wp-content/plugins/sitepress-multilingual-cms/res/flags/it.png',
				'language_code'    => 'it',
			),
			'ru' => array(
				'code'             => 'ru',
				'id'               => '46',
				'native_name'      => 'Русский',
				'major'            => '1',
				'active'           => 0,
				'default_locale'   => 'ru_RU',
				'encode_url'       => '0',
				'tag'              => 'ru',
				'translated_name'  => 'Russian',
				'display_name'     => 'Russian',
				'url'              => 'http://example.org?lang=ru',
				'country_flag_url' => 'http://example.org/wp-content/plugins/sitepress-multilingual-cms/res/flags/ru.png',
				'language_code'    => 'ru',
			),
			'es' => array(
				'code'             => 'es',
				'id'               => '2',
				'native_name'      => 'Español',
				'major'            => '1',
				'active'           => 0,
				'default_locale'   => 'es_ES',
				'encode_url'       => '0',
				'tag'              => 'es',
				'translated_name'  => 'Spanish',
				'display_name'     => 'Spanish',
				'url'              => 'http://example.org?lang=es',
				'country_flag_url' => 'http://example.org/wp-content/plugins/sitepress-multilingual-cms/res/flags/es.png',
				'language_code'    => 'es',
			),
		);
	}
}

