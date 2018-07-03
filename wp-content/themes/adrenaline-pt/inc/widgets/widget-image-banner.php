<?php
/**
 * Image Banner Widget
 *
 * @package adrenaline-pt
 */

if ( ! class_exists( 'PW_Image_Banner' ) ) {
	class PW_Image_Banner extends WP_Widget {

		// Basic widget settings.
		function widget_id_base() { return 'image_banner'; }
		function widget_name() { return esc_html__( 'Image Banner', 'adrenaline-pt' ); }
		function widget_description() { return esc_html__( 'Linkable banner with image background and custom text.', 'adrenaline-pt' ); }
		function widget_class() { return 'widget-image-banner'; }

		public function __construct() {
			parent::__construct(
				'pw_' . $this->widget_id_base(),
				sprintf( 'ProteusThemes: %s', $this->widget_name() ),
				array(
					'description' => $this->widget_description(),
					'classname'   => $this->widget_class(),
				)
			);

			// Enqueue needed admin CSS and JS (for color picker).
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_widget_scripts' ) );
		}

		/**
		 * Enqueue needed admin CSS and JS (for color picker).
		 */
		public function enqueue_widget_scripts() {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args widget arguments.
		 * @param array $instance widget data.
		 */
		public function widget( $args, $instance ) {
			$title      = empty( $instance['title'] ) ? '' : $instance['title'];
			$text       = empty( $instance['text'] ) ? '' : $instance['text'];
			$text_color = empty( $instance['text_color'] ) ? '#ffffff' : $instance['text_color'];
			$text_size  = empty( $instance['text_size'] ) ? 'normal' : $instance['text_size'];
			$image      = empty( $instance['image'] ) ? '' : $instance['image'];
			$custom_url = empty( $instance['custom_url'] ) ? '#' : $instance['custom_url'];
			$cta_text   = empty( $instance['cta_text'] ) ? '' : $instance['cta_text'];
			$new_tab    = empty( $instance['new_tab'] ) ? '_self' : '_blank';

			echo $args['before_widget'];
			?>
				<?php if ( empty( $cta_text ) ) : ?>
					<a href="<?php echo esc_url( $custom_url ); ?>" target="<?php echo esc_attr( $new_tab ); ?>" class="image-banner">
				<?php else : ?>
					<div class="image-banner">
				<?php endif; ?>
					<img class="img-fluid  image-banner__image" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>">
					<div class="image-banner__content  image-banner__content--<?php echo esc_attr( $text_size ); ?>">
						<div class="h3  image-banner__title" style="color: <?php echo esc_attr( $text_color ); ?>">
							<?php echo wp_kses_post( $title ); ?>
						</div>
						<?php if ( ! empty( $text ) ) : ?>
							<p class="image-banner__text" style="color: <?php echo esc_attr( $text_color ); ?>">
								<?php echo wp_kses_post( $text ); ?>
							</p>
						<?php endif; ?>
						<?php if ( ! empty( $cta_text ) ) : ?>
							<a href="<?php echo esc_url( $custom_url ); ?>" target="<?php echo esc_attr( $new_tab ); ?>" class="btn  btn-primary  image-banner__cta"><?php echo esc_html( $cta_text ); ?></a>
						<?php endif; ?>
					</div>
				<?php if ( empty( $cta_text ) ) : ?>
					</a>
				<?php else : ?>
					</div>
				<?php endif; ?>
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

			$instance['title']      = wp_kses_post( $new_instance['title'] );
			$instance['text']       = wp_kses_post( $new_instance['text'] );
			$instance['text_color'] = sanitize_text_field( $new_instance['text_color'] );
			$instance['text_size']  = sanitize_key( $new_instance['text_size'] );
			$instance['image']      = esc_url_raw( $new_instance['image'] );
			$instance['custom_url'] = esc_url_raw( $new_instance['custom_url'] );
			$instance['cta_text']   = esc_html( $new_instance['cta_text'] );
			$instance['new_tab']    = empty( $new_instance['new_tab'] ) ? '' : sanitize_key( $new_instance['new_tab'] );

			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @param array $instance The widget options.
		 */
		public function form( $instance ) {
			$title      = empty( $instance['title'] ) ? '' : $instance['title'];
			$text       = empty( $instance['text'] ) ? '' : $instance['text'];
			$text_color = empty( $instance['text_color'] ) ? '#ffffff' : $instance['text_color'];
			$text_size  = empty( $instance['text_size'] ) ? 'normal' : $instance['text_size'];
			$image      = empty( $instance['image'] ) ? '' : $instance['image'];
			$custom_url = empty( $instance['custom_url'] ) ? '' : $instance['custom_url'];
			$cta_text   = empty( $instance['cta_text'] ) ? '' : $instance['cta_text'];
			$new_tab    = empty( $instance['new_tab'] ) ? '' : $instance['new_tab'];
			?>


			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'adrenaline-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Subtitle:', 'adrenaline-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" type="text" value="<?php echo esc_attr( $text ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'text_color' ) ); ?>" style="vertical-align: top;"><?php esc_html_e( 'Text color: ', 'adrenaline-pt' ); ?></label>
				<input class="pw-color-picker" id="<?php echo esc_attr( $this->get_field_id( 'text_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text_color' ) ); ?>" type="text" value="<?php echo esc_attr( $text_color ); ?>" data-default-color="#ffffff"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'text_size' ) ); ?>"><?php esc_html_e( 'Text size:', 'adrenaline-pt' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'text_size' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'text_size' ) ); ?>">
					<option value="small" <?php selected( $text_size, 'small' ) ?>><?php esc_html_e( 'Small', 'adrenaline-pt' ); ?></option>
					<option value="normal" <?php selected( $text_size, 'normal' ) ?>><?php esc_html_e( 'Normal', 'adrenaline-pt' ); ?></option>
					<option value="big" <?php selected( $text_size, 'big' ) ?>><?php esc_html_e( 'Big', 'adrenaline-pt' ); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php esc_html_e( 'Picture URL:', 'adrenaline-pt' ); ?></label>
				<input class="widefat" style="margin-bottom: 6px;" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" type="text" value="<?php echo esc_attr( $image ); ?>" />
				<input type="button" onclick="ProteusWidgetsUploader.imageUploader.openFileFrame('<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>');" class="button button-secondary" value="<?php esc_html_e( 'Upload Image', 'adrenaline-pt' ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'custom_url' ) ); ?>"><?php esc_html_e( 'Link:', 'adrenaline-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'custom_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'custom_url' ) ); ?>" type="text" value="<?php echo esc_attr( $custom_url ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'cta_text' ) ); ?>"><?php esc_html_e( 'Button text:', 'adrenaline-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cta_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cta_text' ) ); ?>" type="text" value="<?php echo esc_attr( $cta_text ); ?>" />
				<small><?php esc_html_e( 'If this field is empty, then the whole widget will be click-able, otherwise the button will appear.', 'adrenaline-pt' ); ?></small>
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $new_tab, 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'new_tab' ) ); ?>" value="on" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>"><?php esc_html_e( 'Open link in new tab', 'adrenaline-pt' ); ?></label>
			</p>

			<script type="text/javascript">
				(function( $ ) {
					$( document ).ready(function() {

						// Initialize color picker (for Page builder, widgets.php and customizer).
						$( '.so-content .pw-color-picker' ).wpColorPicker();
						$( '#widgets-right .pw-color-picker' ).wpColorPicker();
					});
				})( jQuery );
			</script>

			<?php
		}
	}
	register_widget( 'PW_Image_Banner' );
}
