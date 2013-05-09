<header id="banner" class="navbar navbar-fixed-top" role="banner">
  <?php roots_header_inside(); ?>
  <div class="navbar-inner">
    <div class="<?php echo WRAP_CLASSES; ?>">
     <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="<?php echo home_url(); ?>/">
        <img src="http://dev1.seeyourimpact.com/wp-content/images/logo_03.png" style=" margin: -29px 0; zoom: 74%;">
      </a>
      <nav id="nav-main" class="nav-collapse" role="navigation">
        <? /*STEVE*/ do_action('nav_main_before'); ?>
        <?php wp_nav_menu(array('theme_location' => 'primary_navigation', 'walker' => new Roots_Navbar_Nav_Walker(), 'menu_class' => 'nav')); ?>
        <? /*STEVE*/ do_action('nav_main_after'); ?>
      </nav>
    </div>
  </div>
</header>
