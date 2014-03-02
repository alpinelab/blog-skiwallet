<time class="published" datetime="<?php echo get_the_time('c'); ?>"><?php echo get_the_date(); ?></time>
<p class="byline author vcard">
  <?php echo __('Written by', 'roots'); ?>
  <a href="<?php echo get_the_author_meta('user_url'); ?>?rel=author" rel="author" class="fn">
    <?php echo get_the_author(); ?>
  </a>
</p>

<? if (has_post_thumbnail()) { // Featured image or featured video
  echo the_post_thumbnail('large');
} ?>
