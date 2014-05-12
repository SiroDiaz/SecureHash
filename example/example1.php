<?php

/**
 * Example hashing text using common
 * hash algorithms
 *
 */

require __DIR__ .'/../lib/SecureStatic.php';

use Secure\SecureStatic\SecureHash as SH;

// sha512
echo SH::cifrate('sha512', 'Hello World!') ."\n";
// md5
echo SH::cifrate('md5', 'Hello World!') ."\n";
// ripemd160
echo SH::cifrate('ripemd160', 'Hello World!') ."\n";