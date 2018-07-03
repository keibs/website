<?php
/**
 * Template part for displaying blog sidebar
 *
 * @package adrenaline-pt
 */

$adrenaline_sidebar = get_field( 'sidebar', (int) get_option( 'page_for_posts' ) );

if ( ! $adrenaline_sidebar ) {
	$adrenaline_sidebar = 'left';
}

if ( 'none' !== $adrenaline_sidebar && is_active_sidebar( 'blog-sidebar' ) ) : ?>
	<div class="col-xs-12  col-lg-3<?php echo 'left' === $adrenaline_sidebar ? '  pull-lg-9' : ''; ?>">
		<div class="sidebar" role="complementary">
			<?php dynamic_sidebar( apply_filters( 'adrenaline_blog_sidebar', 'blog-sidebar', get_the_ID() ) ); ?>
		</div>
	</div>
<?php endif; ?>
