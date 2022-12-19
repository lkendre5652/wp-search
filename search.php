<?php include('search_escon.php'); ?> <?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Astra
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}
get_header();
?></div>
<div style='display:none; ' class='loader-img'>
  <img src="<?php echo get_home_url();?>/wp-content/themes/astra/assets/images/loader.gif" />
</div>
<section class="page-header search_page_header">
  <div class="cust-container ast-container">
    <!-- <div class="page-banner"> -->
      <div class="searchbannerdesign">
        <h2>Search</h2>
        <input type="text" name="searchp" value="<?php echo (!empty ($_GET['s'] ))?  $_GET['s']: '' ; ?>" placeholder="Search" id="searchp" onchange="searchFilterSearch(this);searchFilter();">
        <input type="submit" name="search" value="" class="search-btn">
      <!-- </div> -->
    </div>
  </div>
</section>
<div class="ast-container">
  <div class="searchpage_container">
    <!-- search form -->
    <div class="searchpage_sidebar_col">
      <h2> Content</h2>
      <form id='searchform'>
        <ul class="searchpage_sidebar">
          <li>
            <input type="checkbox" id="type_all" name="type_all" value="" onchange="searchFilterAll(this); searchFilter();" checked /> All
          </li>
          <li>
            <input type="checkbox" id="type_pages" name="type_page" value="page" onchange="searchFilterPages(this); searchFilter();" checked /> Pages
          </li>
          <li>
            <input type="checkbox" id="type_post" name="type_post" value="cb_products" onchange="searchFilterPost(this); searchFilter();" checked /> Products
          </li>
        </ul>
      </form>
    </div>
    <!-- search form -->
    <div class="search_page_resule"> <?php 
    $searchText = (!empty($_GET['s'])? $_GET['s'] : '');
    $searchArgs = array(
    'post_type' => array('cb_products','page'),
    'posts_per_page' => 5,
    'order' => 'ASC',
    's' => $searchText, 
    );
    $searchQuery = new Wp_Query($searchArgs);
    $post_count = $searchQuery->post_count; ?> 
    <ul class="searchfilter_ul" id='search_div'> 
      <?php if($post_count > 0){  ?>
      <?php while($searchQuery->have_posts()):$searchQuery->the_post(); ?> <li class="searchresule">
          <div class="img_box">
            <div class="img_box_wrap">
              <a href="
                    <?php echo get_permalink(); ?>">
                <img src="
                      <?php echo ( !empty( get_the_post_thumbnail_url()))? get_the_post_thumbnail_url() : site_url().'/wp-content/uploads/2022/12/search_img_def.jpg'; ?>" />
              </a>
            </div>
          </div>
          <div class="content_box">
            <div class="title">
              <a href="<?php echo get_permalink();?>"> <?php the_title(); ?> </a>                      
            </div>
            <div class="content"> <?php echo get_the_excerpt(); ?> </div>
            <!-- <div class="publishdate"> <?php //echo get_the_date(); ?> </div> -->
            <div class="explore_wrap">
              <a class="Explore_btn" href="<?php echo get_permalink(); ?>">Explore </a>                      
            </div>
          </div>
        </li> <?php endwhile; ?> 
         <?php 
          }else{
            echo '<li class="search-error-msg" >No Data found!!</li>';
          }

          ?>
      </ul>
      <div class="load_button" id="load_button" style='display: none;'>
        <input type="hidden" id="number" name='number' value="5" />
        <input type="button" onclick="incrementValue()" value="Load More" />
      </div>
    </div>
  </div>
  <!-- Blog results --> 
<?php 
if($post_count >= 5){ ?> <script>
    jQuery("#load_button").css('display', 'block');
  </script> <?php
}
?> <?php get_footer(); ?>