<?php
/**
 * Timetable Widget
 *
 * @package adrenaline-pt
 */

if ( ! class_exists( 'PW_Timetable' ) ) {
	class PW_Timetable extends WP_Widget {
		private $widget_id_base, $widget_class, $widget_name, $widget_description;
		private $current_widget_id;

		public function __construct() {
			// Basic widget settings.
			$this->widget_id_base     = 'timetable';
			$this->widget_class       = 'widget-timetable';
			$this->widget_name        = esc_html__( 'Timetable', 'adrenaline-pt' );
			$this->widget_description = esc_html__( 'Timetable with custom link for each row.', 'adrenaline-pt' );

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
			$items  = isset( $instance['items'] ) ? array_values( $instance['items'] ) : array();
			$target = ! empty( $instance['new_tab'] ) ? '_blank' : '_self';

			echo $args['before_widget'];
		?>
			<div class="timetable">
			<?php foreach ( $items as $item ) : ?>
				<?php if ( empty( $item['link'] ) ) : ?>
					<div class="timetable__item">
				<?php else : ?>
					<a class="timetable__item" href="<?php echo esc_url( $item['link'] ); ?>" target="<?php echo esc_attr( $target ); ?>">
				<?php endif; ?>
					<div class="timetable__date">
						<span class="timetable__month"><?php echo esc_html( $item['month'] ); ?></span>
						<span class="timetable__day"><?php echo esc_html( $item['day'] ); ?></span>
					</div>
					<div class="timetable__content">
						<div class="h6  timetable__title">
							<?php echo wp_kses_post( $item['name'] ); ?>
						</div>
						<div class="timetable__description">
							<?php echo wp_kses_post( $item['description'] ); ?>
						</div>
						<div class="timetable__price">
							<?php echo wp_kses_post( $item['price'] ); ?>
						</div>
					</div>
				<?php if ( empty( $item['link'] ) ) : ?>
					</div>
				<?php else : ?>
					</a>
				<?php endif; ?>
			<?php endforeach; ?>
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

			$instance['new_tab'] = ! empty( $new_instance['new_tab'] ) ? sanitize_key( $new_instance['new_tab'] ) : '';

			foreach ( $new_instance['items'] as $key => $item ) {
				$instance['items'][ $key ]['id']          = sanitize_key( $item['id'] );
				$instance['items'][ $key ]['link']        = sanitize_text_field( $item['link'] );
				$instance['items'][ $key ]['month']       = sanitize_text_field( $item['month'] );
				$instance['items'][ $key ]['day']         = sanitize_text_field( $item['day'] );
				$instance['items'][ $key ]['name']        = sanitize_text_field( $item['name'] );
				$instance['items'][ $key ]['description'] = sanitize_text_field( $item['description'] );
				$instance['items'][ $key ]['price']       = sanitize_text_field( $item['price'] );
			}

			// Sort items by ids, because order might have changed.
			usort( $instance['items'], array( $this, 'sort_by_id' ) );

			return $instance;
		}

		/**
		 * Helper function to order items by ids.
		 * Used for sorting widget setting items.
		 *
		 * @param int $a first comparable parameter.
		 * @param int $b second comparable parameter.
		 */
		function sort_by_id( $a, $b ) {
			return $a['id'] - $b['id'];
		}

		/**
		 * Back-end widget form.
		 *
		 * @param array $instance The widget options.
		 */
		public function form( $instance ) {
			$new_tab  = empty( $instance['new_tab'] ) ? '' : $instance['new_tab'];

			if ( ! isset( $instance['items'] ) ) {
				$instance['items'] = array(
					array(
						'id'          => 1,
						'link'        => '',
						'month'       => '',
						'day'         => '',
						'name'        => '',
						'description' => '',
						'price'       => '',
					),
				);
			}

			// Page Builder fix when using repeating fields.
			if ( 'temp' === $this->id ) {
				$this->current_widget_id = $this->number;
			}
			else {
				$this->current_widget_id = $this->id;
			}
		?>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $new_tab, 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'new_tab' ) ); ?>" value="on" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>"><?php esc_html_e( 'Open links in new tab', 'adrenaline-pt' ); ?></label>
			</p>

			<hr>

			<h4><?php esc_html_e( 'Timetable rows', 'adrenaline-pt' ); ?></h4>

			<script type="text/template" id="js-pt-timetable-<?php echo esc_attr( $this->current_widget_id ); ?>">
				<div class="pt-sortable-setting  ui-widget  ui-widget-content  ui-helper-clearfix  ui-corner-all">
					<div class="pt-sortable-setting__header  ui-widget-header  ui-corner-all">
						<span class="dashicons  dashicons-sort"></span>
						<span><?php esc_html_e( 'Timetable row', 'adrenaline-pt' ); ?> - </span>
						<span class="pt-sortable-setting__header-title">{{name}}</span>
						<span class="pt-sortable-setting__toggle  dashicons  dashicons-minus"></span>
					</div>
					<div class="pt-sortable-setting__content">
						<p>
							<strong><?php esc_html_e( 'Date:', 'adrenaline-pt' ); ?></strong><br>

							<label for="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-day"><?php esc_html_e( 'Day:', 'adrenaline-pt' ); ?></label>
							<input id="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-day" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[{{id}}][day]" size="2" type="text" value="{{day}}" />

							<label for="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-month"><?php esc_html_e( 'Month:', 'adrenaline-pt' ); ?></label>
							<input id="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-month" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[{{id}}][month]" size="10" type="text" value="{{month}}" />
						</p>
						<p>
							<label for="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-name"><?php esc_html_e( 'Name:', 'adrenaline-pt' ); ?></label>
							<input class="widefat  js-pt-sortable-setting-title" id="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-name" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[{{id}}][name]" type="text" value="{{name}}" />
						</p>

						<p>
							<label for="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-description"><?php esc_html_e( 'Description:', 'adrenaline-pt' ); ?></label>
							<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-description" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[{{id}}][description]" type="text" value="{{description}}" />
						</p>

						<p>
							<label for="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-price"><?php esc_html_e( 'Price:', 'adrenaline-pt' ); ?></label>
							<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-price" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[{{id}}][price]" type="text" value="{{price}}" />
						</p>

						<p>
							<label for="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-link"><?php esc_html_e( 'Link:', 'adrenaline-pt' ); ?></label>
							<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-link" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[{{id}}][link]" type="text" value="{{link}}" />
						</p>

						<p>
							<input name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[{{id}}][id]" class="js-pt-timetable-id" type="hidden" value="{{id}}" />
							<a href="#" class="pt-remove-timetable-item  js-pt-remove-timetable-item"><span class="dashicons dashicons-dismiss"></span> <?php esc_html_e( 'Remove item', 'adrenaline-pt' ); ?></a>
						</p>
					</div>
				</div>
			</script>
			<div class="pt-widget-timetable" id="timetable-<?php echo esc_attr( $this->current_widget_id ); ?>">
				<div class="timetable-items  js-pt-sortable-timetable-items"></div>
				<p>
					<a href="#" class="button  js-pt-add-timetable-item"><?php esc_html_e( 'Add new item', 'adrenaline-pt' ); ?></a>
				</p>
			</div>
			<script type="text/javascript">
				(function( $ ) {
					// Repopulate the form.
					var timetableJSON = <?php echo wp_json_encode( $instance['items'] ) ?>;

					// Get the right widget id and remove the added < > characters at the start and at the end.
					var widgetId = '<<?php echo esc_js( $this->current_widget_id ); ?>>'.slice( 1, -1 );

					if ( _.isFunction( Adrenaline.Utils.repopulateTimetable ) ) {
						Adrenaline.Utils.repopulateTimetable( timetableJSON, widgetId );
					}

					// Make settings sortable.
					$( '.js-pt-sortable-timetable-items' ).sortable({
						items: '.pt-widget-single-timetable',
						handle: '.pt-sortable-setting__header',
						cancel: '.pt-sortable-setting__toggle',
						placeholder: 'pt-sortable-setting__placeholder',
						stop: function( event, ui ) {
							$( this ).find( '.js-pt-timetable-id' ).each( function( index ) {
								$( this ).val( index );
							});
						}
					});
				})( jQuery );
			</script>
		<?php
		}
	}
	register_widget( 'PW_Timetable' );
}
