<?php
    // TODO - Add SSR support
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#000000">
		<?php wp_head(); ?> 
  </head>
  <body <?php body_class(); ?>>
    <noscript>
      You need to enable JavaScript to run this app.
    </noscript>
    <div id="page">
		<main id="primary" class="site-main">
			<?php
			if ( have_posts() ) :
				if ( is_home() && ! is_front_page() ) : ?>
					<header>
						<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
					</header>
				<?php
				endif;
				/* Start the Loop */
				while ( have_posts() ) : the_post();
					/**
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', get_post_format() );
				endwhile;
				the_posts_navigation();
			else :
				get_template_part( 'template-parts/content', 'none' );
			endif; ?>
			</main><!-- #primary -->
			<span
				id="site-info"
				class="site-info"
				data-endpoint="<?php oil_based_theme_the_endpoint() ?>"
			></span>
		</div>
    <?php wp_footer(); ?>
  </body>
</html>
