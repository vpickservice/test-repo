<?php
if( !isset( $image_size ) ){
	$image_size = 'post-thumbnail';
}
?>
<div class="embed-responsive embed-responsive-16by9">
	<?php the_post_thumbnail( $image_size, array( 'class' => 'embed-responsive-item' ) ) ?>
</div>