<?php
namespace ProteusThemes\WeatherWidget;

/**
 * Class for main weather API.
 * Wrapper for other API provider classes.
 *
 * @package adrenaline-pt
 */

class WeatherApi {
	private $api_provider;
	private $api_key;
	private $latitude;
	private $longitude;

	public function __construct( $api_key, $latitude, $longitude ) {
		// Set class variables.
		$this->api_key   = $api_key;
		$this->latitude  = $latitude;
		$this->longitude = $longitude;

		// Set weather API provider.
		$this->api_provider = $this->set_api_provider();
	}

	/**
	 * Set the weather API provider used in this class.
	 *
	 * @return object Weather API provider object.
	 */
	private function set_api_provider() {
		return new ForecastIoWrapper( $this->api_key, $this->latitude, $this->longitude );
	}

	/**
	 * Get prepared data, which is ready for display.
	 *
	 * @return array Array with needed weather data.
	 */
	public function get_data() {
		return $this->api_provider->prepare_data_for_client();
	}
}
