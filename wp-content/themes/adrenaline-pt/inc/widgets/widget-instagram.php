<?php
/**
 * Instagram Widget
 *
 * @package adrenaline-pt
 */

if ( ! class_exists( 'PW_Instagram' ) ) {
	class PW_Instagram extends WP_Widget {

		private $font_awesome_icons_list;

		// Basic widget settings.
		function widget_id_base() { return 'instagram'; }
		function widget_name() { return esc_html__( 'Instagram', 'adrenaline-pt' ); }
		function widget_description() { return esc_html__( 'A row of images pulled from instagram for Page Builder.', 'adrenaline-pt' ); }
		function widget_class() { return 'widget-instagram'; }

		public function __construct() {
			parent::__construct(
				'pw_' . $this->widget_id_base(),
				sprintf( 'ProteusThemes: %s', $this->widget_name() ),
				array(
					'description' => $this->widget_description(),
					'classname'   => $this->widget_class(),
				)
			);

			// A list of icons to choose from in the widget backend.
			$this->font_awesome_icons_list = apply_filters(
				'pw/instagram_widget_fa_icons_list',
				array(
					'fa-instagram',
					'fa-facebook',
					'fa-twitter',
					'fa-youtube',
					'fa-skype',
					'fa-google-plus',
					'fa-pinterest',
					'fa-vine',
					'fa-tumblr',
					'fa-foursquare',
					'fa-xing',
					'fa-flickr',
					'fa-vimeo',
					'fa-linkedin',
					'fa-dribble',
					'fa-wordpress',
					'fa-rss',
					'fa-github',
					'fa-bitbucket',
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
			$instagram_access_token = ! empty( $instance['instagram_access_token'] ) ? $instance['instagram_access_token'] : '';
			$number_of_images       = ! empty( $instance['number_of_images'] ) ? absint( $instance['number_of_images'] ) : 8;
			$is_cta_enabled         = ! empty( $instance['is_cta_enabled'] );
			$title                  = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$text                   = ! empty( $instance['text'] ) ? $instance['text'] : '';
			$url                    = ! empty( $instance['url'] ) ? $instance['url'] : '#';
			$icon                   = ! empty( $instance['icon'] ) ? $instance['icon'] : 'fa-instagram';

			echo $args['before_widget'];
			?>

			<div class="pw-instagram  js-pw-instagram" data-access-token="<?php echo esc_attr( $instagram_access_token ); ?>" data-num-images="<?php echo esc_attr( $number_of_images ); ?>" data-has-cta="<?php echo esc_attr( $is_cta_enabled ); ?>">
				<?php if ( $is_cta_enabled ) : ?>
				<a class="pw-instagram__item  pw-instagram__item--cta js-pw-instagram-cta" href="<?php echo esc_url( $url ); ?>" target="_blank" style="display:none;">
					<h4 class="pw-instagram__title"><?php echo esc_html( $title ); ?></h4>
					<p class="pw-instagram__text"><?php echo esc_html( $text ); ?></p>
					<i class="fa  <?php echo esc_attr( $icon ); ?>"></i>
				</a>
				<?php endif; ?>
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

			$instance['instagram_access_token'] = esc_html( $new_instance['instagram_access_token'] );
			$instance['number_of_images']       = absint( AdrenalineHelpers::bound( $new_instance['number_of_images'], 0, 10 ) );
			$instance['is_cta_enabled']         = ! empty( $new_instance['is_cta_enabled'] ) ? sanitize_key( $new_instance['is_cta_enabled'] ) : '';
			$instance['title']                  = esc_html( $new_instance['title'] );
			$instance['text']                   = esc_html( $new_instance['text'] );
			$instance['url']                    = esc_url_raw( $new_instance['url'] );
			$instance['icon']                   = esc_html( $new_instance['icon'] );

			// Delete currently cached instagram data (API response), if any.
			delete_transient( 'pt_adrenaline_instagram_data-' . sanitize_key( $instance['instagram_access_token'] ) );

			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @param array $instance The widget options.
		 */
		public function form( $instance ) {
			$instagram_access_token = ! empty( $instance['instagram_access_token'] ) ? $instance['instagram_access_token'] : '';
			$number_of_images       = ! empty( $instance['number_of_images'] ) ? $instance['number_of_images'] : 8;
			$is_cta_enabled         = ! empty( $instance['is_cta_enabled'] ) ? $instance['is_cta_enabled'] : '';
			$title                  = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$text                   = ! empty( $instance['text'] ) ? $instance['text'] : '';
			$url                    = ! empty( $instance['url'] ) ? $instance['url'] : '';
			$selected_icon          = ! empty( $instance['icon'] ) ? $instance['icon'] : '';
		?>

			<div style="background-color: #eff8fb; border: 1px solid #5ac6ea; border-radius: 4px; padding: 20px; ">
				<strong><?php esc_html_e( 'Windget instructions:', 'adrenaline-pt' ); ?></strong>
				<ol>
					<li><strong><?php printf( esc_html__( '%1$sLogin and get your Instagram Access Token%2$s.', 'adrenaline-pt' ), '<a href="https://apps.proteusthemes.com/instagram/get-access-token.php" target="_blank">', '</a>' ); ?></strong></li>
					<li><?php esc_html_e( 'Once you complete the authentication steps, copy the Access Token to the field below.', 'adrenaline-pt' ); ?></li>
				</ol>
			</div>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'instagram_access_token' ) ); ?>"><?php esc_html_e( 'Instagram access token:', 'adrenaline-pt' ); ?></label>
				<br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram_access_token' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram_access_token' ) ); ?>" type="text" value="<?php echo esc_attr( $instagram_access_token ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'number_of_images' ) ); ?>"><?php esc_html_e( 'Number of Images:', 'adrenaline-pt' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'number_of_images' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_of_images' ) ); ?>" type="number" min="1" max="10" value="<?php echo esc_attr( $number_of_images ); ?>" />
			</p>
			<p>
				<input class="checkbox  js-cta-box-control" type="checkbox" <?php checked( $is_cta_enabled, 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'is_cta_enabled' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'is_cta_enabled' ) ); ?>" value="on" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'is_cta_enabled' ) ); ?>"><?php esc_html_e( 'Show CTA box?', 'adrenaline-pt' ); ?></label>
			</p>
			<div class="js-cta-box">
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'adrenaline-pt' ); ?></label>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Text:', 'adrenaline-pt' ); ?></label>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" type="text" value="<?php echo esc_html( $text ); ?>" />
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"><?php esc_html_e( 'Link:', 'adrenaline-pt' ); ?></label>
					<input class="widefat"  id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>" type="text" value="<?php echo esc_url( $url ); ?>" />
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'Select icon:', 'adrenaline-pt' ); ?></label> <br>
					<small><?php printf( esc_html__( 'Click on the icon below or manually select from the %s website.', 'adrenaline-pt' ), '<a href="http://fontawesome.io/icons/" target="_blank">FontAwesome</a>' ); ?></small>
					<input id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>" type="text" value="<?php echo esc_html( $selected_icon ); ?>" class="widefat  js-icon-input" /> <br><br>
					<?php foreach ( $this->font_awesome_icons_list as $icon ) : ?>
						<a class="js-selectable-icon  icon-widget" href="#" data-iconname="<?php echo esc_attr( $icon ); ?>"><i class="fa fa-lg <?php echo esc_html( $icon ) ?>"></i></a>
					<?php endforeach; ?>
				</p>
			</div>

			<script type="text/javascript">
				(function( $ ) {
					// Show/hide CTA box on load (this change event will be caught in the admin.js).
					$( '.js-cta-box-control' ).change();
				})( jQuery );
			</script>

		<?php
		}
	}
	register_widget( 'PW_Instagram' );
}
