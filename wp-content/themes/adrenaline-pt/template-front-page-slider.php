<?php
/**
 * Template Name: Front Page With Slider
 *
 * @package adrenaline-pt
 */

get_header(); ?>

<div id="primary" class="content-area  container" role="main">
	<div class="article__content">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				the_content();
			}
		}
		?>
	</div>
</div>

<?php get_footer(); ?>
