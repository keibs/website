/* global AdrenalineVars */
define( ['jquery', 'underscore'], function ( $, _ ) {
	'use strict';

	var config = {
		currentDayClass: '.js-weather-current',
		forecastClass:   '.js-weather-forecast',
	};

	var template = {
		currentWeather: _.template( '<div class="weather-current"><div class="weather-current__content"><<%= titleTag %> class="weather-current__title" <%= url %>><%= location %></<%= titleTag %>><p class="weather-current__description"><%= weatherInWords %></p></div><div class="weather-current__temperature-container"><p class="weather-current__temperature"><%= temperature %><sup><%= temperatureUnit %></sup><a class="weather-current__powered-by-link" href="https://darksky.net/poweredby/" target="_blank" rel="nofollow"><%= poweredByText %></a></p><img class="weather-current__icon" src="<%= pathToTheme %>/assets/images/weather-widget/color-icons/<%= icon %>.svg" alt="<%= icon %>"></div></div>' ),
		weatherRow: _.template( '<div class="weather-forecast"><span class="weather-forecast__day"><%= dayOfTheWeek %></span><div class="weather-forecast__temperature-container"><img class="weather-forecast__icon" src="<%= pathToTheme %>/assets/images/weather-widget/gray-icons/<%= icon %>.svg" alt="<%= icon %>"><p class="weather-forecast__temperature"><%= temperature %><sup><%= temperatureUnit %></sup></p></div></div>' ),
		error: _.template( '<div class="weather__error"><%= error %></div>' )
	};

	var WeatherWidget = function ( $widget ) {
		this.$widget = $widget;

		this.latitude        = this.$widget.data( 'latitude' );
		this.longitude       = this.$widget.data( 'longitude' );
		this.temperatureUnit = this.$widget.data( 'temperature-unit' );
		this.forecastDays    = this.$widget.data( 'forecast' );
		this.location        = this.$widget.data( 'location' );
		this.url             = this.$widget.data( 'url' );

		this.getDataFromWeatherAPI();

		return this;
	};

	_.extend( WeatherWidget.prototype, {
		/**
		 * Get weather data for the specified location.
		 */
		getDataFromWeatherAPI: function() {
			$.ajax( {
				method:     'GET',
				url:        AdrenalineVars.ajax_url,
				data:       {
					'action':    'pt_adrenaline_get_weather_data',
					'security':  AdrenalineVars.ajax_nonce,
					'latitude':  this.latitude,
					'longitude': this.longitude,
				},
				context:    this,
				beforeSend: function() {
					this.$widget.append( '<p class="weather__loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="screen-reader-text">Loading...</span></p>' );
				},
				complete:   function() {
					this.$widget.find( '.weather__loader' ).remove();
				}
			} )
			.done( function( response ) {
				if ( response.success ) {
					this.outputData( response );
				}
				else {
					this.outputError( response.data );
				}
			});

			return this;
		},

		/**
		 * Output the retrieved data.
		 *
		 * @param response Response with data from the weather API.
		 */
		outputData: function( response ) {
			var today = _.first( response.data ),
				forecast = _.chain( response.data )
					.rest()
					.first( this.forecastDays )
					.value();

			// Weather today
			this.render( today, true );

			// Loop through the forecast days
			_.each( forecast, function( obj ) {
				this.render( obj );
			}, this );
		},

		/**
		 * Render the appropriate template
		 * @param  {object}  obj
		 * @param  {Boolean} isCurrent
		 * @return {void}
		 */
		render: function ( weatherData, isCurrent ) {
			var templateData =  {
				icon:            weatherData.icon,
				temperature:     this.getCorrectTemperatureValue( weatherData.temperature_max ),
				temperatureUnit: this.getCorrectTemperatureUnit( weatherData.temperature_max ),
				pathToTheme:     AdrenalineVars.pathToTheme,
			};

			if ( isCurrent ) { // Append a the current day weather info.
				this.$widget.find( config.currentDayClass ).append(
					template.currentWeather( _.extend( templateData, {
						weatherInWords: weatherData.weather_in_words,
						location:       this.location,
						titleTag:       this.url === '' ? 'p' : 'a',
						url:            this.url === '' ? '' : 'href="' + this.url + '"',
						poweredByText:  AdrenalineVars.poweredByText,
					} ) )
				);
			}
			else { // Append a row of data to the widget. One for each day of forecast.
				this.$widget.find( config.forecastClass ).append(
					template.weatherRow( _.extend( templateData, {
						dayOfTheWeek: weatherData.day_of_the_week_short,
					} ) )
				);
			}
		},

		/**
		 * Output the error message.
		 *
		 * @param response Response with data from the weather API.
		 */
		outputError: function( response ) {
			this.$widget.find( config.forecastClass ).after(
				template.error( {
					error: response,
				} )
			);
		},

		/**
		 * Get correct temperature value.
		 *
		 * @param temperatureObj Temperature data object.
		 */
		getCorrectTemperatureValue: function( temperatureObj ) {
			if ( 'fahrenheit' === this.temperatureUnit ) {
				return temperatureObj.fahrenheit.value;
			}

			return temperatureObj.celsius.value;
		},

		/**
		 * Get correct temperature unit.
		 *
		 * @param temperatureObj Temperature data object.
		 */
		getCorrectTemperatureUnit: function( temperatureObj ) {
			if ( 'fahrenheit' === this.temperatureUnit ) {
				return temperatureObj.fahrenheit.unit;
			}

			return temperatureObj.celsius.unit;
		},

	} );

	return WeatherWidget;
} );
