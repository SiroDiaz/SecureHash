<?php

/**
 * Example hashing text using common
 * hash algorithms
 *
 */

require __DIR__ .'/../lib/SecureStatic.php';

use Secure\SecureStatic\Secure as SA;

// sha512
echo SA::cifrate('sha512', 'Hello World!') ."\n";
// md5
echo SA::cifrate('md5', 'Hello World!') ."\n";
// ripemd160
echo SA::cifrate('ripemd160', 'Hello World!') ."\n";