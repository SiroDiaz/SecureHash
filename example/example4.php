<?php

/**
 * Example hashing files with instance class
 *
 */

require __DIR__ .'/../lib/Secure.php';

use Secure\SecureHash as SH;

$a = new SH('md5', 'img1.jpg');

echo "file md5 ". $a->cifrateFile() ."\n";
$a->setValue('img2.jpeg');
echo "file md5 ". $a->cifrateFile() ."\n";

$a->setAlgo('sha1');
$a->setValue('img1.jpg');

echo "file sha1 ". $a->cifrateFile() ."\n";

$a->setValue('img2.jpeg');

echo "file sha1 ". $a->cifrateFile() ."\n";