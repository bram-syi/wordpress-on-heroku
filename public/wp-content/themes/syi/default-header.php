<? draw_login_bar(); ?>
<nav class="tab-bar">
  <a id="logo" href="<?= SITE_URL ?>" class="menu-tab" title="<?= $site_name ?>"><span>SeeYourImpact.org</span></a>
  <div class="floatr">
    <a id="about-link" class="menu-tab" style="width:120px;" href="<?= get_site_url(1, '/about/') ?>"><span>How it works</span></a>
    <a id="stories-link" class="menu-tab" style="width:127px;" href="<?= get_site_url(1, '/stories/') ?>"><span>See real stories</span></a>
    <form class="fr-search visible-desktop" method="get" action="<? bloginfo('home'); ?>">
      <input class="span3" type="hidden" id="fr-search" name="s" value="" placeholder="Find people or charities" data-validators="required" data-speech-enabled="" x-webkit-speech="x-webkit-speech" autocomplete="off">
    </form>
  </div>
</nav>
