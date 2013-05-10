<?php

define( 'SHORTINIT', true );

require_once('../wp-load.php');
require_once('../syi/transaction.php');

$x = Txn::makeDonation(5613, 23.45, 4.0);

var_export($x);
