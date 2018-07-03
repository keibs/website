<?php
/**
 * Footer
 *
 * @package adrenaline-pt
 */

$adrenaline_footer_widgets_layout = AdrenalineHelpers::footer_widgets_layout_array();
$adrenaline_footer_allowed_html   = array(
	'a'      => array(
		'class'   => array(),
		'href'   => array(),
		'target' => array(),
		'title'  => array(),
	),
	'em'     => array(),
	'strong' => array(),
	'img'    => array(
		'src'    => array(),
		'alt'    => array(),
		'width'  => array(),
		'height' => array(),
	),
	'span'   => array(
		'class'  => array(),
		'style'  => array(),
	),
	'i'      => array(
		'class'  => array(),
	),
);

// Top footer custom text.
$adrenaline_footer_top_info_text = get_theme_mod( 'footer_custom_text', '<div class="footer-top__text"><p class="h6">164 Edgefield St.<br>Richmond, VA 23223</p>+386 123 456<br>rush@info.com<br>adrenalin@info.com</div><div class="footer-top__social-icons">[fa icon="fa-facebook" href="#"][fa icon="fa-twitter" href="#"][fa icon="fa-linkedin" href="#"][fa icon="fa-facebook" href="#"]</div>' );

// Bottom footer texts.
$adrenaline_footer_bottom_left_txt         = get_theme_mod( 'footer_bottom_left_txt', '<i class="fa  fa-2x  fa-cc-paypal"></i> <i class="fa  fa-2x  fa-cc-mastercard"></i> <i class="fa  fa-2x  fa-cc-visa"></i> <i class="fa  fa-2x  fa-cc-amex"></i>' );
$adrenaline_footer_bottom_middle_left_txt  = get_theme_mod( 'footer_bottom_middle_left_txt', '<a href="#"><i class="fa fa-map-marker"></i> FIND US ON MAP</a>' );
$adrenaline_footer_bottom_middle_right_txt = get_theme_mod( 'footer_bottom_middle_right_txt', '<a href="https://www.proteusthemes.com/wordpress-themes/adrenaline/">Adrenaline Theme</a> - Made by ProteusThemes' );
$adrenaline_footer_bottom_right_txt        = get_theme_mod( 'footer_bottom_right_txt', sprintf( '&copy; %s All Rights Reserved', date( 'Y' ) ) );

?>

	<footer class="footer">
		<?php if ( ! empty( $adrenaline_footer_widgets_layout ) && is_active_sidebar( 'footer-widgets' ) ) : ?>
			<div class="container-fluid  footer-top">
				<?php if ( get_theme_mod( 'footer_logo_enabled', true ) ) : ?>
					<a class="footer-top__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php
						$footer_logo   = get_theme_mod( 'footer_logo_img', false );
						$footer_logo2x = get_theme_mod( 'footer_logo2x_img', false );

						if ( ! empty( $footer_logo ) ) :
						?>
							<img src="<?php echo esc_url( $footer_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" srcset="<?php echo esc_attr( $footer_logo ); ?><?php echo empty( $footer_logo2x ) ? '' : ', ' . esc_url( $footer_logo2x ) . ' 2x'; ?>" class="img-fluid" <?php echo AdrenalineHelpers::get_logo_dimensions( 'footer_logo_dimensions_array' ); ?> />
						<?php
						else :
						?>
							<span><?php echo esc_html( get_bloginfo( 'name' ) ) ?></span>
						<?php
						endif;
						?>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $adrenaline_footer_top_info_text ) ) : ?>
					<!-- Footer Top Left Widgets -->
					<div class="footer-top__info">
						<?php echo wp_kses_post( do_shortcode( $adrenaline_footer_top_info_text ) ); ?>
					</div>
				<?php endif; ?>
				<!-- Footer Top Widgets -->
				<div class="footer-top__widgets">
					<div class="row">
						<?php dynamic_sidebar( 'footer-widgets' ); ?>
					</div>
				</div>
				<div class="footer-top__back-to-top">
					<a class="footer-top__back-to-top-link  js-back-to-top" href="#"><?php esc_html_e( 'Back to top', 'adrenaline-pt' ); ?> <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
				</div>
			</div>
		<?php endif; ?>
		<div class="container-fluid  footer-bottom">
			<?php if ( ! empty( $adrenaline_footer_bottom_left_txt ) ) : ?>
				<div class="footer-bottom__left">
					<?php echo wp_kses( do_shortcode( $adrenaline_footer_bottom_left_txt ), $adrenaline_footer_allowed_html ); ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $adrenaline_footer_bottom_middle_left_txt ) ) : ?>
				<div class="footer-bottom__center">
					<?php echo wp_kses( do_shortcode( $adrenaline_footer_bottom_middle_left_txt ), $adrenaline_footer_allowed_html ); ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $adrenaline_footer_bottom_middle_right_txt ) || ! empty( $adrenaline_footer_bottom_right_txt ) ) : ?>
				<div class="footer-bottom__text">
					<?php if ( ! empty( $adrenaline_footer_bottom_middle_right_txt ) ) : ?>
						<div class="footer-bottom__text-left">
							<?php echo wp_kses( do_shortcode( $adrenaline_footer_bottom_middle_right_txt ), $adrenaline_footer_allowed_html ); ?>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $adrenaline_footer_bottom_right_txt ) ) : ?>
						<div class="footer-bottom__text-right">
							<?php echo wp_kses( do_shortcode( $adrenaline_footer_bottom_right_txt ), $adrenaline_footer_allowed_html ); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</footer>
	</div><!-- end of .boxed-container -->

	<?php wp_footer(); ?>
	</body>
</html>
