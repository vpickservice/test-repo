<a href="javascript:;" class="to_top btn">
	<span class="fa fa-angle-up"></span>
</a>

<?php
get_sidebar( 'footer' );
?>

<?php
$copyrights = reviews_get_option( 'copyrights' );
$facebook_link = reviews_get_option( 'copyrights-facebook' );
$twitter_link = reviews_get_option( 'copyrights-twitter' );
$google_link = reviews_get_option( 'copyrights-google' );
$linkedin_link = reviews_get_option( 'copyrights-linkedin' );
$tumblr_link = reviews_get_option( 'copyrights-tumblr' );
$instagram_link = reviews_get_option( 'copyrights-instagram' );
$youtube_link = reviews_get_option( 'copyrights-youtube' );
if( !empty( $copyrights ) || !empty( $facebook_link ) || !empty( $twitter_link ) || !empty( $google_link ) || !empty( $linkedin_link ) || !empty( $tumblr_link ) || !empty( $instagram_link ) || !empty( $youtube_link ) ):
?>
	<section class="copyrights">
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<p><?php                 
                        echo wp_kses_post( $copyrights );
					?></p>
				</div>
				<div class="col-md-4">
					<p class="text-right">			
						<?php if( !empty( $facebook_link ) ): ?>
						<a href="<?php echo esc_url( $facebook_link ); ?>" class="copyrights-share" target="_blank">
							<i class="fa fa-facebook"></i>
						</a>
						<?php endif; ?>

						<?php if( !empty( $twitter_link ) ): ?>
						<a href="<?php echo esc_url( $twitter_link ); ?>" class="copyrights-share" target="_blank">
							<i class="fa fa-twitter"></i>
						</a>
						<?php endif; ?>

						<?php if( !empty( $google_link ) ): ?>
						<a href="<?php echo esc_url( $google_link ); ?>" class="copyrights-share" target="_blank">
							<i class="fa fa-google-plus"></i>
						</a>
						<?php endif; ?>

						<?php if( !empty( $linkedin_link ) ): ?>
						<a href="<?php echo esc_url( $linkedin_link ); ?>" class="copyrights-share" target="_blank">
							<i class="fa fa-linkedin"></i>
						</a>
						<?php endif; ?>

						<?php if( !empty( $tumblr_link ) ): ?>
						<a href="<?php echo esc_url( $tumblr_link ); ?>" class="copyrights-share" target="_blank">
							<i class="fa fa-tumblr"></i>
						</a>
						<?php endif; ?>
						<?php if( !empty( $instagram_link ) ): ?>
						<a href="<?php echo esc_url( $instagram_link ); ?>" class="copyrights-share" target="_blank">
							<i class="fa fa-instagram"></i>
						</a>
						<?php endif; ?>
						<?php if( !empty( $youtube_link ) ): ?>
						<a href="<?php echo esc_url( $youtube_link ); ?>" class="copyrights-share" target="_blank">
							<i class="fa fa-youtube"></i>
						</a>
						<?php endif; ?>
					</p>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
wp_footer();
?>
</body>
</html>