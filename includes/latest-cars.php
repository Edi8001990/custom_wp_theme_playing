<?php 

/// Make cars query/select from database...

$args = [
    'post_type' => 'cars',
    // 'meta_key' => 'colour',
    // 'meta_value' => 'red',
    'posts_per_page' => 1,


];

$query = new WP_Query($args);
?>


<?php 

if( $query->have_posts(  )):?>

<?php while( $query->have_posts()) : $query->the_post();?>
<div class="card mb-3">
<div class="card-body">

<a href="<?php the_post_thumbnail_url('blog-large');?>" class="href">
    <img src="<?php the_post_thumbnail_url('blog-large');?>" alt="<?php the_title();?>" class="img-fluid mb-3 img-thumbnail">
</a>

    <h3><?php the_title();?></h3>

    <?php the_field('registration');?>
    </div>
</div>
<?php endwhile;?>
<?php endif;?>