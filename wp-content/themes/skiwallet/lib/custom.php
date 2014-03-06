<?php
/**
 * Custom functions
 */

// URL helper
function image_asset($filename) {
  return get_stylesheet_directory_uri() . '/assets/img/' . $filename;
}

// Shortcode: [thumbs_rating]
function thumbs_rating_func() {
  return function_exists('thumbs_rating_getlink') ? thumbs_rating_getlink() : '';
}
add_shortcode('thumbs_rating', 'thumbs_rating_func' );

// Override ReadMore link
function readmore_link() {
  return '<a class="more-link btn btn-primary btn-xs" href="' . get_permalink() . '" rel="nofollow"><i class="fa fa-plus"></i> ' . __('More...') . '</a>';
}
add_filter('the_content_more_link', 'readmore_link');
