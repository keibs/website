<?php
namespace ProteusThemes\WeatherWidget;

/**
 * Class for forecast.io API calls and methods.
 * UPDATE: forecast has now changed it's name to darksky.net.
 * So the endpoints have changed.
 *
 * @link https://developer.forecast.io/
 * @link https://darksky.net/dev/
 * @package adrenaline-pt
 */

class ForecastIoWrapper extends WeatherUtils implements WeatherApiInterface {
	const API_ENDPOINT = 'https://api.darksky.net/forecast/';

	private $api_exclude  = 'currently,minutly,hourly,flags';
	private $weather_data;
	private $api_key;
	private $latitude;
	private $longitude;
	private $weather_descriptions;
	private $short_day_strings;

	public function __construct( $api_key, $latitude, $longitude ) {
		// Set class variables.
		$this->api_key   = $api_key;
		$this->latitude  = $latitude;
		$this->longitude = $longitude;

		// Set weather description translations.
		$this->set_weather_description_strings();

		parent::__construct();
	}

	/**
	 * Prepare data returned from the weather API to be used in the client (widget).
	 *
	 * @param  array $weather_data The existing weather data.
	 * @return array               With filtered weather data.
	 */
	public function prepare_data_for_client( $weather_data = null ) {
		$data = $output = array();

		if ( ! is_array( $weather_data ) ) {
			$weather_data = $this->get_data_from_api();
		}

		// Loop through the daily forecast and create collect the needed data.
		if (
			is_array( $weather_data ) &&
			array_key_exists( 'daily', $weather_data ) &&
			array_key_exists( 'data', $weather_data['daily'] )
		) {
			$data = $this->loop_daily_data( $weather_data['daily']['data'], $weather_data['timezone'] );
		}

		// Prepare output array with success status and data.
		if ( ! empty( $data ) ) {
			$output['success'] = true;
			$output['data']    = $data;
		}
		else {
			$output['success'] = false;
			$output['data']    = esc_html__( 'Error: No data was retrieved from the weather API.', 'adrenaline-pt' );
		}

		return $output;
	}

	/**
	 * Loop through the daily data from the API and prepare the filtered data.
	 *
	 * @param  array $daily_data The daily data from the weather API.
	 * @param  string $timezone  The timezone in a string.
	 * @return array             The filtered daily data.
	 */
	public function loop_daily_data( $daily_data, $timezone ) {
		$filtered_data = array();

		foreach ( $daily_data as $day ) {
			$filtered_data[] = $this->single_day_data( $day, $timezone );
		}

		return $filtered_data;
	}

	/**
	 * Will process raw data from API and return standardized array of data for a single day.
	 *
	 * @param  array  $day             The weather data of a single day.
	 * @param  string $timezone_string The timezone in a string.
	 * @return array                   The prepared/parsed single day weather data.
	 */
	public function single_day_data( $day, $timezone_string ) {
		// Create new DateTime object to set the timezone and get the correct date and time.
		$datetime = new \DateTime();
		$timezone = new \DateTimeZone( $timezone_string );
		$datetime->setTimezone( $timezone );
		$datetime->setTimestamp( $day['time'] );

		// Temperature data.
		$temperature_data = array(
			'celsius' => array(
				'unit'  => '&deg;C',
				'value' => round( $day['temperatureMax'] ),
			),
			'fahrenheit' => array(
				'unit'  => '&deg;F',
				'value' => round( self::convert_temperature_from_celsius_to_fahrenheit( $day['temperatureMax'] ) ),
			),
		);

		// Wind data.
		$wind_data = array(
			'meters_per_second' => array(
				'unit'  => 'm/s',
				'value' => round( $day['windSpeed'] ),
			),
			'kilometers_per_hour' => array(
				'unit'  => 'km/h',
				'value' => round( self::convert_speed_to( $day['windSpeed'], 'km/h' ) ),
			),
			'knots' => array(
				'unit'  => 'kts',
				'value' => round( self::convert_speed_to( $day['windSpeed'], 'kts' ) ),
			),
			'miles_per_hour' => array(
				'unit'  => 'mph',
				'value' => round( self::convert_speed_to( $day['windSpeed'], 'mph' ) ),
			),
		);

		return array(
			'timestamp'             => $day['time'],
			'timezone'              => $datetime->getTimezone()->getName(),
			'full_date_time'        => $datetime->format( 'l jS \of F Y h:i:s A' ),
			'day_of_the_week_long'  => $datetime->format( 'l' ),
			'day_of_the_week_short' => $this->get_short_day_string_translation( $datetime->format( 'D' ) ),
			'icon'                  => $this->get_correct_icon( $day['icon'] ),
			'weather_in_words'      => $this->get_weather_description_from_icon( $day['icon'] ),
			'temperature_max'       => $temperature_data,
			'wind_speed'            => $wind_data,
			'wind_direction'        => $day['windBearing'],
		);
	}

	/**
	 * Retrieve data from the weather API.
	 *
	 * @return object Object with all weather data.
	 */
	public function get_data_from_api() {
		// Build the request url.
		$request_url = self::API_ENDPOINT .
			$this->api_key . '/' .
			$this->latitude . ',' . $this->longitude .
			'?units=si&exclude=' . $this->api_exclude;

		// Retrieve the data via the request url.
		$response = wp_remote_get( $request_url );

		// Return false if something went wrong.
		if ( is_wp_error( $response ) ) {
			return false;
		}

		// Return associative array with weather data.
		return json_decode( $response['body'], true );
	}

	/**
	 * Get weather description from the icon code retrieved from weather API.
	 *
	 * @param  string $icon_value Icon value (rain, clear-day,...).
	 * @return string The state of the weather in few words.
	 */
	public function get_weather_description_from_icon( $icon_value = 'cloudy' ) {
		return $this->weather_descriptions[ $icon_value ];
	}

	/**
	 * Set the weather description texts, so that they can be translated.
	 * Note: replace "night" with "day", since we are using the daily forecasts.
	 */
	public function set_weather_description_strings() {
		$this->weather_descriptions = array(
			'clear-day'           => esc_html__( 'Clear day', 'adrenaline-pt' ),
			'clear-night'         => esc_html__( 'Clear day', 'adrenaline-pt' ),
			'rain'                => esc_html__( 'Rain', 'adrenaline-pt' ),
			'snow'                => esc_html__( 'Snow', 'adrenaline-pt' ),
			'sleet'               => esc_html__( 'Sleet', 'adrenaline-pt' ),
			'wind'                => esc_html__( 'Windy', 'adrenaline-pt' ),
			'fog'                 => esc_html__( 'Fog', 'adrenaline-pt' ),
			'cloudy'              => esc_html__( 'Cloudy', 'adrenaline-pt' ),
			'hail'                => esc_html__( 'Hail', 'adrenaline-pt' ),
			'thunderstorm'        => esc_html__( 'Thunderstorm', 'adrenaline-pt' ),
			'tornado'             => esc_html__( 'Tornado', 'adrenaline-pt' ),
			'partly-cloudy-day'   => esc_html__( 'Partly cloudy', 'adrenaline-pt' ),
			'partly-cloudy-night' => esc_html__( 'Partly cloudy', 'adrenaline-pt' ),
		);
	}

	/**
	 * Get the correct icon string (remove the "night" icons, since we are using the daily forecast).
	 * Note: we don't know, how "partly-cloudy-night" or "clear-night", can be a valid daily forecasts from the API.
	 *
	 * @param  string $icon The raw icon string from the API.
	 * @return string       The updated icon string.
	 */
	public function get_correct_icon( $icon ) {
		// Replace 'night' with 'day' in the icon name.
		return str_replace( 'night' , 'day', $icon );
	}
}
