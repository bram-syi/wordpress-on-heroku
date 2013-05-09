<?
session_start();
class CaptchaSecurity {
 
   var $font = 'monofont.ttf';
 
   function generateCode() {
      //Now lets use md5 to generate a totally random string
	$md5Code = md5(microtime() * mktime());
	
	/*
	We dont need a 32 character long string so we trim it down to 5
	*/
	$stringForCaptcha = substr($md5Code,0,5);
      return $stringForCaptcha;
   }
 
   function CaptchaSecurity($width='120',$height='40',$characters='6') {
      $code = $this->generateCode();
      /* font size will be 75% of the image height */
      $font_size = $height * 0.75;
      $image = imagecreate($width, $height) or die('Cannot initialize new GD image stream');
      /* set the colours */
      $background_color = imagecolorallocate($image, 255, 255, 255);
      $text_color = imagecolorallocate($image, 20, 40, 100);
      $noise_color = imagecolorallocate($image, 170, 182, 178);
     
     /* generate random dots in background */
      for( $i=0; $i<($width*$height)/3; $i++ ) {
         imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
      }

      /* create textbox and add text */
      $textbox = imagettfbbox($font_size, 0, $this->font, $code) or die('Error in imagettfbbox function');
      $x = ($width - $textbox[4])/2;
      $y = ($height - $textbox[5])/2;
      imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font , $code) or die('Error in 		imagettftext function');
      /* output captcha image to browser */
      header('Content-Type: image/jpeg');
      imagejpeg($image);
      imagedestroy($image);
      $_SESSION['security_code'] = $code;
     
   }
 
}
$captcha = new CaptchaSecurity(110, 20);
?>