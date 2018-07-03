<?php
/**
 * Template part for meta data
 *
 * @package adrenaline-pt
 */

?>

<span class="meta__author"><i class="fa fa-user"></i> <?php the_author(); ?></span>
<a href="<?php the_permalink(); ?>"><time class="meta__item  meta__item--date" datetime="<?php the_time( 'c' ); ?>"><i class="fa fa-calendar-o"></i> <?php echo get_the_date(); ?></time></a>
<?php if ( has_category() ) : ?>
	<span class="meta__item  meta__item--categories"><i class="fa fa-tag"></i> <?php the_category( ', ' ); ?></span>
<?php endif; ?>
<?php if ( comments_open( get_the_ID() ) ) : // Only show comments count if the comments are open. ?>
	<span class="meta__item  meta__item--comments"><i class="fa fa-comment"></i> <a href="<?php comments_link(); ?>"><?php AdrenalineHelpers::pretty_comments_number(); ?></a></span>
<?php endif; ?>
<?php if ( has_tag() ) : ?>
	<span class="meta__item  meta__item--tags"><i class="fa fa-bookmark-o"></i> <?php the_tags( '', ', ' ); ?></span>
<?php endif; ?>
