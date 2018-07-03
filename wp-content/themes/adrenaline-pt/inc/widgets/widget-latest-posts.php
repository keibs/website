<?php
/**
 * Latest Posts Widget
 *
 * @package adrenaline-pt
 */

if ( ! class_exists( 'PW_Latest_Posts' ) ) {
	class PW_Latest_Posts extends WP_Widget {
		private $widget_id_base, $widget_class, $widget_name, $widget_description;

		public function __construct() {
			// Basic widget settings.
			$this->widget_id_base     = 'latest_posts';
			$this->widget_class       = 'widget-latest-posts';
			$this->widget_name        = esc_html__( 'Latest Posts', 'adrenaline-pt' );
			$this->widget_description = esc_html__( 'Four latest posts in a grid like display with more news link.', 'adrenaline-pt' );

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
			// Get the latest 4 posts.
			$posts_args = array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => 4,
				'ignore_sticky_posts' => true,
			);
			$latest_posts = new WP_Query( $posts_args );

			// Make the excerpt length filterable.
			$excerpt_length = apply_filters( 'pt-adrenaline/latest_posts_excerpt_length', 12 );

			echo $args['before_widget'];
		?>
			<?php if ( $latest_posts->have_posts() ) : ?>
			<div class="latest-posts">
				<?php
				while ( $latest_posts->have_posts() ) :
					$latest_posts->the_post();
					$is_last = ( $latest_posts->current_post + 1 ) === $latest_posts->post_count;

					if ( $is_last ) :
				?>
					<a class="latest-posts__item  latest-posts__item--more-news" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" target="_blank">
						<div class="h3  latest-posts__more-news"><?php esc_html_e( 'More News', 'adrenaline-pt' ); ?> <i class="fa fa-long-arrow-right" aria-hidden="true"></i></div>
					</a>
				<?php endif; ?>
					<a class="latest-posts__item" href="<?php the_permalink(); ?>" target="_blank" <?php echo has_post_thumbnail() ? 'style="background-image: url(' . esc_url( get_the_post_thumbnail_url( get_the_ID(), 'adrenaline-jumbotron-slider-m' ) ) . ')"' : ''; ?>>
						<div class="latest-posts__date-container">
							<div class="latest-posts__date">
								<?php echo get_the_date( 'F j' ); ?>
							</div>
						</div>
						<div class="latest-posts__content">
							<h3 class="h3  latest-posts__title"><?php the_title(); ?></h3>
							<?php if ( ! ( $is_last && 0 === $latest_posts->post_count % 2 ) ) : ?>
								<p class="latest-posts__text">
									<?php echo esc_html( AdrenalineHelpers::get_post_excerpt( get_the_excerpt(), get_the_content(), $excerpt_length ) ); ?>
								</p>
							<?php endif; ?>
						</div>
					</a>
				<?php
				endwhile;

				// Reset the loop post data.
				wp_reset_postdata();
				?>
			</div>
			<?php endif; ?>
		<?php
			echo $args['after_widget'];
		}

		/**
		 * Sanitize widget form values as they are saved.
		 * No Update method needed, since there are not settings to save!
		 *
		 * @param array $new_instance The new options.
		 * @param array $old_instance The previous options.
		 */
		public function update( $new_instance, $old_instance ) {}

		/**
		 * Back-end widget form.
		 *
		 * @param array $instance The widget options.
		 */
		public function form( $instance ) {
		?>
			<p>
				<?php esc_html_e( 'There are no settings for this widget. Four latest posts will be retrieved and displayed automatically!', 'adrenaline-pt' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'Note: please set the "Posts page" option in Settings -> Reading, so that the "More News" will link to the blog page.', 'adrenaline-pt' ); ?>
			</p>
		<?php
		}
	}
	register_widget( 'PW_Latest_Posts' );
}
