<?php
/**
 * Default header for the majority of pages.
 *
 * @package adrenaline-pt
 */

?>

<header class="site-header  header">
	<!-- Logo -->
	<a class="header__logo  header__logo--default" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php
		$adrenaline_logo   = get_theme_mod( 'logo_img', false );
		$adrenaline_logo2x = get_theme_mod( 'logo2x_img', false );

		if ( ! empty( $adrenaline_logo ) ) :

		?>
			<img src="<?php echo esc_url( $adrenaline_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" srcset="<?php echo esc_attr( $adrenaline_logo ); ?><?php echo empty( $adrenaline_logo2x ) ? '' : ', ' . esc_url( $adrenaline_logo2x ) . ' 2x'; ?>" class="img-fluid" <?php echo AdrenalineHelpers::get_logo_dimensions(); ?> />
		<?php
		else :
			printf( '<%1$s class="h1  header__logo-text">%2$s</%1$s>', AdrenalineHelpers::is_slider_template() ? 'h1' : 'p', esc_html( get_bloginfo( 'name' ) ) );
		endif;
		?>
	</a>
	<!-- Toggle button for Main Navigation on mobile -->
	<button class="btn  btn-secondary  header__navbar-toggler  hidden-lg-up  js-sticky-mobile-option" type="button" data-toggle="collapse" data-target="#adrenaline-main-navigation"><i class="fa  fa-bars  hamburger"></i> <span><?php esc_html_e( 'MENU' , 'adrenaline-pt' ); ?></span></button>
	<!-- Main Navigation -->
	<nav class="header__navigation  collapse  navbar-toggleable-md" id="adrenaline-main-navigation" aria-label="<?php esc_html_e( 'Main Menu', 'adrenaline-pt' ); ?>">
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
	<!-- Widgets -->
	<div class="header__widgets">
		<!-- Header left widget area -->
		<?php if ( is_active_sidebar( 'header-left-widgets' ) ) : ?>
			<div class="header__widgets-left">
				<?php dynamic_sidebar( apply_filters( 'adrenaline_header_left_widgets', 'header-left-widgets', get_the_ID() ) ); ?>
			</div>
		<?php endif; ?>
		<!-- Header right widget area -->
		<?php if ( is_active_sidebar( 'header-right-widgets' ) ) : ?>
			<div class="header__widgets-right">
				<?php dynamic_sidebar( apply_filters( 'adrenaline_header_right_widgets', 'header-right-widgets', get_the_ID() ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</header>
