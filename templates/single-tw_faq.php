<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since TW 1.0
 */
  $image_sizes = array('4x3-small','16x9-medium','16x9-large');
  $links = is_array(get_post_meta(get_the_id(), 'tw_external_links', true))? get_post_meta(get_the_id(), 'tw_external_links', true) : false;

  $is_faq_category = get_option('wpt_tw_faq_category') == 'on' ? true : false;
  $is_faq_tag      = get_option('wpt_tw_faq_tag')      == 'on' ? true : false;

  $faq_topics = get_the_terms(get_the_id(), 'tw_faq_topic');
  $faq_cats = get_the_terms(get_the_id(), 'tw_faq_category');
  $faq_tags = get_the_terms(get_the_id(), 'tw_faq_tag');
get_header(); ?>
<!-- Site Container -->
<div id="site-content" class="container-fluid">
  <?php do_action('tw_faq_plugin_before_faq'); ?>
  <div id="site-container" class="">
    <div id="primary" class="content-area container">
    	<main id="main" class="site-main" role="main" itemprop="mainContentOfPage">
    	<?php	while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php echo tw_html_tag_schema('Article'); ?>>
          <header class="entry-header">
            <?php do_action('tw_faq_plugin_before_faq_title'); ?>
            <?php
              the_title('<div class="container"><h1 class="entry-title" itemprop="headline">','</h1></div>');
            ?>
            <div class="entry-meta post-meta page-meta ">
              <div class="entry-categories container">

                <?php if(count($faq_topics)>0):?>
                  <span class="tags-title"><?php _e('Topics','tw-faq-plugin');?>:</span>
                  <?php foreach($faq_topics as $faq_topic): ?>
                    <span class=""><a href="<?php echo get_term_link($faq_topic, 'tw_faq_topic') ;?>" title="<?php echo $faq_topic->name;?>"><?php echo $faq_topic->name;?></a></span>
                  <?php endforeach; ?>
                <?php endif;?>

                <?php if($is_faq_category && $faq_cats && count($faq_cats)>0):?>
                  <span class="tags-title"><?php _e('Categories','tw-faq-plugin');?>:</span>
                  <?php foreach($faq_cats as $faq_cat): ?>
                    <span class=""><a href="<?php echo get_term_link($faq_cat, 'tw_faq_category') ;?>" title="<?php echo $faq_cat->name;?>"><?php echo $faq_cat->name;?></a></span>
                  <?php endforeach; ?>
                <?php endif;?>

              </div>
            </div>
          </header>
          <div class="container">
            <section class="entry-content" >
              <?php the_content();
            		wp_link_pages( array(
          				'before'      => '<nav class="navigation post-navigation" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement"><span class="page-links-title">' . __( 'Pages:', 'tw' ) . '</span>',
          				'after'       => '</nav>',
          				'link_before' => '<span>',
          				'link_after'  => '</span>',
          				'pagelink'    => '<span class="sr-only">' . __( 'Page', 'tw' ) . ' </span>%',
          				'separator'   => '<span class="sr-only">, </span>',
          				'echo'             => 0
          			) );
              ?>
            </section>

            <?php if($links && count($links)>0):?>
            <section class="faq-external-links">
              <h3><?php _e('Related Links','tw-faq-plugin');?></h3>
              <ul class="fa-ul">
                <?php foreach($links as $link): ?>
                  <li><a href="<?php echo $link['tw_link_url'];?>" <?php if(isset($link['tw_link_external']) && $link['tw_link_external']=='on'){ echo 'target="_blank"';}?>  >
                    <i class="fa fa-fw fa-angle-right"></i>
                    <?php echo $link['tw_link_name'];?>
                    <?php if(isset($link['tw_link_external']) && $link['tw_link_external']=='on'):?>
                    <i class="fa fa-fw fa-external-link"></i>
                    <?php endif; ?>
                  </a></li>
                <?php endforeach; ?>
              </ul>
            </section>
            <?php endif; ?>
          </div>

          <footer class="hidden-print entry-footer container">
            <?php if($is_faq_tag && count($faq_tags)>0):?>
            <div class="entry-tags">
              <span class="tags-title"><?php _e('Tags','tw-faq-plugin');?>:</span>
              <?php foreach($faq_tags as $tag): ?>
              <span class="badge"><a href="<?php echo get_term_link($tag, 'tw_faq_tag') ;?>" title="<?php echo $tag->name;?>"><?php echo $tag->name;?></a></span>
              <?php endforeach;?>
            </div>
            <?php endif; ?>

            <?php edit_post_link( __( 'Edit', 'tw' ), '<span class="edit-link">', '</span>' ); ?>
          </footer>

        </article>
      <?php endwhile; ?>
      </main><!-- .site-main -->

    </div><!-- .content-area -->

  </div><!-- #site-container -->
  <?php do_action('tw_faq_plugin_after_faq'); ?>
</div><!-- #site-content -->
<?php get_footer(); ?>