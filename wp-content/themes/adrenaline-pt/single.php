<?php
/**
 * The template for displaying all single posts.
 *
 * @package adrenaline-pt
 */

get_header();

$adrenaline_sidebar = get_field( 'sidebar', (int) get_option( 'page_for_posts' ) );

if ( ! $adrenaline_sidebar ) {
	$adrenaline_sidebar = 'left';
}

get_template_part( 'template-parts/page-header-single' );

?>

	<div id="primary" class="content-area  container">
		<div class="row">
			<main id="main" class="site-main  col-xs-12<?php echo 'left' === $adrenaline_sidebar ? '  site-main--left  col-lg-9  push-lg-3' : ''; ?><?php echo 'right' === $adrenaline_sidebar ? '  site-main--right  col-lg-9' : ''; ?>" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'template-parts/content', 'single' ); ?>

					<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>

					<!-- Post Navigation -->
					<?php
					$prev_post = get_adjacent_post( false, '', false );
					$next_post = get_adjacent_post( false, '', true );
					?>

					<div class="post-navigation__container">
						<a class="post-navigation  post-navigation--previous" href="<?php echo get_permalink( $prev_post ); ?>">
							<div class="post-navigation__text  post-navigation__text--previous">
								<?php esc_html_e( 'Previous reading' , 'adrenaline-pt' ); ?>
							</div>
							<div class="post-navigation__title  post-navigation__title--previous">
								<?php echo get_the_title( $prev_post ); ?>
							</div>
						</a>
						<a class="post-navigation  post-navigation--next" href="<?php echo get_permalink( $next_post ); ?>">
							<div class="post-navigation__text  post-navigation__text--next">
								<?php esc_html_e( 'Next reading' , 'adrenaline-pt' ); ?>
							</div>
							<div class="post-navigation__title  post-navigation__title--next">
								<?php echo get_the_title( $next_post ); ?>
							</div>
						</a>
					</div>

				<?php endwhile; // End of the loop. ?>

			</main><!-- #main -->

			<?php get_template_part( 'template-parts/sidebar', 'blog' ); ?>

		</div>
	</div><!-- #primary -->

<?php get_footer(); ?>
