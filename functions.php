<?php 


/* ---------- Load Stylesheets ----------- */


 /* Get bootstrap */
function load_css(){


/* Attach bootstrap CSS */

    wp_register_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', 
    array(), false, 'all');
    wp_enqueue_style('bootstrap');

/* Attach main CSS */

    wp_register_style('main', get_template_directory_uri() . '/css/main.css', 
    array(), false, 'all');
    wp_enqueue_style('main');

}

add_action('wp_enqueue_scripts', 'load_css');



/* ---------- Load JS ----------- */

/* Attach bootstrap JS */

function load_js(){

    wp_enqueue_script('jquery');

    wp_register_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', 
    'jquery', false, true);
    wp_enqueue_script('bootstrap');
}

add_action('wp_enqueue_scripts', 'load_js');



/* ---------- Load Theme Options ----------- */
add_theme_support('menus');
add_theme_support('post-thumbnails');
add_theme_support('widgets');

// Register Custom Navigation Walker

function register_navwalker(){
	require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';
}
add_action( 'after_setup_theme', 'register_navwalker' );


// Menus

register_nav_menus(

    array(
        'top-menu' => 'Top Menu Location',
        'mobile-menu' => 'Mobile Menu Location',
        'footer-menu' => 'Footer Menu Location',
    )

    );

//Custom Image sizes
add_image_size('blog-large', 800, 600, false);
add_image_size('blog-small', 300, 200, true);



// Register Sidebars
function my_sidebars(){
    register_sidebar(
        array(
            'name' => 'Page Sidebar',
            'id' => 'page-sidebar',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>'
        )
        );

        register_sidebar(
            array(
                'name' => 'Blog Sidebar',
                'id' => 'blog-sidebar',
                'before_title' => '<h3 class="blog-title">',
                'after_title' => '</h3>'
            )
            );
}
add_action('widgets_init', 'my_sidebars');





//Custom post types
function my_first_post_type(){

    $args = array(
        'labels' => array(
            'name' => 'Cars',
            'singular_name' => 'Car',
        ),
        'hierarchical' => true,
        'public' => true,
        'menu_icon' => 'dashicons-car',
        'has_archive' => true, 
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        // 'rewrite' => array('slug' => 'cars'),

    );

    register_post_type('cars', $args);
}

add_action('init', 'my_first_post_type');


// Add cars taxonomy/categories

function my_first_taxonomy(){
    $args = array(

        'labels' => array(
            'name' => 'Brands',
            'singular_name' => 'Brand',
        ),

        

       
            'public' => true,
            'hierarchical' => true,
        
    );

    register_taxonomy('brands',  array('cars'), $args);
}
add_action('init', 'my_first_taxonomy');



// Register the custom form

add_action('wp_ajax_enquiry', 'enquiry_form');
add_action('wp_ajax_nopriv_enquiry', 'enquiry_form'); // Enable form for non loggged in users
function enquiry_form(){

    if(! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' )){  // Check if form request comes from WP site
        wp_send_json_error( 'Nonce is incorrect' , 404 );
        die();
    }


    $formdata = [];
    wp_parse_str($_POST['enquiry'], $formdata);// Grab form data

    // Admin Email
    $admin_email = get_option('admin_email');

    // Email Headers
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $headers[] = 'From: My Website <' . $admin_email . '>';
    $headers[] = 'Reply-to:' . $formdata['email'];

    // Who are we sending the email to?
    $send_to = $admin_email;

    //Subject
    $subject = "Enquiry from " . $formdata['fname'] . ' ' . $formdata['lname'];

    //Message

    $message = '';

    foreach($formdata as $index => $field){
        $message .= '<strong>' . $index . '</strong> ' . $field . '<br/>';
    }

    try{
        if( wp_mail($send_to, $subject, $message, $headers) ){
            wp_send_json_success('Email sendt');
        }
        else{
            wp_send_json_error('Emain error');
        }
    }

    catch (Exception $e){
        wp_send_json_error( $e->getMessage());
    }

    wp_send_json_success($formdata['fname']);

}



// SMTP mail ---> prrvent to Land in spam folder


add_action('phpmailer_init', 'custom_mailer');

function custom_mailer( $phpmailer)
 {

    ;
    $phpmailer->SetFrom('mateusz@hubstars.com', 'Mateusz T');
    $phpmailer->Host = 'smtp.gmail.com';
    $phpmailer->Port = 465;
    $phpmailer->SMTPAuth = true;
    $phpmailer->SMTPSecure = 'ssl';
    $phpmailer->Username = SMTP_LOGIN;
    $phpmailer->Password = SMTP_PASSWORD;
    $phpmailer->IsSMTP();
 }


//  add_filter( 'wp_mail_content_type','set_my_mail_content_type' );
//  function set_my_mail_content_type() {
//      return "text/html";
//  }



//Shortcodes ---> select cars

function my_shortcode(){

    ob_start(); /// don't echo out meaning display the shortcode in right place
    get_template_part('includes/latest' , 'cars'); 
    return ob_get_clean(); /// don't echo out meaning display the shortcode in right place
}


add_shortcode('latest_cars', 'my_shortcode');


function my_phone(){
    return '<a href="tel:05345345 43534 534535">05345345 43534 534535</a>';
}

add_shortcode( 'phone', 'my_phone' );