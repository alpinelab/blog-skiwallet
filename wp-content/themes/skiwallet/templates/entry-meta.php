<p class="byline author vcard">

  <?= __('Written by', 'roots') ?>
  <a href="<?= get_the_author_meta('user_url') ?>?rel=author" rel="author" class="fn">
    <?= get_the_author() ?>
  </a>

  <?= __('in', 'roots') ?>
  <? the_category(', ') ?>

  <?= __('on', 'roots') ?>
  <time class="published" datetime="<?= get_the_time('c') ?>"><?= get_the_date() ?></time>

</p>

<? if (has_post_thumbnail()) { // Featured image or featured video
  echo the_post_thumbnail('large');
} ?>
