<?php
/**
 * Single portfolio page template
 *
 * @package adrenaline-pt
 */

get_header();

get_template_part( 'template-parts/page-header-portfolio' );

?>

	<!-- Content Area -->
	<div id="primary" class="content-area  container">
		<main role="main" class="row">
			<div class="col-xs-12">
				<!-- Content -->
				<div class="portfolio__content">
				<?php
				// Content without page builder editor will not display if not in the WP loop.
				while ( have_posts() ) {
					the_post();

					// Display the content.
					the_content();
				}
				?>
				</div>
			</div>
		</main>
	</div>

<?php get_footer(); ?>
