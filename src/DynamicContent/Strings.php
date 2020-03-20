<?php

namespace WPML\PB\Elementor\DynamicContent;

use WPML\Collect\Support\Collection;
use WPML_PB_String;

class Strings {

	const SETTINGS_REGEX        = '/settings="(.*?(?="]))/';
	const NAME_PREFIX           = 'dynamic';
	const DELIMITER             = '-';
	const TRANSLATABLE_SETTINGS = [
		'before',
		'after',
		'fallback',
	];

	/**
	 * Remove the strings overwritten with dynamic content
	 * and add the extra strings "before", "after" and "fallback".
	 *
	 * @param WPML_PB_String[] $strings
	 * @param string            $nodeId
	 * @param array             $element
	 *
	 * @return WPML_PB_String[]
	 */
	public static function filter( array $strings, $nodeId, array $element ) {

		$dynamicFields = wpml_collect( isset( $element['settings']['__dynamic__'] ) ? $element['settings']['__dynamic__'] : [] );

		$getStringKey = function ( WPML_PB_String $string ) {
			return explode( '-', $string->get_name() )[0];
		};

		$updateFromDynamicFields = function( WPML_PB_String $string, $fieldKey ) use ( &$dynamicFields, $nodeId ) {
			if ( $dynamicFields->has( $fieldKey ) ) {
				return self::addBeforeAfterAndFallback( wpml_collect( [ $fieldKey => $dynamicFields->pull( $fieldKey ) ] ), $nodeId );
			}

			return $string;
		};

		return wpml_collect( $strings )
			->keyBy( $getStringKey )
			->map( $updateFromDynamicFields )
			->merge( self::addBeforeAfterAndFallback( $dynamicFields, $nodeId ) )
			->flatten()
			->toArray();
	}

	private static function addBeforeAfterAndFallback( Collection $dynamicFields, $nodeId ) {
		$dynamicFieldToSettingStrings = function( $dynamicTag, $tagKey ) use ( $nodeId ) {
			preg_match( self::SETTINGS_REGEX, $dynamicTag, $matches );

			$isTranslatableSetting = function( $value, $settingField ) {
				return in_array( $settingField, self::TRANSLATABLE_SETTINGS );
			};

			$buildStringFromSetting = function( $value, $settingField ) use ( $nodeId, $tagKey ) {
				return new WPML_PB_String(
					$value,
					self::getStringName( $nodeId, $tagKey, $settingField ),
					sprintf( __( 'Dynamic content string: %s', 'sitepress' ), $tagKey ),
					'LINE'
				);
			};

			return wpml_collect( isset( $matches[1] ) ? self::decodeSettings( $matches[1] ) : [] )
				->filter( $isTranslatableSetting )
				->map( $buildStringFromSetting );
		};
		
		return $dynamicFields->map( $dynamicFieldToSettingStrings );
	}

	/**
	 * @param array          $element
	 * @param WPML_PB_String $string
	 *
	 * @return array
	 */
	public static function updateNode( array $element, WPML_PB_String $string ) {
		$stringNameParts = explode( self::DELIMITER, $string->get_name() );

		if ( count( $stringNameParts ) !== 4 || self::NAME_PREFIX !== $stringNameParts[0] ) {
			return $element;
		}

		list( , , $dynamicField, $settingField ) = $stringNameParts;

		if ( ! isset( $element['settings']['__dynamic__'][ $dynamicField ] ) ) {
			return $element;
		}

		$replaceSettingStrings = function( array $matches ) use ( $string, $settingField ) {
			$settings                  = self::decodeSettings( $matches[1] );
			$settings[ $settingField ] = $string->get_value();
			$replace                   = urlencode( json_encode( $settings ) );

			return str_replace( $matches[1], $replace, $matches[0] );

		};

		$element['settings']['__dynamic__'][ $dynamicField ] = preg_replace_callback(
			self::SETTINGS_REGEX,
			$replaceSettingStrings,
			$element['settings']['__dynamic__'][ $dynamicField ]
		);

		return $element;
	}

	/**
	 * @param string $settingsString
	 *
	 * @return array
	 */
	private static function decodeSettings( $settingsString ) {
		return json_decode( urldecode( $settingsString ), true );
	}

	/**
	 * @param string $nodeId
	 * @param string $tagKey
	 * @param string $settingField
	 *
	 * @return string
	 */
	public static function getStringName( $nodeId, $tagKey, $settingField ) {
		return self::NAME_PREFIX . self::DELIMITER. $nodeId . self::DELIMITER . $tagKey . self::DELIMITER . $settingField;
	}
}