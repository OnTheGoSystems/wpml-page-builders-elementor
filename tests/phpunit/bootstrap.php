<?php
define( 'WPML_ST_SITE_URL', 'https://domain.tld' );

define( 'WPML_ST_TESTS_MAIN_FILE', __DIR__ . '/../../plugin.php' );
define( 'WPML_ST_PATH', dirname( WPML_ST_TESTS_MAIN_FILE ) );

/** ST constants */
define( 'WPML_ST_FOLDER', __DIR__ . '/../wpml-shared/' );
define( 'WPML_ST_URL', WPML_ST_SITE_URL . '/wp-content/plugins/wpml-string-translation' );
define( 'ICL_STRING_TRANSLATION_STRING_TRACKING_TYPE_SOURCE', 0 );
define( 'ICL_STRING_TRANSLATION_STRING_TRACKING_TYPE_PAGE', 1 );
define( 'ICL_STRING_TRANSLATION_COMPLETE', 10 );
define( 'ICL_STRING_TRANSLATION_NOT_TRANSLATED', 0 );
define( 'ICL_STRING_TRANSLATION_PARTIAL', 2 );
define( 'WPML_ST_VERSION', '2.5.2' );
define( 'WPML_STRING_TABLE_NAME_CONTEXT_LENGTH', 160 );

/** Core and add-ons constants */
define( 'ICL_PLUGIN_FOLDER', '' );
define( 'WPML_PLUGIN_PATH', '' );
define( 'ICL_PLUGIN_URL', '' );
define( 'ICL_TM_NOT_TRANSLATED', 0 );
define( 'ICL_TM_WAITING_FOR_TRANSLATOR', 1 );
define( 'ICL_TM_IN_PROGRESS', 2 );
define( 'ICL_TM_NEEDS_UPDATE', 3 );
define( 'ICL_TM_COMPLETE', 10 );
define( 'WPML_PLUGIN_FOLDER', __DIR__ . '/../wpml-shared/' );
define( 'WP_PLUGIN_DIR', realpath( __DIR__ . '/../../public/' ) );
define( 'WPMU_PLUGIN_DIR', __DIR__ . '/../../public/' );
define( 'WP_CONTENT_DIR', realpath( __DIR__ . '/../../../app/' ) );

define( 'WP_CONTENT_URL', WPML_ST_SITE_URL . '/wp-content' );
define( 'WP_PLUGIN_URL', WPML_ST_SITE_URL . '/' . WP_CONTENT_URL . '/plugins' );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', realpath(WPML_ST_PATH . '/../../../') );
}

define( 'ELEMENTOR_PATH', __DIR__ . '/../../vendor/elementor/elementor/' );
require_once ELEMENTOR_PATH . '/includes/autoloader.php';
Elementor\Autoloader::run();
// Load classes not included in Elementor's autoloader.
require_once ELEMENTOR_PATH . '/includes/managers/controls.php';
//require_once ELEMENTOR_PATH . '/includes/interfaces/scheme.php';
//require_once ELEMENTOR_PATH . '/includes/schemes/base.php';
//require_once ELEMENTOR_PATH . '/includes/schemes/color.php';
//require_once ELEMENTOR_PATH . '/includes/interfaces/group-control.php';
//require_once ELEMENTOR_PATH . '/includes/controls/groups/base.php';
//require_once ELEMENTOR_PATH . '/includes/controls/groups/typography.php';
require_once ELEMENTOR_PATH . '/core/base/base-object.php';
require_once ELEMENTOR_PATH . '/includes/base/controls-stack.php';
require_once ELEMENTOR_PATH . '/includes/base/element-base.php';
require_once ELEMENTOR_PATH . '/includes/base/widget-base.php';

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/otgs/unit-tests-framework/phpunit/bootstrap.php';
