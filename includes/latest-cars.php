<?php 

$attributes = get_query_var('attributes'); /// get car attribute for shortcode



/// Make cars query/select from database...

$args = [
    'post_type' => 'cars',
    'posts_per_page' => 0,
    'tax_query' => [],
    'meta_query' =>  [],


];

if(isset($attributes['price_below'])){ // Check if the car price below arrtibute exits in array
    $args['meta_query'][] =  array(
        'key' => 'price',
        'value' => $attributes['price_below'],
        'type' => 'numeric',
        'compare' => '<='
    );
}


if(isset($attributes['price_above'])){ // Check if the car price below arrtibute exits in array
    $args['meta_query'][] =  array(
        'key' => 'price',
        'value' => $attributes['price_above'],
        'type' => 'numeric',
        'compare' => '>='
    );
}

 
if(isset($attributes['colour'])){ // Check if the car colour arrtibute exits in array

    $args['meta_query'][] =  array(
        'key' => 'colour',
        'value' => $attributes['colour'],
        'compare' => '='
    );
}

if(isset($attributes['brand'])){ // Check if the car brand arrtibute exits in array
    $args['tax_query'][] = [
        'taxonomy' => 'brands',
        'field' => 'slug',
        'terms' => array($attributes['brand']),
    ];
}

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