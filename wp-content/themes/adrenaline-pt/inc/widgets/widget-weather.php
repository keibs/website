<?php
/**
 * Weather Widget
 *
 * @package adrenaline-pt
 */

if ( ! class_exists( 'PW_Weather' ) ) {
	class PW_Weather extends WP_Widget {

		// Basic widget settings.
		function widget_id_base() { return 'weather'; }
		function widget_name() { return esc_html__( 'Weather', 'adrenaline-pt' ); }
		function widget_description() { return esc_html__( 'Weather widget for a given location.', 'adrenaline-pt' ); }
		function widget_class() { return 'widget-weather'; }

		public function __construct() {
			parent::__construct(
				'pw_' . $this->widget_id_base(),
				sprintf( 'ProteusThemes: %s', $this->widget_name() ),
				array(
					'description' => $this->widget_description(),
					'classname'   => $this->widget_class(),
				)
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args Widget arguments.
		 * @param array $instance Widget data.
		 */
		public function widget( $args, $instance ) {
			$api_key          = ! empty( $instance['api_key'] ) ? $instance['api_key'] : '';
			$location         = ! empty( $instance['location'] ) ? $instance['location'] : '';
			$latitude         = ! empty( $instance['latitude'] ) ? $instance['latitude'] : '';
			$longitude        = ! empty( $instance['longitude'] ) ? $instance['longitude'] : '';
			$temperature_unit = ! empty( $instance['temperature_unit'] ) ? $instance['temperature_unit'] : 'celsius';
			$link_url         = ! empty( $instance['link_url'] ) ? $instance['link_url'] : '';
			$forecast         = ! empty( $instance['forecast'] ) ? $instance['forecast'] : '3';
			$forecast         = 'none' === $forecast ? 0 : $forecast; // Select option fix -> 0 returns true for `empty`.

			echo $args['before_widget'];
			?>

			<div class="weather  js-weather" data-latitude="<?php echo esc_attr( $latitude ); ?>" data-longitude="<?php echo esc_attr( $longitude ); ?>" data-temperature-unit="<?php echo esc_attr( $temperature_unit ); ?>" data-forecast="<?php echo esc_attr( $forecast ); ?>" data-location="<?php echo esc_html( $location ); ?>" data-url="<?php echo esc_url( $link_url ); ?>">
				<div class="weather-current__container  js-weather-current"></div>
				<div class="weather-forecast__container  js-weather-forecast"></div>
			</div>

			<?php
			echo $args['after_widget'];
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @param array $new_instance The new options.
		 * @param array $old_instance The previous options.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();

			$instance['api_key']          = sanitize_text_field( $new_instance['api_key'] );
			$instance['location']         = sanitize_text_field( $new_instance['location'] );
			$instance['latitude']         = sanitize_text_field( $new_instance['latitude'] );
			$instance['longitude']        = sanitize_text_field( $new_instance['longitude'] );
			$instance['temperature_unit'] = sanitize_key( $new_instance['temperature_unit'] );
			$instance['forecast']         = sanitize_key( $new_instance['forecast'] );
			$instance['link_url']         = esc_url_raw( $new_instance['link_url'] );

			// Set WP option for API key, so it can be retrieved when needed in API requests.
			// In this way we don't expose the API key to the frontend (in HTML or JS).
			update_option( AdrenalineHelpers::create_location_key( 'pt_adrenaline_api_key', $instance['latitude'], $instance['longitude'] ), $instance['api_key'] );

			// Delete currently cached weather data for this location (API response), if any.
			delete_transient( AdrenalineHelpers::create_location_key( 'pt_adrenaline_weather_data', $instance['latitude'], $instance['longitude'] ) );

			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @param array $instance The widget options.
		 */
		public function form( $instance ) {
			$api_key          = ! empty( $instance['api_key'] ) ? $instance['api_key'] : '';
			$location         = ! empty( $instance['location'] ) ? $instance['location'] : '';
			$latitude         = ! empty( $instance['latitude'] ) ? $instance['latitude'] : '';
			$longitude        = ! empty( $instance['longitude'] ) ? $instance['longitude'] : '';
			$temperature_unit = ! empty( $instance['temperature_unit'] ) ? $instance['temperature_unit'] : 'celsius';
			$forecast         = ! empty( $instance['forecast'] ) ? $instance['forecast'] : '3';
			$link_url         = ! empty( $instance['link_url'] ) ? $instance['link_url'] : '';
		?>

			<div style="background-color: #eff8fb; border: 1px solid #5ac6ea; border-radius: 4px; padding: 20px; ">
				<strong><?php esc_html_e( 'Widget instructions:', 'adrenaline-pt' ); ?></strong>
				<ol>
					<li><strong><?php printf( esc_html__( '%1$sRegister at darksky.net/dev and get your API key%2$s.', 'adrenaline-pt' ), '<a href="https://darksky.net/dev/" target="_blank">', '</a>' ); ?></strong></li>
					<li><?php esc_html_e( 'Once you register, copy the API key to the field below.', 'adrenaline-pt' ); ?></li>
				</ol>
			</div>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'api_key' ) ); ?>"><?php esc_html_e( 'API key:', 'adrenaline-pt' ); ?></label>
				<br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'api_key' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'api_key' ) ); ?>" type="text" value="<?php echo esc_attr( $api_key ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'location' ) ); ?>"><?php esc_html_e( 'Location:', 'adrenaline-pt' ); ?></label>
				<br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'location' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'location' ) ); ?>" type="text" value="<?php echo esc_attr( $location ); ?>" />
			</p>
			<p>
				<strong><?php esc_html_e( 'Location coordinates', 'adrenaline-pt' ); ?></strong>
				<br>
				<label for="<?php echo esc_attr( $this->get_field_id( 'latitude' ) ); ?>"><?php esc_html_e( 'Latitude:', 'adrenaline-pt' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'latitude' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'latitude' ) ); ?>" type="text" value="<?php echo esc_attr( $latitude ); ?>" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'longitude' ) ); ?>"><?php esc_html_e( 'Longitude:', 'adrenaline-pt' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'longitude' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'longitude' ) ); ?>" type="text" value="<?php echo esc_attr( $longitude ); ?>" />
				<br>
				<small><?php printf( esc_html__( '%1$sGet the coordinates of your desired location here%2$s. Just copy/paste the latitude and longitude to the above fields.', 'adrenaline-pt' ), '<a href="http://www.latlong.net/" target="_blank">', '</a>' ); ?></small>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'forecast' ) ); ?>"><?php esc_html_e( 'Forecast for:', 'adrenaline-pt' ); ?></label>
				<br>
				<select name="<?php echo esc_attr( $this->get_field_name( 'forecast' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'forecast' ) ); ?>">
					<option value="none" <?php selected( 'none', $forecast ); ?>><?php esc_html_e( 'Current day only', 'adrenaline-pt' ); ?></option>
					<option value="1" <?php selected( '1', $forecast ); ?>><?php esc_html_e( '1 day', 'adrenaline-pt' ); ?></option>
					<option value="2" <?php selected( '2', $forecast ); ?>><?php esc_html_e( '2 days', 'adrenaline-pt' ); ?></option>
					<option value="3" <?php selected( '3', $forecast ); ?>><?php esc_html_e( '3 days', 'adrenaline-pt' ); ?></option>
					<option value="4" <?php selected( '4', $forecast ); ?>><?php esc_html_e( '4 days', 'adrenaline-pt' ); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'temperature_unit' ) ); ?>"><?php esc_html_e( 'Temperature unit:', 'adrenaline-pt' ); ?></label>
				<br>
				<select name="<?php echo esc_attr( $this->get_field_name( 'temperature_unit' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'temperature_unit' ) ); ?>">
					<option value="celsius" <?php selected( 'celsius', $temperature_unit ); ?>><?php esc_html_e( 'Celsius', 'adrenaline-pt' ); ?></option>
					<option value="fahrenheit" <?php selected( 'fahrenheit', $temperature_unit ); ?>><?php esc_html_e( 'Fahrenheit', 'adrenaline-pt' ); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'link_url' ) ); ?>"><?php esc_html_e( 'Link url:', 'adrenaline-pt' ); ?></label>
				<br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link_url' ) ); ?>" type="text" value="<?php echo esc_attr( $link_url ); ?>" />
			</p>

		<?php
		}
	}
	register_widget( 'PW_Weather' );
}
