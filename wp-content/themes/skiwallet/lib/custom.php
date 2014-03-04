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
