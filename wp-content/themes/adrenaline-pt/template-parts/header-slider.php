<?php
/**
 * Header with slider for front page.
 *
 * @package adrenaline-pt
 */

?>

<header class="site-header  header  header--slider">
	<!-- Logo -->
	<a class="header__logo  header__logo--big" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php
		$slider_header_logo   = get_field( 'header_logo' );
		$slider_header_logo2x = get_field( 'header_retina_logo' );

		// Use the customizer logos, if the slider header logos are not set in ACFs.
		if ( empty( $slider_header_logo ) ) {
			$slider_header_logo   = get_theme_mod( 'logo_img', false );
			$slider_header_logo2x = get_theme_mod( 'logo2x_img', false );
		}

		if ( ! empty( $slider_header_logo ) ) :

		?>
			<img src="<?php echo esc_url( $slider_header_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" srcset="<?php echo esc_attr( $slider_header_logo ); ?><?php echo empty( $slider_header_logo2x ) ? '' : ', ' . esc_url( $slider_header_logo2x ) . ' 2x'; ?>" class="img-fluid" />
		<?php
		else :
			printf( '<%1$s class="h1  header__logo-text">%2$s</%1$s>', AdrenalineHelpers::is_slider_template() ? 'h1' : 'p', esc_html( get_bloginfo( 'name' ) ) );
		endif;
		?>
	</a>
	<!-- Slider -->
	<div class="header__slider">
		<?php
		// Only include the slick carousel if we defined some slides.
		if ( have_rows( 'slides' ) ) {
			get_template_part( 'template-parts/slick-carousel' );
		}
		?>
	</div>
	<!-- Toggle button for Main Navigation on mobile -->
	<button class="btn  btn-secondary  header__navbar-toggler  hidden-lg-up  js-sticky-mobile-option" type="button" data-toggle="collapse" data-target="#adrenaline-main-navigation"><i class="fa  fa-bars  hamburger"></i> <span><?php esc_html_e( 'MENU' , 'adrenaline-pt' ); ?></span></button>
	<!-- Header Info -->
	<div class="header__info  header-info">
		<div class="header-info__content">
			<?php echo wp_kses_post( get_field( 'header_info_text' ) ); ?>
		</div>
		<div class="header-info__link-container">
			<?php
				$header_info_link = get_field( 'header_info_link' );
				$header_info_link_icon = get_field( 'header_info_link_icon' );
				if ( ! empty( $header_info_link ) ) :
			?>
			<a class="header-info__link" href="<?php echo esc_url( $header_info_link ); ?>">
			<?php endif; ?>
				<?php if ( ! empty( $header_info_link_icon ) ) : ?>
				<i class="fa  <?php echo esc_attr( $header_info_link_icon ); ?>" aria-hidden="true"></i>
				<?php endif; ?>
				<?php echo wp_kses_post( get_field( 'header_info_link_text' ) ); ?>
			<?php if ( ! empty( $header_info_link ) ) : ?>
			</a>
			<?php endif; ?>
		</div>
		<?php if ( have_rows( 'header_info_social_icons' ) ) : ?>
		<div class="header-info__social">
			<?php
			while ( have_rows( 'header_info_social_icons' ) ) :
				the_row();
			?>
				<a class="header-info__social-link" href="<?php echo esc_url( get_sub_field( 'url' ) ); ?>" target="_blank">
					<i class="fa <?php echo esc_attr( get_sub_field( 'icon' ) ); ?>" aria-hidden="true"></i>
				</a>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>
	</div>
	<!-- Main Navigation -->
	<div class="header__navigation-container">
		<div class="container">
			<nav class="header__navigation  collapse  navbar-toggleable-md  js-sticky-desktop-option" id="adrenaline-main-navigation" aria-label="<?php esc_html_e( 'Main Menu', 'adrenaline-pt' ); ?>">
				<?php
				if ( has_nav_menu( 'main-menu' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'main-menu',
						'container'      => false,
						'menu_class'     => 'main-navigation  js-main-nav  js-dropdown',
						'walker'         => new Aria_Walker_Nav_Menu(),
						'items_wrap'     => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
					) );
				}
				?>
			</nav>
		</div>
	</div>
</header>
