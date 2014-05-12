<?php

/**
 * Example hashing files with static class
 *
 */

require __DIR__ .'/../lib/SecureStatic.php';

use Secure\SecureStatic\SecureHash as SH;

echo "file md5 ". SH::cifrateFile('md5', 'img1.jpg') ."\n";
echo "file md5 ". SH::cifrateFile('md5', 'img2.jpeg') ."\n";

echo "file sha1 ". SH::cifrateFile('sha1', 'img1.jpg') ."\n";
echo "file sha1 ". SH::cifrateFile('sha1', 'img2.jpeg') ."\n";