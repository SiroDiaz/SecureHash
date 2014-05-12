<?php

/**
 * Example hashing text with instance class
 *
 */

require __DIR__ .'/../lib/Secure.php';

use Secure\SecureHash as SH;

// sha512
$a = new SH('sha512', 'Hello World!');
echo $a->cifrate() ."\n";
// md5
$a->setAlgo('md5');
echo $a->cifrate() ."\n";
// ripemd160
$a->setAlgo('ripemd160');
echo $a->cifrate() ."\n";