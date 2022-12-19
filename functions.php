<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {
	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );
}
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );


// Search filter start
add_action('wp_ajax_nopriv_search_action', 'searchdata_fetch');
add_action('wp_ajax_search_action', 'searchdata_fetch');
function searchdata_fetch(){    
    $search = (!empty($_POST['search_text']) )? sanitize_text_field($_POST['search_text']) : ''; 
    $type_all = (!empty($_POST['type_all']) )? sanitize_text_field($_POST['type_all']) : ''; 
    $type_post = (!empty($_POST['type_post']) )? sanitize_text_field($_POST['type_post']) : ''; 
    $type_page = (!empty($_POST['type_pages']) )? sanitize_text_field($_POST['type_pages']) : '';          
    // load more pending    
    $no_post = (!empty($_POST['number']) )? sanitize_text_field($_POST['number']) : '';   
    $no_post = (int)$no_post;    
    if( ( !empty($search) ) && ( !empty($type_all) ) || (!empty($type_page) && ( !empty($type_post) ) ) ){                  
        $args = array(
            'post_type' => array('cb_products','page'),
            'posts_per_page' => $no_post,
            'order' => 'DESC',
            's' => $search,           
        );
        $getPosts = new WP_Query($args);        
    }else if( ( !empty($search) ) && ( !empty($type_page) ) || ( !empty($type_post) ) ){
        $type_page = (!empty($type_page) )? $type_page: ''; 
        $post_types = (!empty($type_post) )? $type_post: '';                                             
        $args = array(
            'post_type' => array($type_page,$post_types ),
            'posts_per_page' => $no_post,
            'order' => 'DESC',
            's' => $search,           
        );
        $getPosts = new WP_Query($args);       
    }else{
        $result = [
        'status' => 'error',        
        'msg' => ( 'No Data found!! ' ),        
        ];
        wp_send_json($result);
        wp_die(); 
    }
   
    $post_count = $getPosts->post_count;
    if($post_count == 0) {
        $result = [
        'status' => 'error',        
        'msg' => ( 'No Result found' ),        
        ];
        wp_send_json($result);
        wp_die();    
    }
    $posts = [];
     if ( $getPosts->have_posts() ) { 
          while ($getPosts->have_posts()) {
            $getPosts->the_post();                       
            $posts[] = array(
                'title' => get_the_title(),
                'permalink' => get_permalink(),
                'contents' => get_the_excerpt(), 
                'thumbnail' => get_the_post_thumbnail_url(), 
                'publishdate' => get_the_date(),
                'post_count' => $post_count,
            );
        }
    }
    $result = [
        'status' => 'success',
        'response_type' => 'get posts',
        'msg' => 'results',        
        'data' => $posts,              
    ];
    wp_send_json($result);
    wp_die();    
}



// search title
add_action('wp_ajax_nopriv_title_search_action','lkGetTheSearchTitle');
add_action('wp_ajax_title_search_action','lkGetTheSearchTitle');

function lkGetTheSearchTitle(){
    $searchtitle = (!empty($_POST['searchtitle']) )? sanitize_text_field($_POST['searchtitle']) : '';      
    if(!empty($searchtitle) ){     
       global $wpdb;
       $myposts = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_type IN('page','cb_products') AND post_title LIKE '%s'", '%'. $wpdb->esc_like($searchtitle).'%') );
       $posts = [];
       if(count($myposts) > 1 ){
            foreach ($myposts as $key => $value) {
            $posts[] = array(
                'posts_title' => $value->post_title,
            );        
           }                  
            $result = [
                'status' => 'success',
                'response_type' => 'get posts',
                'msg' => 'results',        
                'data' => $posts,              
            ];

       }else{
            $result = [
                'status' => 'error',        
                'msg' => ( 'No Result found' ),             
            ];


       }
       
    }       
    
    wp_send_json($result);
    wp_die();
}

// Search filter end

// SMTP setting
add_action( 'phpmailer_init', 'my_phpmailer_smtp' );
function my_phpmailer_smtp( $phpmailer ) {
    $phpmailer->isSMTP();     
    $phpmailer->Host = SMTP_HOST;
    $phpmailer->SMTPAuth = SMTP_AUTH;
    $phpmailer->Port = SMTP_PORT;
    $phpmailer->Username = SMTP_USER;
    $phpmailer->Password = SMTP_PASS;
    $phpmailer->SMTPSecure = SMTP_SECURE;
    $phpmailer->From = SMTP_FROM;
    $phpmailer->FromName = SMTP_NAME;
}
// SMTP setting

// Form Validation
add_filter( 'wpcf7_validate_text', 'custom_name_validation_filter', 20, 2 );
add_filter( 'wpcf7_validate_text*', 'custom_name_validation_filter', 20, 2 );
function custom_name_validation_filter( $result, $tag ) {     
  if ( "your-name" == $tag->name ) {
    $name = isset( $_POST[$tag->name] ) ? $_POST[$tag->name]  : '';
 
    if ( $name != "" && !preg_match("/^[a-zA-Z ]*$/",$name) ) {
      $result->invalidate( $tag, "The name entered is invalid." );
    }
  }  
  return $result;  
}

// form validation mobile
add_filter( 'wpcf7_validate_text', 'custom_mobile_validation_filter', 20, 2 );
add_filter( 'wpcf7_validate_text*', 'custom_mobile_validation_filter', 20, 2 );
function custom_mobile_validation_filter( $result, $tag ) {     
  if ( "your-mobile" == $tag->name ) {
    $name = isset( $_POST[$tag->name] ) ? $_POST[$tag->name]  : '';
 
    if ( $name != "" && !preg_match("/^[0-9]+$/",$name) ) {
      $result->invalidate( $tag, "Please enter valid contact" );
    }else if(strlen($name) < 8 || strlen($name) > 15 ){
        $result->invalidate( $tag, "Contact no should be 8 to 15 charactors only");
    }else{
        $numberlen = strlen($name);
        $zeros = substr_count($name,'0');
        if($numberlen == $zeros ){
            $result->invalidate( $tag, "Please enter valid contact");
        }             
    }   
  }  
  return $result;  
}


function mytheme_register_nav_menu(){
         register_nav_menus( array(
            'sitemap_main_menu' => __( 'Sitemap Main Menu', 'text_domain' ),
            'disel_genset_menu' => __( 'Disel Genset Menu', 'text_domain' ),
            'gas_genset_menu' => __( 'Gas Genset Menu', 'text_domain' ),
            'acoustics_menu' => __( 'Acoustics Menu', 'text_domain' ),
            'projects_menu' => __( 'Projects Menu', 'text_domain' ),
         ));
}
add_action('after_setup_theme', 'mytheme_register_nav_menu', 0 );