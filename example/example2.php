<?php

/**
 * Example hashing text
 *
 */

require __DIR__ .'/../lib/SecureStatic.php';

use Secure\SecureStatic\Secure as SA;

echo "file md5 ". SA::cifrateFile('md5', 'img1.jpg') ."\n";
echo "file md5 ". SA::cifrateFile('md5', 'img2.jpeg') ."\n";

echo "file sha1 ". SA::cifrateFile('sha1', 'img1.jpg') ."\n";
echo "file sha1 ". SA::cifrateFile('sha1', 'img2.jpeg') ."\n";