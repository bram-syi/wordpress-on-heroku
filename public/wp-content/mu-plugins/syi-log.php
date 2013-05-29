<?php

// a simple class to manage multiple info logs, NOT WARNINGS OR ERRORS

class SyiLog {

  // the main function, the only public part of this class
  // $tag: this is a short string that will be included in the filename
  // $msg: this will be prepended with the timestamp and written to the
  //   log file. it will also be appended with the best guess for where
  //   this function was called from
  public function log($tag, $msg) {
    if (!$tag) $tag = 'unknown';
    if (!$msg) $msg = '(no message)';

    if (!array_key_exists($tag, self::$filehandles)) {
      self::init($tag);
    }
    $fh = self::$filehandles[$tag];

    if (!$fh) {
      // as much as I don't like just returning silently, who knows where
      // any error messages will go?
      return;
    }

    $stamp = date('c');
    $from = self::called_from();
    $msg = "[$stamp] $msg ($from)\n";
    fwrite($fh, $msg);
    self::fallback_log($tag, $msg);
  }

  // devmachines.php needs to build a filename for the php error log
  public function php_error_logfile() {
    return self::build_filename('php');
  }

  // array of filehandles to various logfiles, eg:
  //   array(
  //    'txn' => $fh1,
  //    'fb' => $fh2,
  //   );
  protected static $filehandles = array();

  // creates a filehandle and sets up the class for subsequent log() calls
  protected function init($tag) {
    if (!array_key_exists($tag, self::$filehandles)) {
      $file = self::build_filename($tag);
      self::$filehandles[$tag] = fopen($file, 'a');
    }
  }

  // make up the filename for a tag, given the HTTP environment we are in
  protected function build_filename($tag) {
    $log_directory = '/var/log/syi_log';
    $host = $_SERVER['HTTP_HOST'];
    
    if ( defined('LOG_DIR') ) {
      $log_directory = LOG_DIR;
    }

    if (preg_match('/([^\.]+)\.seeyourimpact\.com$/', $host, $m)) {
      $file = $log_directory . "/$tag-" . $m[1] . '.log';
    }
    else if (preg_match('/seeyourimpact\.org$/', $host, $m)) {
      $file = $log_directory . "/$tag-live.log";
    }
    else {
      $file = $log_directory . "/unknown-http-env.log";
    }

    return $file;
  }

  protected function called_from($index = 1) {
    $stack = debug_backtrace();

    // this is here so that methods like Txn::log and SyiFacebook::log don't log
    // *their* function location, which is useless
    if ($stack[$index]['function'] == 'log') {
      $index += 2;
    }
    else if ($stack[$index]['function'] == 'logThisFunction' && $stack[$index]['class'] == 'Txn') {
      $index += 2;
    }
    else if (preg_match("/^d(p|f)$/", $stack[$index]['function'])) {
      $index += 2;
    }

    if ($stack[$index]['function']) {
      $s = $stack[$index]['class'] . '::' . $stack[$index]['function'];
    }
    else {
      $s = '-';
    }

    if ($stack[$index-1]['file'] && $stack[$index-1]['line']) {
      $file = $stack[$index-1]['file'];
      $file = preg_replace('#/home/digvijay/(SeeYourImpact.org|dev/dev\d+)/#', '', $file);
      $s .= " $file:" . $stack[$index-1]['line'];
    }

    return $s;
  }

  protected static $fallback_filehandle;
  protected function fallback_log($tag, $msg) {
    if (!self::$fallback_filehandle) {
      self::$fallback_filehandle = fopen(self::build_filename('syilog'), 'a');
    }
    fwrite(self::$fallback_filehandle, "$tag $msg");
  }
}
