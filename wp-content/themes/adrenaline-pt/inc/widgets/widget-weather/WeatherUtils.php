<?php
namespace ProteusThemes\WeatherWidget;

/**
 * Abstract class for weather APIs
 *
 * @package adrenaline-pt
 */

abstract class WeatherUtils {
	const ROUND_TO_DECIMAL = 1;

	private $short_day_strings;

	public function __construct() {
		// Set short day string translations.
		$this->short_day_strings = array(
			'Mon' => esc_html__( 'Mon', 'adrenaline-pt' ),
			'Tue' => esc_html__( 'Tue', 'adrenaline-pt' ),
			'Wed' => esc_html__( 'Wed', 'adrenaline-pt' ),
			'Thu' => esc_html__( 'Thu', 'adrenaline-pt' ),
			'Fri' => esc_html__( 'Fri', 'adrenaline-pt' ),
			'Sat' => esc_html__( 'Sat', 'adrenaline-pt' ),
			'Sun' => esc_html__( 'Sun', 'adrenaline-pt' ),
		);
	}

	/**
	 * Get translated day name (3 characters long ).
	 *
	 * @param  string $day_short Short day name in 3 characters.
	 * @return string Translated day name in 3 characters.
	 */
	public function get_short_day_string_translation( $day_short ) {
		return $this->short_day_strings[ $day_short ];
	}

	/**
	 * Convert Temperature from Celsius to Fahrenheit.
	 *
	 * @param  float $temp_celsius Temperature in degrees Celsius.
	 * @return float The temperature in degrees Fahrenheit.
	 */
	public static function convert_temperature_from_celsius_to_fahrenheit( $temp_celsius ) {
		return $temp_celsius * ( 9 / 5 ) + 32;
	}

	/**
	 * Convert wind speed from m/s to km/h.
	 *
	 * @param  float $speed_meter_second Speed in m/s.
	 * @param  string $new_unit          Which unit it should be converting to..
	 * @return float                     Speed in selected unit.
	 */
	public static function convert_speed_to( $speed_meter_second, $new_unit = 'kts' ) {
		switch ( $new_unit ) {
			case 'mph':
				$multipier = WeatherApiInterface::MS_TO_MPH;
				break;

			case 'km/h':
				$multipier = WeatherApiInterface::MS_TO_KMH;
				break;

			default:
				$multipier = WeatherApiInterface::MS_TO_KTS;
				break;
		}

		return round( $speed_meter_second * $multipier, self::ROUND_TO_DECIMAL );
	}
}
