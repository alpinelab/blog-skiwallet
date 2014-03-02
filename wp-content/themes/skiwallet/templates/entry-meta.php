<time class="published" datetime="<?php echo get_the_time('c'); ?>"><?php echo get_the_date(); ?></time>
<p class="byline author vcard">
  <?php echo __('By', 'roots'); ?>
  <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" rel="author" class="fn">
    <?php echo get_the_author(); ?>
  </a>
</p>

<? if (has_post_thumbnail()) { // Featured image or featured video
  echo the_post_thumbnail('large');
} ?>
