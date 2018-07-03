<?php
/**
 * Special Title Widget
 *
 * @package adrenaline-pt
 */

if ( ! class_exists( 'PW_Special_Title' ) ) {
	class PW_Special_Title extends WP_Widget {
		private $widget_id_base, $widget_class, $widget_name, $widget_description;

		public function __construct() {
			// Basic widget settings.
			$this->widget_id_base     = 'special_title';
			$this->widget_class       = 'widget-special-title';
			$this->widget_name        = esc_html__( 'Special Title', 'adrenaline-pt' );
			$this->widget_description = esc_html__( 'Title widget with a background effect. For use in Page Builder editor.', 'adrenaline-pt' );

			parent::__construct(
				'pw_' . $this->widget_id_base,
				sprintf( 'ProteusThemes: %s', $this->widget_name ),
				array(
					'description' => $this->widget_description,
					'classname'   => $this->widget_class,
				)
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			echo $args['before_widget'];
		?>
			<h2 class="special-title">
				<?php echo esc_html( $instance['title'] ); ?>
			</h2>
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

			$instance['title'] = esc_html( $new_instance['title'] );

			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @param array $instance The widget options.
		 */
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'adrenaline-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
		<?php
		}
	}
	register_widget( 'PW_Special_Title' );
}
