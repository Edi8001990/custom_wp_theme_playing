
<?php if(have_posts() ): while( have_posts()): the_post();?>

    <p><?php echo get_the_date('l jS F, Y'); ?></p>
    <?php the_content();?>


    <?php 
        $first_name = get_the_author_meta('first_name');
        $last_name = get_the_author_meta('last_name');
    ?>

    <p>Posted By: <?php echo $first_name?> <?php echo $last_name?></p>

    <?php 
        $tags = get_the_tags();
        if($tags):
        foreach($tags as $tag):?>
            <a href="<?php echo get_tag_link($tag->term_id);?>" class="badge badge-success">
                    <?php echo $tag->name;?>
            </a>

        <?php endforeach; endif;?>


        <?php 
            $categories = get_the_category();

            foreach($categories as $category):?>
                        <a href="<?php echo get_category_link($category->term_id);?>"></a>
                     <?php echo $category->name;?>
            <?php endforeach;?>


            <!-- Enable comments feature -->
           <!-- <?php //comments_template();?> -->

<?php endwhile; else: endif;?>