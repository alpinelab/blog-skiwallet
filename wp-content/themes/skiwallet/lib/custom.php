<?php
/**
 * Custom functions
 */

// URL helper
function image_asset($filename) {
  return get_stylesheet_directory_uri() . '/assets/img/' . $filename;
}
