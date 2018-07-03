<?php
/**
 * The page title part of the header for portfolio/events
 *
 * @package adrenaline-pt
 */

$label              = get_field( 'label' );
$specification_text = get_field( 'specification_text' );

?>

<header class="page-header-portfolio  js-sticky-desktop-option">
	<!-- Featured Image -->
	<?php if ( 'featured-image' === get_field( 'header' ) && has_post_thumbnail() ) : ?>
		<div class="page-header-portfolio__image-container  js-object-fit-fallback">
			<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'img-fluid  page-header-portfolio__image', 'sizes' => '(min-width: 992px) calc(100vw - 22.2rem), (min-width: 1200px) calc(100vw - 27.8rem), 100vw' ) ); ?>
		</div>
	<?php endif; ?>

	<?php if ( 'slider' === get_field( 'header' ) && have_rows( 'slider' ) ) : ?>
		<?php
			// Parameters for the slick carousel slider.
			$slick_data = apply_filters( 'pt-adrenaline/portfolio_slick_carousel_data', array(
				'vertical'       => true,
				'dots'           => true,
				'appendArrows'   => '.js-sc-portfolio-navigation',
				'appendDots'     => '.js-sc-portfolio-navigation',
				'prevArrow'      => '<button type="button" class="slick-prev  slick-arrow"><span class="screen-reader-text">' . esc_html__( 'Previous', 'adrenaline-pt' ) . '</span><i class="fa fa-long-arrow-left" aria-hidden="true"></i></button>',
				'nextArrow'      => '<button type="button" class="slick-next  slick-arrow"><span class="screen-reader-text">' . esc_html__( 'Next', 'adrenaline-pt' ) . '</span><i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>',
				'responsive'     => array(
					array(
						'breakpoint' => 992,
						'settings'   => array(
							'dots'         => false,
							'vertical'     => false,
							'appendArrows' => '.js-sc-portfolio-slider',
						),
					),
				),
			) );
		?>

		<div class="page-header-portfolio__slider-container">
			<div class="page-header-portfolio__slider  js-sc-portfolio-slider" data-slick='<?php echo wp_json_encode( $slick_data ); ?>'>
				<?php
					$slider_counter  = 0;
					while ( have_rows( 'slider' ) ) :
						the_row();
						$slider_counter++;

						$slide_image        = get_sub_field( 'image' );
						$slider_src_img     = wp_get_attachment_image_src( absint( $slide_image ), 'adrenaline-jumbotron-slider-s' );
						$slide_image_srcset = AdrenalineHelpers::get_image_srcset( $slide_image, array( 'adrenaline-jumbotron-slider-l', 'adrenaline-jumbotron-slider-m', 'adrenaline-jumbotron-slider-s' ) );
				?>
					<div class="portfolio-carousel-item">
						<img src="<?php echo esc_url( $slider_src_img[0] ); ?>" srcset="<?php echo esc_attr( $slide_image_srcset ); ?>" sizes="100vw" alt="<?php the_title(); ?>">
					</div>
				<?php endwhile; ?>
			</div>

			<div class="page-header-portfolio__navigation-container">
				<div class="page-header-portfolio__navigation  js-sc-portfolio-navigation">
					<div class="page-header-portfolio__slide-number">
						<span class="js-sc-portfolio-current-number"><?php printf( '%02d', 1 ); ?></span><?php printf( ' / %02d', absint( $slider_counter ) ); ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="page-header-portfolio__content-container">
		<!-- CTA button -->
		<?php
		$cta_type       = get_field( 'cta_button_type' );
		$cta_text       = get_field( 'cta_button_text' );
		$cta_new_tab    = get_field( 'open_in_new_tab' );
		$cta_url        = '';

		// Setup correct URL.
		if ( 'custom-url' === $cta_type ) {
			$cta_url = get_field( 'cta_button_custom_url' );
		}
		elseif ( 'wc-product' === $cta_type && AdrenalineHelpers::is_woocommerce_active() ) {
			$product_obj = get_field( 'cta_button_product_link' );
			$product_id  = ! empty( $product_obj ) ? absint( $product_obj->ID ) : '';
			if ( ! empty( $product_id ) ) {
				$cta_url = add_query_arg( 'add-to-cart', esc_attr( $product_id ), AdrenalineHelpers::get_woocommerce_cart_url() );
			}
		}

		if ( ! empty( $cta_url ) ) :
		?>
		<div class="page-header-portfolio__cta-container">
			<a class="btn  btn-primary  btn-block  page-header-portfolio__cta" href="<?php echo esc_url( $cta_url ); ?>" target="<?php echo ! empty( $cta_new_tab ) ? '_blank' : '_self'; ?>"><?php echo ! empty( $cta_text ) ? esc_html( $cta_text ) : esc_html__( 'Book Now', 'adrenaline-pt' ); ?></a>
		</div>
		<?php endif; ?>

		<div class="page-header-portfolio__content">
			<!-- Price -->
			<?php if ( ( $price = get_field( 'price' ) ) && get_field( 'show_price' ) ) : ?>
				<p class="page-header-portfolio__price"><?php echo wp_kses_post( $price ); ?></p>
			<?php endif; ?>
			<!-- Label -->
			<?php if ( ! empty( $label ) ) : ?>
				<div class="page-header-portfolio__label">
					<?php echo wp_kses_post( get_field( 'label' ) ); ?>
				</div>
			<?php endif; ?>
			<!-- Title -->
			<h2 class="page-header-portfolio__title"><?php the_title(); ?></h2>
			<!-- Specification -->
			<?php if ( ! empty( $specification_text ) ) : ?>
				<div class="page-header-portfolio__specification">
					<i class="fa <?php echo esc_attr( get_field( 'specification_icon' ) ); ?>" aria-hidden="true"></i> <?php echo esc_html( $specification_text ); ?>
				</div>
			<?php endif; ?>
			<!-- Text -->
			<div class="page-header-portfolio__text">
				<?php echo wp_kses_post( get_field( 'short_description' ) ); ?>
			</div>
		</div>
	</div>
</header>
