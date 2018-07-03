<?php
/**
 * The page title part of the header for single post
 *
 * @package adrenaline-pt
 */

?>

<header class="page-header-single  js-sticky-desktop-option">
	<!-- Featured Image -->
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="page-header-single__image-container  js-object-fit-fallback">
			<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'img-fluid  page-header-single__image  u-photo', 'sizes' => '(min-width: 992px) calc(100vw - 22.2rem), (min-width: 1200px) calc(100vw - 27.8rem), 100vw' ) ); ?>
		</div>
	<?php endif; ?>
	<div class="page-header-single__content">
		<!-- Categories -->
		<?php if ( has_category() ) : ?>
			<div class="page-header-single__categories"><?php the_category( ' ' ); ?></div>
		<?php endif; ?>
		<!-- Title -->
		<?php the_title( '<h1 class="page-header-single__title  p-name">', '</h1>' ); ?>
		<!-- Meta Container -->
		<div class="page-header-single__meta">
			<!-- Author -->
			<span class="page-header-single__author"><?php printf( '%1$s %2$s', esc_html__( 'Posted by' , 'adrenaline-pt' ), wp_kses_post( AdrenalineHelpers::get_author_posts_link( 'page-header-single__author-link  p-author' ) ) ); ?></span>
			<!-- Date -->
			<time class="page-header-single__date  dt-published" datetime="<?php the_time( 'c' ); ?>"><?php echo get_the_date(); ?></time>
		</div>
	</div>
</header>
