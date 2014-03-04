<footer class="content-info" role="contentinfo">
  <div class="container">
    <div class="row">

      <div class="col-md-3">
        <h3>SkiWallet</h3>
        <ul>
          <li><a href="https://www.skiwallet.com/fr/vente-forfait-ski">Qui sommes-nous ?</a></li>
          <li><a href="https://www.skiwallet.com/fr/contactez-nous">Contactez nous</a></li>
        </ul>
        <h3>Mentions</h3>
        <ul>
          <li><a href="https://www.skiwallet.com/fr/mentions-legales">Mentions légales</a></li>
          <li><a href="https://www.skiwallet.com/fr/conditions-generales-de-vente">Conditions générales de vente</a></li>
        </ul>
      </div>

      <div class="col-md-3">
        <h3>Toutes les langues</h3>
        <ul>
          <li><a href="https://www.skiwallet.com/en">SkiWallet in English</a></li>
          <li><a href="https://www.skiwallet.com/fr">SkiWallet en français</a></li>
          <li><a href="https://www.skiwallet.com/es">SkiWallet en español</a></li>
        </ul>
        <h3>Lieux</h3>
        <ul>
          <li><a href="https://www.skiwallet.com/fr/resorts">Voir toutes les stations</a></li>
          <li><a href="https://www.skiwallet.com/fr/regions">Voir tous les massifs</a></li>
        </ul>
      </div>

      <div class="col-md-3">
        <?php dynamic_sidebar('sidebar-footer'); ?>
      </div>

      <div class="col-md-3">
        <h3>Réseaux</h3>
        <h4>Suivez nous</h4>
        <ul class="social-networks">
          <li>
            <a href="https://www.facebook.com/SkiWallet" target="_blank">
              <img alt="Facebook" src="<?= image_asset('footer/facebook.png') ?>">
            </a>
          </li>
          <li>
            <a href="https://twitter.com/SkiWallet" target="_blank">
              <img alt="Twitter" src="<?= image_asset('footer/twitter.png') ?>">
            </a>
          </li>
          <li>
            <a href="https://plus.google.com/+SkiWallet" rel="publisher" target="_blank">
              <img alt="Google+" src="<?= image_asset('footer/gplus.png') ?>">
            </a>
          </li>
        </ul>
        <h3>Créé par</h3>
        <ul>
          <li><a href="http://www.alpine-lab.com">Alpine Lab</a></li>
          <li><a href="http://www.labonnepiste.com">La Bonne Piste</a></li>
        </ul>
      </div>

  </div>
</footer>

<?php wp_footer(); ?>
