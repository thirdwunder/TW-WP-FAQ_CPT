<?php
/**
 * Template Name: FAQ Page Template
 *
 * The full width page template.
 *
 * @package WordPress
 * @subpackage Third Wunder
 * @since Third Wunder 1.0
 */
$taxonomy = 'tw_faq_topic';

$args = array(
    'orderby'           => 'name',
    'order'             => 'ASC',
    'hide_empty'        => true,
    'fields'            => 'all',
    'hierarchical'      => true,
);

$terms = get_terms($taxonomy, $args);
get_header(); ?>
<!-- Site Container -->
<div id="site-content" class="container-fluid">
  <?php do_action('tw_faq_plugin_before_faq_template'); ?>
  <div id="site-container" class="">

    <div id="primary" class="content-area">
    	<main id="main" class="site-main" role="main" itemprop="mainContentOfPage">

        <?php while ( have_posts() ): the_post(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php echo tw_html_tag_schema(); ?>>
            <header class="page-header">
            <?php do_action('tw_faq_plugin_before_faq_template_title'); ?>
          	<div class="container">
          		<h1 itemprop="name" class="entry-title"><?php the_title(); ?></h1>
          	</div>
        	</header><!-- .entry-header -->
        	<div class="container">
            <div class="page-content" itemprop="text">
            	<?php
            		the_content();
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
            </div><!-- .entry-content -->
            <?php if(count($terms)>0):?>
            <div id="faq-topics" class="faq-topics row">
              <?php foreach($terms as $term):
                $faq_args = array (
                	'post_type' => 'tw_faq',
                	'order'     => 'ASC',
                	'orderby'   => 'menu_order',
                	'tax_query' => array(
                  		array(
                  			'taxonomy' => $taxonomy,
                  			'field' => 'slug',
                  			'terms' => $term->slug,
                  		)
                  	)
                );
                $faq_query = new WP_Query( $faq_args );
              ?>
              <div id="faq-topic-<?php echo $term->term_id;?>" class="faq-topic col-xs-12 col-sm-6 col-md-4">
                <h3><a href="<?php echo get_term_link($term, $taxonomy);?>" title="<?php echo $term->name;?>"><?php echo $term->name;?></a></h3>

                 <?php if ( $faq_query->have_posts() ) : $counter=0; $limit=5; ?>
                 <ul class="fa-ul">
                  <?php while ( $faq_query->have_posts() ) : $faq_query->the_post(); $counter++;?>
                  <li><i class="fa-li fa fa-fw fa-angle-right"></i><a href="<?php the_permalink();?>" title="<?php echo $term->name;?> - <?php the_title();?>"><?php the_title();?></a></li>
                  <?php if($counter==$limit):?>
                  <div class="more-wrapper">
                    <a class="btn btn-default btn-xs" href="<?php echo get_term_link($term, $taxonomy);?>" title="<?php echo $term->name;?>"><?php _e('More','tw-faq-plugin');?></a>
                  </div>
                  <?php break; ?>
                  <?php endif;?>
                  <?php endwhile; wp_reset_postdata();?>
                 </ul>
                 <?php endif;?>
              </div>
              <?php endforeach;?>
            </div>
            <?php endif;?>
        	</div>


          </article>
        <?php endwhile; ?>
    	</main>
    </div>

  </div><!-- #site-container -->
  <?php do_action('tw_faq_plugin_after_faq_template'); ?>
</div><!-- #site-content -->
<?php get_footer(); ?>