</section><!-- #content -->
<? global $NO_SIDEBAR;
if (!$NO_SIDEBAR) { ?>
  <section id="bottom-panels" class="page-content">
    <? do_action('syi_bottom_widgets'); ?>
  </section>
  <div class="column-spacer"></div>
<? } ?>
</div><!-- #container -->
<? if (!PJAX) { ?>
</div><!-- #outer -->

<? if (!isset($_REQUEST['NOHDR'])) { ?>
  <? switch_to_blog(1); ?>

  <footer id="footer" class="page-center">    
    <a href="<?=SITE_URL?>" class="syi-footer-img"></a>
    <div class="syi-footer">
      <? draw_promo_content("footer"); ?>
      <div class="copyright">Copyright &copy; <?php echo date('Y'); ?> SeeYourImpact.org</div>
    </div>
  </footer>

  <? restore_current_blog(); ?>
<? } ?>

<div id="resources" style="display:none;">
  <div id="checkout"></div>
</div>

<? if (FB_PLACEMENT === 'footer') { ?>
  <div id="fb-root"></div>
  <script type="text/javascript">
  window.fbAsyncInit = function() { FB.init({appId: '123397401011758', channelUrl: '//<?= $_SERVER["SERVER_NAME"] ?>/fb-channel.php', status: true, cookie: true, xfbml: true}); };
  (function() {
      var e = document.createElement('script');
      e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
      e.async = true;
      document.getElementById('fb-root').appendChild(e);
      }());
  </script>
<? } ?>

<? do_action('draw_client_templates'); ?>
</body>

<!--[if IE 9]>
<style>
.button, .button:active, .button:hover, .stats .meter span {
  background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAWCAYAAAAILVbQAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAZdEVYdFNvZnR3YXJlAFBhaW50Lk5FVCB2My41Ljg3O4BdAAAAOUlEQVQoU2NgGAXEhQAzUBk6ZmADCqJjBi6gIDpm4AcKomMGUaAgOmaQBgqiYwZloCA6ZtACCqJgAKCYAxxo9SkIAAAAAElFTkSuQmCC);
  background-size: 100% 100%;
  filter: none !important;
}
</style>
<![endif]-->
<? stopwatch_comment('end'); ?>
</html>
<? wp_footer();?>
<? } // !PJAX

