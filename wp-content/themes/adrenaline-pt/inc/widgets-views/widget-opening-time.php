<?php echo $args['before_widget']; ?>

	<div class="time-table">

		<?php if ( ! empty( $instance['title'] ) ) : ?>
			<h2 class="h4  widget-title"><?php echo esc_html( $instance['title'] ); ?></h2>
		<?php endif; ?>

		<div class="inner-bg">
			<?php foreach ( $opening_times as $line ) : ?>
				<dl class="week-day <?php echo esc_html( $line['class'] ); ?>">
					<dt><?php echo esc_html( substr( $line['day'], 0, 3) ); ?></dt>
					<dd class="week-day__line"></dd>
					<dd><?php echo esc_html( $line['day-time'] ); ?></dd>
				</dl>
			<?php endforeach; ?>
		</div>

		<?php if ( ! empty( $instance['additional_info'] ) ) : ?>
			<div class="additional-info"><?php echo esc_html( $instance['additional_info'] ); ?></div>
		<?php endif; ?>

	</div>

<?php echo $args['after_widget']; ?>
