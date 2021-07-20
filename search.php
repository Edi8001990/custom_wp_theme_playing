<?php get_header(); ?>

<section class="page-wrap">
<div class="container">

<section class="row">

                        
        <h1>Search Results for '<?php echo get_search_query();?>'</h1>
       
        <?php get_template_part('includes/section', 'searchresults'); ?>


      

        <?php 

        // pagination first method
        
        // previous_posts_link();
        // next_posts_link();

        // pagination second method ---> Taken from WP handbook
            global $wp_query;

            $big = 999999999;

            echo paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $wp_query->max_num_pages
            ));
        ?>
</div>
</section>
</div>
</section>
<?php get_footer(); ?>