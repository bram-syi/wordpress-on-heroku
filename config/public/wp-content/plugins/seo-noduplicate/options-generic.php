<form method="post"><fieldset>
<?php
  echo(
    '<h2>'.__('Support this plugin!', $this->name).'</h2>'.
    '<p><label>'.
    __('Display plugin attribution link in the footer', $this->name).' &nbsp; <input name="snd_options[show_link]" value="on" type="radio" '.checked(true, $snd_options['show_link'], false).'/>'.
    '</label></p>'.
    '<p><label>'.
    sprintf(__('Do not display plugin attribution link.  (%sDonations are appreciated!%s)', $this->name), '<a href="http://omninoggin.com/donate">', '</a>').' &nbsp; <input name="snd_options[show_link]" value="off" type="radio" '.checked(false, $snd_options['show_link'], false).'/>'.
    '</label></p>'
  );

  if ( function_exists( 'wp_nonce_field' ) && wp_nonce_field( $this->name ) ) {
    echo(
      '<p class="submit">'.
        '<input type="submit" name="snd_options_submit" value="'.__('Update Options', $this->name).' &#187;" />'.
      '</p>'
    );
  }
?>
</form>

<?php
  echo(
    '<h2>'.__('Where are all the configurations?', $this->name).'</h2>'.
    '<p><label>'.
    __('This plugin is already working fine. If you would like to override each post/page\'s canonical URL individually, you may do so on each post/page\'s edit page under the "SEO No Duplicate" section.', $this->name).
    '</label></p>'
  );
?>
