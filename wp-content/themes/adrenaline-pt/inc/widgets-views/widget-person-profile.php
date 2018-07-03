<?php echo $args['before_widget']; ?>

	<div class="card  person-profile  h-card">
		<?php if ( ! empty( $instance['image'] ) ) : ?>
		<img class="person-profile__image  wp-post-image" src="<?php echo esc_url( $instance['image'] ); ?>" alt="<?php echo esc_attr( $text['picture_of'] ) . ' ' . esc_attr( $instance['name'] ); ?>">
		<?php endif; ?>
		<?php if ( ! empty( $instance['carousel'] ) ) : ?>
			<?php
				// Parameters for the slick carousel slider.
				$slick_data = array(
					'adaptiveHeight' => true,
					'fade'           => true,
					'prevArrow'      => '<button type="button" class="slick-prev  slick-arrow"><span class="screen-reader-text">' . esc_html__( 'Previous', 'adrenaline-pt' ) . '</span><i class="fa fa-long-arrow-down" aria-hidden="true"></i></button>',
					'nextArrow'      => '<button type="button" class="slick-next  slick-arrow"><span class="screen-reader-text">' . esc_html__( 'Next', 'adrenaline-pt' ) . '</span><i class="fa fa-long-arrow-up" aria-hidden="true"></i></button>',
				);
			?>
			<div class="person-profile__carousel<?php echo 1 < count( $instance['carousel'] ) ? '  js-person-profile-initialize-carousel' : ''; ?>" <?php echo 1 < count( $instance['carousel'] ) ? "data-slick='" . wp_json_encode( $slick_data ) . "'" : ""; ?>>
				<?php foreach ( $instance['carousel'] as $carousel_item ) : ?>
					<div class="person-profile__carousel-item">
						<?php if ( 'image' === $carousel_item['type'] && ! empty( $carousel_item['url'] ) ) : ?>
							<img class="person-profile__image  wp-post-image  u-photo" src="<?php echo esc_url( $carousel_item['url'] ); ?>" alt="<?php echo esc_attr( $text['picture_of'] ) . ' ' . esc_attr( $instance['name'] ); ?>">
						<?php elseif ( 'video' === $carousel_item['type'] && ! empty( $carousel_item['url'] ) ) : ?>
							<?php
								$video_class = '';
								if ( strstr( $carousel_item['url'], 'youtube.com/' ) ) {
									$video_class = '  js-carousel-item-yt-video';
								}
								elseif ( strstr( $carousel_item['url'], 'vimeo.com/' ) ) {
									$video_class = '  js-carousel-item-vimeo-video';
								}
							?>
							<div class="person-profile__carousel-item--video<?php echo esc_attr( $video_class ); ?>">
								<?php
									echo wp_oembed_get(
										esc_url( $carousel_item['url'] ),
										array(
											'api' => '1',
											'player_id' => uniqid( 'pt-sc-pp-video-' ),
										)
									);
								?>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<div class="card-block  person-profile__container">
			<div class="person-profile__content">
				<h4 class="card-title  person-profile__name  p-name"><?php echo esc_html( $instance['name'] ); ?></h4>
				<?php if ( ! empty( $instance['label'] ) ) : ?>
					<span class="person-profile__label  p-label"><?php echo esc_html( $instance['label'] ); ?></span>
				<?php endif; ?>
				<p class="card-text  person-profile__description"><?php echo wp_kses_post( $instance['description'] ); ?></p>
				<?php if ( ! empty( $instance['skills'] ) ) : ?>
				<div class="person-profile__skills">
					<?php foreach ( $instance['skills'] as $skill ) : ?>
						<div class="person-profile__skill">
							<?php echo esc_html( $skill['name'] ); ?>
							<?php if ( ! ( 'nothing' === $skill['rating'] ) ) : ?>
								<span class="person-profile__skill-rating">
									<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
										<?php if ( $i <= absint( $skill['rating'] ) ) : ?>
											<i class="fa fa-star" aria-hidden="true"></i>
										<?php else : ?>
											<i class="fa fa-star-o" aria-hidden="true"></i>
										<?php endif; ?>
									<?php endfor; ?>
								</span>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<?php if ( ! empty( $instance['tags'] ) ) : ?>
					<div class="person-profile__tags">
						<?php foreach ( $instance['tags'] as $tag ) : ?>
							<div class="person-profile__tag">
								<?php echo esc_html( $tag ); ?>
							</div>
					<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $instance['social_icons'] ) ) : ?>
				<div class="person-profile__social-icons">
					<?php esc_html_e( 'Social:' , 'adrenaline-pt' ); ?>
					<?php foreach ( $instance['social_icons'] as $icon ) : ?>
						<a class="person-profile__social-icon" href="<?php echo esc_url( $icon['link'] ); ?>" target="<?php echo ( ! empty( $instance['new_tab'] ) ) ? '_blank' : '_self' ?>"><i class="fa  <?php echo esc_attr( $icon['icon'] ); ?>"></i></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

<?php echo $args['after_widget']; ?>
