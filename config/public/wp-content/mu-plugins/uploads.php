<?

function draw_image_uploader($img, $id="main-image", $editable = TRUE) {

  $empty = empty($img) || strpos($img, "buddypress/bp-core") !== FALSE;

  $color = $empty ? 'green' : 'gray';
  $class = $editable ? "image-frame image-uploader" : "image-frame";

  ?><div id="<?=$id?>" class="image-frame image-uploader <? if (!$empty) echo 'has-picture'; ?>"><?
  echo $img;
  if ($editable) {
    ?><div id="button" class="ui image-button button medium-button <?=$color?>-button">Choose a picture</div><?
  }
  ?></div><?
}


