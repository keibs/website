<?php echo $args['before_widget']; ?>

	<div class="testimonials__container">

	<?php echo $args['before_title'] . wp_kses_post( $instance['title'] ) . $args['after_title']; ?>

		<div id="carousel-testimonials-<?php echo esc_attr( $args['widget_id'] ); ?>" class="carousel  slide  testimonials" data-ride="carousel" data-interval="<?php echo esc_attr( $instance['slider_settings'] ); ?>">
			<!-- Wrapper for slides -->
			<div class="carousel-inner" role="listbox">
				<div class="carousel-item active">
					<div class="row">
						<?php
						$counter = 0;
						foreach ( $testimonials as $testimonial ) :
							$counter++;
							echo wp_kses_post( $testimonial['more-at-once'] );
						?>
							<div class="col-xs-12  col-md-<?php echo esc_attr( $instance['spans'] ); ?>">
								<div class="testimonial">
									<blockquote class="testimonial__quote">
										<?php echo wp_kses_post( $testimonial['quote'] ); ?>
									</blockquote>
									<!-- Author Name -->
									<cite class="h6  testimonial__author">
										<?php echo esc_html( $testimonial['author'] ); ?>
									</cite>
									<!-- Author Description -->
									<?php if ( ! empty( $testimonial['author_description'] ) ) : ?>
										<div class="testimonial__author-description">
											<?php echo wp_kses_post( $testimonial['author_description'] ); ?>
										</div>
									<?php endif; ?>
									<!-- Ratings -->
									<?php if ( isset( $testimonial['display-ratings'] ) && $testimonial['display-ratings'] ) : ?>
										<div class="testimonial__rating">
											<?php foreach ( $testimonial['rating'] as $rating ) : ?>
												<i class="fa  fa-star"></i>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="testimonial__carousel-container">
			<?php if ( isset( $instance['navigation'] ) && $instance['navigation'] ) : ?>
				<a class="testimonial__carousel  testimonial__carousel--left" href="#carousel-testimonials-<?php echo esc_attr( $args['widget_id'] ); ?>" data-slide="prev"><i class="fa  fa-long-arrow-left" aria-hidden="true"></i><span class="sr-only" role="button"><?php echo esc_html( $text['next'] ); ?></span></a>
			<?php endif; ?>

			<?php if ( isset( $instance['navigation'] ) && $instance['navigation'] ) : ?>
				<a class="testimonial__carousel  testimonial__carousel--right" href="#carousel-testimonials-<?php echo esc_attr( $args['widget_id'] ); ?>" data-slide="next"><i class="fa  fa-long-arrow-right" aria-hidden="true"></i><span class="sr-only" role="button"><?php echo esc_html( $text['previous'] ); ?></span></a>
			<?php endif; ?>
		</div>

	</div>

<?php echo $args['after_widget']; ?>
