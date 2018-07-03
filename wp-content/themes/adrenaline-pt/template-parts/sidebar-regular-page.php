<?php
/**
 * Template part for displaying page sidebar
 *
 * @package adrenaline-pt
 */

$adrenaline_sidebar = get_field( 'sidebar' );

if ( ! $adrenaline_sidebar ) {
	$adrenaline_sidebar = 'left';
}


if ( 'none' !== $adrenaline_sidebar && is_active_sidebar( 'regular-page-sidebar' ) ) : ?>
	<div class="col-xs-12  col-lg-3<?php echo 'left' === $adrenaline_sidebar ? '  pull-lg-9' : ''; ?>">
		<div class="sidebar" role="complementary">
			<?php dynamic_sidebar( apply_filters( 'adrenaline_regular_page_sidebar', 'regular-page-sidebar', get_the_ID() ) ); ?>
		</div>
	</div>
<?php endif; ?>
