<?php
/**
 * Special Offer Widget
 *
 * @package adrenaline-pt
 */

if ( ! class_exists( 'PW_Special_Offer' ) ) {
	class PW_Special_Offer extends WP_Widget {
		private $font_awesome_icons_list;

		// Basic widget settings.
		function widget_id_base() { return 'special_offer'; }
		function widget_name() { return esc_html__( 'Special Offer', 'adrenaline-pt' ); }
		function widget_description() { return esc_html__( 'Display your special offer with important info.', 'adrenaline-pt' ); }
		function widget_class() { return 'widget-special-offer'; }

		/**
		 * Register widget with WordPress.
		 */
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
				'pw/special_offer_fa_icons_list',
				array(
					'fa-map-marker',
					'fa-home',
					'fa-phone',
					'fa-envelope-o',
					'fa-users',
					'fa-compass',
					'fa-money',
					'fa-suitcase',
					'fa-warning',
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
			// Setup correct CTA URL.
			$cta_url  = '';
			$cta_type = empty( $instance['cta_type'] ) ? 'custom-url' : $instance['cta_type'];
			if ( 'custom-url' === $cta_type ) {
				$cta_url = empty( $instance['cta_custom_url'] ) ? '' : $instance['cta_custom_url'];
			}
			elseif ( ! empty( $instance['cta_wc_product'] ) && 'none' !== $instance['cta_wc_product'] ) {
				$cta_url = add_query_arg( 'add-to-cart', esc_attr( $instance['cta_wc_product'] ), AdrenalineHelpers::get_woocommerce_cart_url() );
			}

			echo $args['before_widget'];
			?>
				<div class="special-offer">
					<div class="special-offer__image">
						<img class="img-fluid" src="<?php echo esc_url( $instance['image'] ); ?>" alt="<?php echo esc_html( $instance['title'] ); ?>">
						<div class="special-offer__location">
							<i class="fa <?php echo esc_attr( $instance['location_icon'] ); ?>" aria-hidden="true"></i> <?php echo esc_html( $instance['location_name'] ); ?>
						</div>
					</div>
					<div class="special-offer__content">
						<?php if ( ! empty( $instance['price'] ) ) : ?>
							<div class="special-offer__price">
								<?php echo wp_kses_post( $instance['price'] ); ?>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $instance['label'] ) ) : ?>
							<div class="special-offer__label">
								<?php echo esc_html( $instance['label'] ); ?>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $instance['title'] ) ) : ?>
							<p class="h4  special-offer__title">
								<?php echo esc_html( $instance['title'] ); ?>
							</p>
						<?php endif; ?>
						<?php if ( ! empty( $instance['text'] ) ) : ?>
							<p class="special-offer__text">
								<?php echo wp_kses_post( $instance['text'] ); ?>
							</p>
						<?php endif; ?>
						<?php if ( ! empty( $cta_url ) ) : ?>
							<a href="<?php echo esc_url( $cta_url ); ?>" target="<?php echo ! empty( $instance['new_tab'] ) ? '_blank' : '_self'; ?>" class="btn  btn-primary  btn-outline-primary  special-offer__cta"><?php echo ! empty( $instance['cta_text'] ) ? esc_html( $instance['cta_text'] ) : esc_html__( 'Book Now', 'adrenaline-pt' ); ?></a>
						<?php endif; ?>
					</div>
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

			$instance['title']          = sanitize_text_field( $new_instance['title'] );
			$instance['label']          = sanitize_text_field( $new_instance['label'] );
			$instance['location_icon']  = sanitize_text_field( $new_instance['location_icon'] );
			$instance['location_name']  = sanitize_text_field( $new_instance['location_name'] );
			$instance['price']          = wp_kses_post( $new_instance['price'] );
			$instance['image']          = esc_url_raw( $new_instance['image'] );
			$instance['text']           = wp_kses_post( $new_instance['text'] );
			$instance['cta_text']       = sanitize_text_field( $new_instance['cta_text'] );
			$instance['cta_custom_url'] = esc_url_raw( $new_instance['cta_custom_url'] );
			$instance['cta_type']       = ! empty( $new_instance['cta_wc_product'] ) ? sanitize_key( $new_instance['cta_type'] ) : 'custom-url';
			$instance['cta_wc_product'] = ! empty( $new_instance['cta_wc_product'] ) ? sanitize_key( $new_instance['cta_wc_product'] ) : '';
			$instance['new_tab']        = ! empty( $new_instance['new_tab'] ) ? sanitize_key( $new_instance['new_tab'] ) : '';

			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @param array $instance The widget options.
		 */
		public function form( $instance ) {
			$title          = empty( $instance['title'] ) ? '' : $instance['title'];
			$label          = empty( $instance['label'] ) ? '' : $instance['label'];
			$location_icon  = empty( $instance['location_icon'] ) ? '' : $instance['location_icon'];
			$location_name  = empty( $instance['location_name'] ) ? '' : $instance['location_name'];
			$price          = empty( $instance['price'] ) ? '' : $instance['price'];
			$image          = empty( $instance['image'] ) ? '' : $instance['image'];
			$text           = empty( $instance['text'] ) ? '' : $instance['text'];
			$cta_text       = empty( $instance['cta_text'] ) ? '' : $instance['cta_text'];
			$cta_type       = empty( $instance['cta_type'] ) ? 'custom-url' : $instance['cta_type'];
			$cta_custom_url = empty( $instance['cta_custom_url'] ) ? '' : $instance['cta_custom_url'];
			$cta_wc_product = empty( $instance['cta_wc_product'] ) ? '' : $instance['cta_wc_product'];
			$new_tab        = empty( $instance['new_tab'] ) ? '' : $instance['new_tab'];
		?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'adrenaline-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<p>
				<span><?php esc_html_e( 'Location:', 'adrenaline-pt' ); ?></span><br>
				<label for="<?php echo esc_attr( $this->get_field_id( 'location_icon' ) ); ?>"><?php esc_html_e( 'Icon:', 'adrenaline-pt' ); ?></label>
				<input class="js-icon-input" id="<?php echo esc_attr( $this->get_field_id( 'location_icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'location_icon' ) ); ?>" type="text" size=20 value="<?php echo esc_attr( $location_icon ); ?>" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'location_name' ) ); ?>"><?php esc_html_e( 'Name:', 'adrenaline-pt' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'location_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'location_name' ) ); ?>" type="text" size=50 value="<?php echo esc_attr( $location_name ); ?>" />
				<br>
				<small><?php printf( esc_html__( 'Click on the icon below or manually select from the %s website', 'adrenaline-pt' ), '<a href="http://fontawesome.io/icons/" target="_blank">FontAwesome</a>' ); ?>.</small>
				<br>
				<?php foreach ( $this->font_awesome_icons_list as $icon ) : ?>
					<a class="js-selectable-icon  icon-widget" href="#" data-iconname="<?php echo esc_attr( $icon ); ?>"><i class="fa fa-lg <?php echo esc_attr( $icon ); ?>"></i></a>
				<?php endforeach; ?>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'label' ) ); ?>"><?php esc_html_e( 'Label:', 'adrenaline-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'label' ) ); ?>" type="text" value="<?php echo esc_attr( $label ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'price' ) ); ?>"><?php esc_html_e( 'Price:', 'adrenaline-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'price' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'price' ) ); ?>" type="text" value="<?php echo esc_attr( $price ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php esc_html_e( 'Picture URL:', 'adrenaline-pt' ); ?></label>
				<input class="widefat" style="margin-bottom: 6px;" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" type="text" value="<?php echo esc_attr( $image ); ?>" />
				<input type="button" onclick="ProteusWidgetsUploader.imageUploader.openFileFrame('<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>');" class="button button-secondary" value="<?php esc_html_e( 'Upload Image', 'adrenaline-pt' ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Description:', 'adrenaline-pt' ); ?></label>
				<textarea rows="4" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo wp_kses_post( $text ); ?></textarea>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'cta_text' ) ); ?>"><?php esc_html_e( 'CTA button text:', 'adrenaline-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cta_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cta_text' ) ); ?>" type="text" value="<?php echo esc_attr( $cta_text ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'cta_type' ) ); ?>"><?php esc_html_e( 'CTA button type:', 'adrenaline-pt' ); ?></label>
				<select class="js-pt-feature_cta_type" name="<?php echo esc_attr( $this->get_field_name( 'cta_type' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'cta_type' ) ); ?>">
					<option value="custom-url" <?php selected( $cta_type, 'custom-url' ) ?>><?php esc_html_e( 'Custom URL', 'adrenaline-pt' ); ?></option>
					<option value="wc-product" <?php selected( $cta_type, 'wc-product' ) ?>><?php esc_html_e( 'Link with WooCommerce product', 'adrenaline-pt' ); ?></option>
				</select>
			</p>

			<p class="js-pt-feature-custom-url" style="display: none;">
				<label for="<?php echo esc_attr( $this->get_field_id( 'cta_custom_url' ) ); ?>"><?php esc_html_e( 'CTA button URL:', 'adrenaline-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cta_custom_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cta_custom_url' ) ); ?>" type="text" value="<?php echo esc_attr( $cta_custom_url ); ?>" />
			</p>

			<p class="js-pt-feature-wc-product" style="display: none;">
				<label for="<?php echo esc_attr( $this->get_field_id( 'cta_wc_product' ) ); ?>"><?php esc_html_e( 'CTA button product link:', 'adrenaline-pt' ); ?></label><br>
				<small><?php esc_html_e( 'Link a WooCommerce product to this CTA button. (Woocommerce plugin has to be active).', 'adrenaline-pt' );?></small><br>
				<?php if ( AdrenalineHelpers::is_woocommerce_active() ) : ?>
					<select name="<?php echo esc_attr( $this->get_field_name( 'cta_wc_product' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'cta_wc_product' ) ); ?>">
						<option value="none" <?php selected( $cta_wc_product, 'none' ); ?>><?php esc_html_e( 'Select product', 'adrenaline-pt' ); ?></option>
					<?php
						$args = array( 'post_type' => 'product', 'post_status' => array( 'publish' ) );
						$loop = new WP_Query( $args );
						while ( $loop->have_posts() ) :
							$loop->the_post();
							$id = get_the_ID();
					?>
							<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $cta_wc_product, $id ); ?>><?php the_title(); ?></option>
					<?php
						endwhile;

						// Restore original Post Data.
						wp_reset_postdata();
					?>
					</select>
					<?php else : ?>
						<span style="color: red;"><?php esc_html_e( 'Woocommerce plugin has to be active, for this option!', 'adrenaline-pt' );?></span>
					<?php endif; ?>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $new_tab, 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'new_tab' ) ); ?>" value="on" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>"><?php esc_html_e( 'Open CTA in new tab', 'adrenaline-pt' ); ?></label>
			</p>

			<script type="text/javascript">
				(function( $ ) {
					// Show/hide cta_type.
					if ( 'custom-url' === $( '.js-pt-feature_cta_type' ).val() ) {
						$( '.js-pt-feature-custom-url' ).show();
					}
					else {
						$( '.js-pt-feature-wc-product' ).show();
					}
				})( jQuery );
			</script>

			<?php
		}
	}
	register_widget( 'PW_Special_Offer' );
}
