<?php
/**
 * The Header for Adrenaline Theme
 *
 * @package adrenaline-pt
 */

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php endif; ?>

		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
	<div class="boxed-container<?php echo ( is_single() && 'post' === get_post_type() ) ? '  h-entry' : ''; ?>">

	<?php
	/**
	 * Use the slider header variation, if the the slider page template is selected,
	 * otherwise use the default header layout.
	 */
	if ( is_page_template( 'template-front-page-slider.php' ) ) {
		get_template_part( 'template-parts/header', 'slider' );
	}
	else {
		get_template_part( 'template-parts/header', 'default' );
	}
	?>
