<?php
/**
 * 404 page
 *
 * @package adrenaline-pt
 */

get_header();

?>

<div class="error-404  container">
	<p class="h1  error-404__title"><?php esc_html_e( '404' , 'adrenaline-pt' ); ?></p>
	<p class="h2  error-404__subtitle"><?php esc_html_e( 'You landed on the wrong side of the page' , 'adrenaline-pt' ); ?></p>
	<p class="error-404__text">
	<?php
		printf(
			/* translators: the first %s for line break, the second and third %s for link to home page wrap */
			esc_html__( 'Page you are looking for is not here. %1$s Go %2$sHome%3$s or try to search:' , 'adrenaline-pt' ),
			'<br>',
			'<b><a href="' . esc_url( home_url( '/' ) ) . '">',
			'</a></b>'
		);
	?>
	</p>
	<div class="widget_search">
		<?php get_search_form(); ?>
	</div>
</div>

<?php get_footer(); ?>
