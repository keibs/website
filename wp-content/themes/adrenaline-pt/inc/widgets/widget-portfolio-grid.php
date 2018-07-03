<?php
/**
 * Portfolio Grid Widget
 *
 * @package adrenaline-pt
 */

if ( ! class_exists( 'PW_Portfolio_Grid' ) ) {
	class PW_Portfolio_Grid extends WP_Widget {

		// Basic widget settings.
		function widget_id_base() { return 'portfolio_grid'; }
		function widget_name() { return esc_html__( 'Portfolio Grid', 'adrenaline-pt' ); }
		function widget_description() { return sprintf( esc_html_x( 'Displays portfolio items from the %s plugin.', '%s represents a link to plugin: https://wordpress.org/plugins/portfolio-post-type/', 'adrenaline-pt' ), '<a target="_blank" href="https://wordpress.org/plugins/portfolio-post-type/">Portfolio Post Type</a>' ); }
		function widget_class() { return 'widget-portfolio-grid'; }

		public function __construct() {
			parent::__construct(
				'pw_' . $this->widget_id_base(),
				sprintf( 'ProteusThemes: %s', $this->widget_name() ),
				array(
					'description' => $this->widget_description(),
					'classname'   => $this->widget_class(),
				)
			);

			// Actions.
			add_action( 'admin_enqueue_scripts', array( $this, 'pt_admin_enqueue_scripts' ) );
		}

		/**
		 * Enqueue Sortable jQuery UI module.
		 */
		public function pt_admin_enqueue_scripts() {
			wp_enqueue_script( 'jquery-ui-sortable' );
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
			// Unique ID for this accordion.
			$uniqid                          = sanitize_html_class( $args['widget_id'] );
			$instance['selected_categories'] = empty( $instance['selected_categories'] ) ? array() : (array) $instance['selected_categories'];
			$instance['selected_categories'] = array_map( 'absint' , $instance['selected_categories'] );
			$cat_IDs_header                  = array();

			$items_params = array(
				'post_type'      => 'portfolio',
				'nopaging'       => -1 === intval( $instance['posts_per_page'] ),
				'posts_per_page' => intval( $instance['posts_per_page'] ),
				'orderby'        => $instance['orderby'],
				'order'          => $instance['order'],
			);

			if ( is_array( $instance['selected_categories'] ) && ! empty( $instance['selected_categories'] ) ) {
				$items_params['tax_query'] = array(
					array(
						'taxonomy' => 'portfolio_category',
						'terms'    => $instance['selected_categories'],
					),
				);
				$cat_IDs_header = $instance['selected_categories'];
			}

			$items = new WP_Query( $items_params );

			echo $args['before_widget'];

			switch ( $instance['layout'] ) {
				case 'slider':
					?>
					<div class="portfolio-grid  portfolio-grid--slider" data-type="slider">
						<?php $this->item_categories_header( $instance['widget_title'], $instance['title'], $args['widget_id'], $cat_IDs_header, true, $args ); ?>
						<div id="portfolio-grid-<?php echo esc_attr( $args['widget_id'] ); ?>" class="carousel  slide" data-ride="carousel" data-interval="false">
							<div class="carousel-inner  js-wpg-items" role="listbox">
								<div class="carousel-item  active">
									<div class="row">
										<?php
										$i = 0;
										while ( $items->have_posts() ) {
											if ( $i !== 0 && $i % 4 === 0 ) {
												echo '</div></div><div class="carousel-item"><div class="row">';
											}

											$items->the_post();
											$this->single_item_html();

											$i++;
										}
										wp_reset_postdata();

										if ( array_key_exists( 'add_cta', $instance ) && 'yes' === $instance['add_cta'] ) {
											if ( $i !== 0 && $i % 4 === 0 ) {
												echo '</div></div><div class="carousel-item"><div class="row">';
											}

											$this->single_dummy_item_html( $instance['cta_title'], $instance['cta_text'], $instance['cta_btn'], $instance['cta_link'] );
										}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php

					break;

				default: // List all in a grid.
					?>
					<div class="portfolio-grid  portfolio-grid--grid" data-type="grid">
					<?php $this->item_categories_header( $instance['widget_title'], $instance['title'], $args['widget_id'], $cat_IDs_header, false ); ?>
						<div class="js-wpg-items">
							<div class="row">
							<?php
							$i = 0;
							while ( $items->have_posts() ) {
								if ( $i !== 0 && $i % 4 === 0 ) {
									echo '</div><div class="row">';
								}

								$items->the_post();
								$this->single_item_html();

								$i++;
							}
							wp_reset_postdata();

							if ( 'yes' === $instance['add_cta'] ) {
								if ( $i !== 0 && $i % 4 === 0 ) {
									echo '</div><div class="row">';
								}

								$this->single_dummy_item_html( $instance['cta_title'], $instance['cta_text'], $instance['cta_btn'], $instance['cta_link'] );
							}
							?>
							</div>
						</div>
					</div>
					<?php

					break;
			}

			echo $args['after_widget'];
		}

		/**
		 * Single portfolio item HTML output.
		 */
		private function single_item_html() {
			$label              = get_field( 'label' );
			$price              = get_field( 'price' );
			$specification_text = get_field( 'specification_text' );

			?>
			<div class="col-xs-12  col-sm-6  col-lg-3  js-wpg-item" data-categories="<?php echo implode( ',', array_keys( AdrenalineHelpers::get_custom_categories( get_the_ID(), 'portfolio_category' ) ) ); ?>">
				<div class="portfolio-grid__item">
					<?php if ( has_post_thumbnail() ) : ?>
						<a class="portfolio-grid__image  js-wpg-card" href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'pw-latest-news', array( 'class' => 'card-img-top  portfolio-grid__card-img' ) ); ?>
							<?php if ( ! empty( $specification_text ) ) : ?>
								<div class="portfolio-grid__specification">
									<i class="fa <?php echo esc_attr( get_field( 'specification_icon' ) ); ?>" aria-hidden="true"></i> <?php echo esc_html( $specification_text ); ?>
								</div>
							<?php endif; ?>
						</a>
					<?php endif; ?>
					<div class="portfolio-grid__content">
						<a class="h4  portfolio-grid__item-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						<?php if ( ! empty( $price ) && get_field( 'show_price' ) ) : ?>
							<p class="portfolio-grid__price"><?php echo wp_kses_post( $price ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $label ) ) : ?>
							<div class="portfolio-grid__label">
								<?php echo wp_kses_post( get_field( 'label' ) ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * CTA portfolio item HTML output.
		 *
		 * @param string $title The title.
		 * @param string $text  The text.
		 * @param string $btn   The button text.
		 * @param string $link  The CTA URL.
		 */
		private function single_dummy_item_html( $title, $text, $btn, $link ) {
			?>
			<div class="col-xs-12  col-sm-6  col-lg-3  js-wpg-item" data-categories="">
				<div class="portfolio-grid__card  portfolio-grid__card--dummy  js-wpg-card" href="<?php the_permalink(); ?>">
					<div class="portfolio-grid__card-block  text-xs-center">
						<span class="fa fa-envelope fa-3x"></span>
						<h5 class="portfolio-grid__card-title"><?php echo wp_kses_post( $title ); ?></h5>
						<p class="portfolio-grid__card-text"><?php echo wp_kses_post( $text ); ?></p>
						<a href="<?php echo esc_url( $link ); ?>" class="portfolio-grid__cta  btn  btn-primary"><?php echo esc_html( $btn ); ?></a>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * The widget header output.
		 *
		 * @param string  $widget_title  The widget title.
		 * @param string  $label_for_all The 'All' button text.
		 * @param string  $widget_id     The ID of the widget.
		 * @param array   $cat_IDs       The array of all category IDs.
		 * @param boolean $is_slider     True if the widget is set to slider mode.
		 * @param array   $args          Widget arguments.
		 */
		private function item_categories_header( $widget_title, $label_for_all, $widget_id, $cat_IDs = array(), $is_slider, $args = array() ) {
			?>
				<nav class="portfolio-grid__header">
					<a href="#" class="portfolio-grid__mobile-filter  js-filter  btn  btn-primary  btn-sm  hidden-lg-up">
						<span class="fa  fa-filter"></span> &nbsp;
						<?php esc_html_e( 'FILTER', 'adrenaline-pt' ); ?>
					</a>

					<?php if ( ! empty( $widget_title ) ) : ?>
						<h3 class="portfolio-grid__title"><?php echo wp_kses_post( $widget_title ); ?></h3>
					<?php endif; ?>

					<ul class="portfolio-grid__nav  js-wpg-nav-holder">
						<?php if ( ! empty( $label_for_all ) ) : ?>
							<li class="portfolio-grid__nav-item  is-active">
								<a href="#<?php echo esc_attr( sanitize_title( $widget_id . '-all' ) ); ?>" data-category="*" class="portfolio-grid__nav-link  js-wpg-nav"><?php echo esc_html( $label_for_all ) ?></a>
							</li>
						<?php endif; ?>
						<?php foreach ( $this->get_categories( $cat_IDs ) as $category ) : ?>
							<li class="portfolio-grid__nav-item">
								<a href="#<?php echo esc_attr( sanitize_title( $widget_id . '-' . $category->name ) ); ?>" data-category="<?php echo esc_attr( $category->slug ); ?>" class="portfolio-grid__nav-link  js-wpg-nav"><?php echo esc_html( $category->name ); ?></a>
							</li>
						<?php endforeach; ?>
					</ul>

					<?php if ( $is_slider ) : ?>
						<div class="portfolio-grid__arrows">
							<a class="portfolio-grid__arrow  portfolio-grid__arrow--left" href="#portfolio-grid-<?php echo esc_attr( $args['widget_id'] ); ?>" role="button" data-slide="prev">
								<span class="fa fa-long-arrow-left" aria-hidden="true"></span>
								<span class="sr-only"><?php esc_html_e( 'Previous', 'adrenaline-pt' ); ?></span>
							</a><a class="portfolio-grid__arrow  portfolio-grid__arrow--right" href="#portfolio-grid-<?php echo esc_attr( $args['widget_id'] ); ?>" role="button" data-slide="next">
								<span class="fa fa-long-arrow-right" aria-hidden="true"></span>
								<span class="sr-only"><?php esc_html_e( 'Next', 'adrenaline-pt' ); ?></span>
							</a>
						</div>
					<?php endif; ?>
				</nav>

			<?php
		}

		/**
		 * Get categories for the post type this widget is displaying
		 *
		 * @param  array $cat_IDs Array of all selected category IDs.
		 * @return array          Array of stdObj(WP_Term).
		 */
		protected function get_categories( $cat_IDs = array() ) {
			$params = array(
				'taxonomy' => 'portfolio_category',
				'orderby'  => 'count',
				'order'    => 'desc',
			);

			if ( ! empty( $cat_IDs ) ) {
				$params['include'] = $cat_IDs;
				$params['orderby'] = 'include';
				$params['order']   = 'asc';
			}

			return get_categories( $params );
		}

		/**
		 * Get sorted categories for the post type this widget is displaying for the widget settings.
		 * Always get all categories. First get the sorted categories and then the rest (new categories).
		 *
		 * @param  array $cat_order Array of all sorted category IDs.
		 * @return array            Array of stdObj(WP_Term).
		 */
		protected function get_all_sorted_categories( $cat_order = array() ) {
			$params = array(
				'taxonomy' => 'portfolio_category',
				'orderby'  => 'count',
				'order'    => 'desc',
			);

			$categories = array();

			if ( ! empty( $cat_order ) ) {

				// Get other (new) categories.
				$params['exclude'] = $cat_order;
				$new_categories    = get_categories( $params );

				// Get already sorted categories.
				$sorted_categories = $this->get_categories( $cat_order );

				// Combine both arrays.
				$categories = array_merge( $sorted_categories, $new_categories );
			}
			else {
				$categories = get_categories( $params );
			}

			return $categories;
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @param array $new_instance The new options.
		 * @param array $old_instance The previous options.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();

			$instance['widget_title']   = wp_kses_post( $new_instance['widget_title'] );
			$instance['title']          = sanitize_text_field( $new_instance['title'] );
			$instance['layout']         = sanitize_text_field( $new_instance['layout'] );
			$instance['posts_per_page'] = intval( $new_instance['posts_per_page'] );
			$instance['orderby']        = sanitize_text_field( $new_instance['orderby'] );
			$instance['order']          = sanitize_text_field( $new_instance['order'] );

			$instance['selected_categories'] = empty( $new_instance['selected_categories'] ) ? array() : array_keys( $new_instance['selected_categories'] );
			$instance['selected_categories'] = array_map( 'absint', $instance['selected_categories'] );
			$instance['categories_order']    = empty( $new_instance['categories_order'] ) ? array() : (array) $new_instance['categories_order'];
			$instance['categories_order']    = array_map( 'absint', $instance['categories_order'] );
			$instance['selected_categories'] = $this->sort_selected_categories( $instance['categories_order'], $instance['selected_categories'] );

			$instance['add_cta']   = empty( $new_instance['add_cta'] ) ? '' : sanitize_text_field( $new_instance['add_cta'] );
			$instance['cta_title'] = sanitize_text_field( $new_instance['cta_title'] );
			$instance['cta_text']  = sanitize_text_field( $new_instance['cta_text'] );
			$instance['cta_btn']   = sanitize_text_field( $new_instance['cta_btn'] );
			$instance['cta_link']  = esc_url_raw( $new_instance['cta_link'] );

			return $instance;
		}

		/**
		 * Sort selected categories.
		 *
		 * @param array $categories_order Array with keys = category id and values = order number.
		 * @param array $selected_categories Array with values = category id.
		 */
		private function sort_selected_categories( $categories_order, $selected_categories ) {
			$sorted_selected_categories = array();

			asort( $categories_order );
			foreach ( $categories_order as $cat_ID => $cat_order ) {
				if ( in_array( $cat_ID, $selected_categories, true )  ) {
					$sorted_selected_categories[] = $cat_ID;
				}
			}

			return $sorted_selected_categories;
		}

		/**
		 * Back-end widget form.
		 *
		 * @param array $instance The widget options.
		 */
		public function form( $instance ) {
			$widget_title   = empty( $instance['widget_title'] ) ? '' : $instance['widget_title'];
			$title          = empty( $instance['title'] ) ? '' : $instance['title'];
			$layout         = empty( $instance['layout'] ) ? 'grid' : $instance['layout'];
			$posts_per_page = empty( $instance['posts_per_page'] ) ? -1 : $instance['posts_per_page'];
			$orderby        = empty( $instance['orderby'] ) ? 'date' : $instance['orderby'];
			$order          = empty( $instance['order'] ) ? 'ASC' : $instance['order'];

			$selected_categories = empty( $instance['selected_categories'] ) ? array() : (array) $instance['selected_categories'];
			$selected_categories = array_map( 'absint', $selected_categories );
			$categories_order    = empty( $instance['categories_order'] ) ? array() : (array) $instance['categories_order'];

			// Prepare categories order array for $this->get_all_sorted_categories call.
			$sorted_categories_order = array_flip( $categories_order );
			ksort( $sorted_categories_order );

			$add_cta   = empty( $instance['add_cta'] ) ? '' : $instance['add_cta'];
			$cta_title = empty( $instance['cta_title'] ) ? 'Do you want' : $instance['cta_title'];
			$cta_text  = empty( $instance['cta_text'] ) ? 'something special?' : $instance['cta_text'];
			$cta_btn   = empty( $instance['cta_btn'] ) ? 'DROP US A LINE' : $instance['cta_btn'];
			$cta_link  = empty( $instance['cta_link'] ) ? 'http://www.proteusthemes.com/' : $instance['cta_link'];

			$orderby_options = array(
				'ID'       => esc_html__( 'Post ID', 'adrenaline-pt' ),
				'title'    => esc_html__( 'Title', 'adrenaline-pt' ),
				'date'     => esc_html__( 'Date', 'adrenaline-pt' ),
				'modified' => esc_html__( 'Last modified date', 'adrenaline-pt' ),
				'rand'     => esc_html__( 'Random order', 'adrenaline-pt' ),
			);

		?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'widget_title' ) ); ?>"><?php esc_html_e( 'Widget title', 'adrenaline-pt' ); ?></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'widget_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_title' ) ); ?>" value="<?php echo esc_attr( $widget_title ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Label for all items', 'adrenaline-pt' ); ?></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" placeholder="<?php esc_html_e( 'All', 'adrenaline-pt' ); ?>"  value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php esc_html_e( 'Layout', 'adrenaline-pt' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>">
					<option value="grid" <?php selected( $layout, 'grid' ); ?>><?php esc_html_e( 'Display all the items in grid (4 in a row)', 'adrenaline-pt' ); ?></option>
					<option value="slider" <?php selected( $layout, 'slider' ); ?>><?php esc_html_e( 'Display only one row of items, with arrows to see more', 'adrenaline-pt' ); ?></option>
				</select>
				<br>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>"><?php esc_html_e( 'Maximum number of items', 'adrenaline-pt' ); ?></label>
				<input class="widefat" type="number" min="-1" id="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_per_page' ) ); ?>" value="<?php echo esc_attr( $posts_per_page ); ?>" />
				<br>
				<small><?php esc_html_e( 'Set -1 to show all.', 'adrenaline-pt' ); ?></small>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order items by', 'adrenaline-pt' ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
					<?php
					foreach ( $orderby_options as $value => $label ) {
						printf( '<option value="%s" %s>%s</option>', esc_attr( $value ), selected( $orderby, $value, false ), esc_html( $label ) );
					}
					?>
				</select>
				<select id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
					<option value="ASC" <?php selected( $order, 'ASC' ); ?>><?php esc_html_e( 'Ascending', 'adrenaline-pt' ); ?></option>
					<option value="DESC" <?php selected( $order, 'DESC' ); ?>><?php esc_html_e( 'Descending', 'adrenaline-pt' ); ?></option>
				</select>
			</p>

			<p>
				<?php esc_html_e( 'Filter by categories', 'adrenaline-pt' ); ?>
				<ul class="portfolio-grid-setting__category-filter" style="padding-left: 15px; border-left: 2px solid #ddd;">
					<?php foreach ( $this->get_all_sorted_categories( $sorted_categories_order ) as $category ) : ?>
						<li class="portfolio-grid-setting__category-filter-item" style="background-color: #ddd; padding: 3px 5px 3px; width: 250px; cursor: move;">
							<span class="dashicons dashicons-sort" style="float: right;"></span>
							<input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'selected_categories' ) ); ?>-<?php echo absint( $category->cat_ID ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'selected_categories' ) ); ?>[<?php echo absint( $category->cat_ID ); ?>]" <?php echo in_array( absint( $category->cat_ID ), $selected_categories, true ) ? 'checked' : ''; ?> />
							<?php echo esc_html( $category->name ); ?>
							<input class="portfolio-grid-setting__category-filter-order" type="hidden" id="<?php echo esc_attr( $this->get_field_id( 'categories_order' ) ); ?>-<?php echo absint( $category->cat_ID ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'categories_order' ) ); ?>[<?php echo absint( $category->cat_ID ); ?>]" value="<?php echo empty( $categories_order[ $category->cat_ID ] ) ? 99 : absint( $categories_order[ $category->cat_ID ] ); ?>" />
						</li>
					<?php endforeach; ?>
				</ul>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $add_cta, 'yes' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'add_cta' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'add_cta' ) ); ?>" value="yes" data-controlling="#<?php echo esc_attr( $this->get_field_id( 'add_cta' ) ); ?>_panel" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'add_cta' ) ); ?>"><?php esc_html_e( 'Add CTA (click to action) project.', 'adrenaline-pt' ); ?></label>
			</p>

			<div id="<?php echo esc_attr( $this->get_field_id( 'add_cta' ) ); ?>_panel" style="padding-left: 15px; border-left: 2px solid #ddd;">
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'cta_title' ) ); ?>"><?php esc_html_e( 'CTA title', 'adrenaline-pt' ); ?></label>
					<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'cta_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cta_title' ) ); ?>" value="<?php echo esc_attr( $cta_title ); ?>" />
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'cta_text' ) ); ?>"><?php esc_html_e( 'CTA text', 'adrenaline-pt' ); ?></label>
					<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'cta_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cta_text' ) ); ?>" value="<?php echo esc_attr( $cta_text ); ?>" />
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'cta_btn' ) ); ?>"><?php esc_html_e( 'CTA button', 'adrenaline-pt' ); ?></label>
					<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'cta_btn' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cta_btn' ) ); ?>" value="<?php echo esc_attr( $cta_btn ); ?>" />
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'cta_link' ) ); ?>"><?php esc_html_e( 'CTA link', 'adrenaline-pt' ); ?></label>
					<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'cta_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cta_link' ) ); ?>" value="<?php echo esc_attr( $cta_link ); ?>" />
				</p>
			</div>

			<!-- controls for the CTA and the sortable categories  -->
			<script>
				jQuery(function($) {
					var fieldIds = [
						'<<?php echo esc_attr( $this->get_field_id( 'add_cta' ) ); ?>>'.slice(1, -1)
					];

					var checkboxHandler = function (ev) {
						var $panel = $($(ev.currentTarget).data('controlling'));
						if( $(ev.currentTarget).is(':checked') ) {
							$panel.slideDown();
						}
						else{
							$panel.slideUp();
						}
					};

					$(fieldIds.map(function (id) { return '#' + id; }).join(', '))
						.change(checkboxHandler)
						.trigger('change');

					// Make category filters sortable and trigger sortable "update" event to index categories order.
					$( '.portfolio-grid-setting__category-filter' )
						.sortable({
							items: '.portfolio-grid-setting__category-filter-item'
						})
						.on( 'sortupdate', function() {
							$( this ).find( '.portfolio-grid-setting__category-filter-order' ).each( function( index ) {
								$( this ).val( index );
							});
						})
						.trigger( 'sortupdate' );
				});
			</script>

			<?php
		}
	}
	register_widget( 'PW_Portfolio_Grid' );
}
