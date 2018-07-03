<?php
/**
 * Template part for displaying posts.
 *
 * @package adrenaline-pt
 */

$blog_columns = get_theme_mod( 'blog_columns', 6 );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'h-entry', 'grid-item', 'col-xs-12', 'col-sm-6', esc_attr( sprintf( 'col-lg-%s', $blog_columns ) ) ) ); ?>>
	<!-- Featured Image and Date -->
	<?php if ( has_post_thumbnail() ) : ?>
		<header class="article__header">
			<a class="article__featured-image-link" href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'img-fluid  article__featured-image  u-photo' ) ); ?>
			</a>
			<!-- Date -->
			<a href="<?php the_permalink(); ?>"><time class="article__date  article__date--on-image  dt-published" datetime="<?php the_time( 'c' ); ?>"><?php echo get_the_date(); ?></time></a>
		</header>
	<?php endif; ?>

	<!-- Content Box -->
	<div class="article__content">
		<?php if ( ! has_post_thumbnail() ) : ?>
			<!-- Date -->
			<a href="<?php the_permalink(); ?>"><time class="article__date  dt-published" datetime="<?php the_time( 'c' ); ?>"><?php echo get_the_date(); ?></time></a>
		<?php endif; ?>
		<!-- Content -->
		<?php the_title( sprintf( '<h2 class="article__title  p-name"><a class="article__title-link  u-url" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<?php
		$adrenaline_is_excerpt = ( 1 === (int) get_option( 'rss_use_excerpt', 0 ) );
		if ( $adrenaline_is_excerpt ) : ?>
			<p class="e-content">
				<?php echo wp_kses_post( get_the_excerpt() ); ?>
			</p>
			<p>
				<a href="<?php echo esc_url( get_permalink() ); ?>" class="more-link"><?php printf( esc_html__( 'Read more %s', 'adrenaline-pt' ), the_title( '<span class="screen-reader-text">', '</span>', false ) ); ?></a>
			</p>
		<?php else :
			/* translators: %s: Name of current post */
			the_content( sprintf(
				esc_html__( 'Read more %s', 'adrenaline-pt' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
		endif;
		?>
		<div class="article__meta">
			<!-- Author -->
			<span class="article__author"><?php esc_html_e( 'Posted by' , 'adrenaline-pt' ) ?> <span class="p-author"><?php the_author(); ?></span></span>
			<!-- Categories -->
			<?php if ( has_category() ) : ?>
				<span class="article__categories"><?php the_category( ' ' ); ?></span>
			<?php endif; ?>
		</div>
	</div><!-- .article__content -->
</article><!-- .article -->
