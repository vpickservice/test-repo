<div class="embed-responsive embed-responsive-16by9">
	<?php 
	$iframe_standard = get_post_meta( get_the_ID() , 'iframe_standard', true );
	if( !empty( $iframe_standard ) ){
		?>
		<iframe src="<?php echo esc_url( $iframe_standard ) ?>" class="embed-responsive-item"></iframe>
		<?php
	}
	else{
		if( !isset( $image_size ) ){
			$image_size = 'post-thumbnail';
		}
		echo '<a href="'.get_permalink().'">';
			the_post_thumbnail( $image_size, array( 'class' => 'embed-responsive-item' ) );
		echo '</a>';
	}
	?>
</div>