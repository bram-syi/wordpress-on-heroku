
  </div><!-- /#wrap -->
</div><!-- /#syi-page -->

  <?php roots_footer_before(); ?>
  <footer id="syi-footer" class="syi-footer" role="contentinfo">
    <?php roots_footer_inside(); ?>
    <div class="container">
      <div class="row balanced">
        <div class="col span4">
          <h4>Our Story</h4>
          <?php dynamic_sidebar('footer-story'); ?>
        </div>
        <div class="col span4">
          <h4>Our Team</h4>
          <?php dynamic_sidebar('footer-team'); ?>
        </div>
        <div class="col span4">
          <h4>Connect with SeeYourImpact.org</h4>
          <div class="btn-social">
            <a href="http://facebook.com/SeeYourImpact" class="btn facebook-btn btn-contact"><i class="icon-facebook icon-large"></i> Facebook</a>
            <a href="http://twitter.com/SeeYourImpact" class="btn twitter-btn btn-contact"><i class="icon-twitter icon-large"></i> Twitter</a>
            <a href="/blog/" class="btn facebook-btn btn-contact">Our blog</a>
            <a href="/contact/" class="btn twitter-btn btn-contact">Contact us</a>
          </div>
          <?php dynamic_sidebar('footer-social'); ?>
        </div>
      </div>
      <div class="navbar footer-navbar">
        <div class="navbar-inner">
          <div class="container">
            <?php wp_nav_menu(array('theme_location' => 'footer', 'menu_class' => 'nav footer-nav')); ?>
            <ul class="nav pull-right">
              <li>
                <p class="navbar-text copy span3">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    </div>
  </footer>
  <?php roots_footer_after(); ?>

  <?php wp_footer(); ?>
  <?php roots_footer(); ?>

</body>
</html>
