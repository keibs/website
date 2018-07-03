<?php
namespace ProteusThemes\WeatherWidget;

/**
 * Interface for weather API classes.
 *
 * @package adrenaline-pt
 */

interface WeatherApiInterface {
	// Speed conversion: meter per second to kilometers per hour.
	const MS_TO_KMH = 3.6;

	// Speed conversion: meter per second to knots.
	const MS_TO_KTS = 1.94384;

	// Speed conversion: meter per second to miles per hour.
	const MS_TO_MPH = 2.23694;

	public function prepare_data_for_client();
	public function get_data_from_api();
}
