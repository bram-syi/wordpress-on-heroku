<?php
include_once '../a/api/gdata.php';

$token = GAuth::login();

// Should not get here - normally we will redirect away
wp_redirect('/');
