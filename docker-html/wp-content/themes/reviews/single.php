<?php
/*=============================
	DEFAULT SINGLE
=============================*/
get_header();
the_post();

$post_pages = wp_link_pages( 
	array(
		'before' => '',
		'after' => '',
		'link_before'      => '<span>',
		'link_after'       => '</span>',
		'next_or_number'   => 'number',
		'nextpagelink'     => esc_html__( '&raquo;', 'reviews' ),
		'previouspagelink' => esc_html__( '&laquo;', 'reviews' ),			
		'separator'        => ' ',
		'echo'			   => 0
	) 
);
?>
<section class="single-blog">
	<input type="hidden" name="post-id" value="<?php the_ID() ?>">
	<div class="container">
		<div class="row">
			<div class="col-md-9">
				<div class="white-block single-item">
					<div class="blog-media">
						<?php $post_format = get_post_format(); ?>
						<?php 
							if( reviews_has_media() ){
								add_filter( 'wp_get_attachment_image_attributes', 'reviews_lazy_load_product_images');
								get_template_part( 'media/media', $post_format );
								remove_filter( 'wp_get_attachment_image_attributes', 'reviews_lazy_load_product_images');
							}
						?>
					</div>
					<div class="content-inner">
						<ul class="list-unstyled list-inline post-meta">
							<li class="single-small-time" title="<?php esc_attr_e( 'Creation time', 'reviews' ) ?>">
								<i class="fa fa-calendar-o"></i><?php the_time( 'F j, Y' ) ?>
							</li>
							<li title="<?php esc_attr_e( 'Number of comments', 'reviews' ) ?>">
								<i class="fa fa-comment-o"></i><?php comments_number( esc_html__( '0 comments', 'reviews' ), esc_html__( '1 comment', 'reviews' ), esc_html__( '% comments', 'reviews' ) ); ?>
							</li>
							<li title="<?php esc_attr_e( 'Author', 'reviews' ) ?>">
								<i class="fa fa-user"></i><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"> <?php echo get_the_author_meta('display_name'); ?></a>
							</li>
							<li title="<?php esc_attr_e( 'Post categories', 'reviews' ) ?>">
								<i class="fa fa-folder-open-o"></i> <?php echo reviews_the_category(); ?>
							</li>
						</ul>

						<h1 class="post-title size-h3"><?php the_title() ?></h1>
						
						<div class="post-content clearfix">
							<?php the_content(); ?>							
						</div>

					</div>
				</div>

				<?php 
				$tags = reviews_the_tags();
				if( !empty( $tags ) ):
				?>
					<div class="post-tags white-block">
						<div class="content-inner">
							<i class="fa fa-tags"></i> <?php esc_html_e( 'Tags: ', 'reviews' ); echo  $tags; ?>
						</div>
					</div>
				<?php
				endif;
				?>		
				
				<?php if( !empty( $post_pages ) ): ?>
					<div class="pagination">
						<?php echo $post_pages; ?>
					</div>
				<?php endif; ?>
								
				<?php comments_template( '', true ) ?>

			</div>

			<?php include( get_theme_file_path( 'includes/posts-sidebar.php' ) ) ?>
			
		</div>
	</div>
</section>
<?php get_footer(); ?>