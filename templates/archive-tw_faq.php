<?php
/**
 * General Archive File
 *
 * @package WordPress
 * @subpackage Third Wunder
 * @since Third Wunder 1.0
 */
get_header(); ?>
<!-- Site Container -->

<div id="site-content" class="container-fluid">
  <?php do_action('tw_faq_plugin_before_faq_archive'); ?>
  <div id="site-container" class="">

    <div id="primary" class="content-area container">
    	<main id="main" class="site-main" role="main" itemprop="mainContentOfPage">
        <div id="page-archive" class="page-archive" <?php echo tw_html_tag_schema(); ?>>

          <header class="page-header">
            <?php do_action('tw_faq_plugin_before_faq_archive_title'); ?>
            <?php
              the_archive_title( '<h1 class="page-title">', '</h1>' );
              the_archive_description( '<div class="archive-meta">', '</div>' );
            ?>
          </header><!-- .page-header -->

          <?php if (have_posts()): ?>
    			  <section id="author-posts" class="page-posts">
    				<?php while (have_posts()): the_post(); ?>

              <article <?php post_class('post');?>>
                <header>
                  <h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
                </header>
                <section>
                  <?php the_excerpt(); ?>
                </section>
                <footer></footer>
              </article>

            <?php endwhile; ?>
            </section><!-- .page-posts -->
          <?php else:
              get_template_part( 'content/content', 'none' );
            endif;
          ?>


          <footer class="page-footer">
            <?php tw_pagination(); ?>
        	</footer><!-- .page-footer -->

        </div><!-- #page-category -->
      </main><!-- .site-main -->
    </div><!-- .content-area -->

  </div><!-- #site-container -->
  <?php do_action('tw_faq_plugin_after_faq_archive'); ?>
</div><!-- #site-content -->
<?php get_footer(); ?>