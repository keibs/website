<?php
/**
 * Template part for displaying portfolio posts on archive page.
 *
 * @package adrenaline-pt
 */

$blog_columns = get_theme_mod( 'blog_columns', 6 );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'grid-item', 'col-xs-12', 'col-sm-6', esc_attr( sprintf( 'col-lg-%s', $blog_columns ) ) ) ); ?>>
	<!-- Featured Image -->
	<?php if ( has_post_thumbnail() ) : ?>
		<header class="article__header">
			<a class="article__featured-image" href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'img-fluid' ) ); ?>
			</a>
		</header><!-- .article__header -->
	<?php endif; ?>

	<!-- Content Box -->
	<div class="article__content">
		<!-- Date -->
		<a href="<?php the_permalink(); ?>"><time class="article__date" datetime="<?php the_time( 'c' ); ?>"><?php echo get_the_date(); ?></time></a>
		<!-- Author -->
		<span class="article__author"><i class="fa  fa-user"></i> <?php echo esc_html__( 'By' , 'adrenaline-pt' ) . ' ' . get_the_author(); ?></span>
		<!-- Content -->
		<?php the_title( sprintf( '<h2 class="article__title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<p>
			<?php echo wp_kses_post( get_the_excerpt() ); ?>
		</p>
		<p>
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="more-link"><?php printf( esc_html__( 'Read more %s', 'adrenaline-pt' ), the_title( '<span class="screen-reader-text">', '</span>', false ) ); ?></a>
		</p>
	</div><!-- .article__content -->
</article><!-- .article -->
