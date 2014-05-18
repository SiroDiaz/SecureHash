# SecureHash

PHP Class to encrypt data easily with hash algorithms

requires PHP 5 >= 5.3.0

## Composer

	$ composer require securehash/securehash:dev-master

## Quick Start and Examples

### Supported algorithms

md2, md4, md5, sha1, sha224, sha256, sha384, sha512, ripemd128, ripemd160, ripemd256, ripemd320, whirlpool, tiger128,3, tiger160,3, tiger192,3, tiger128,4, tiger160,4, tiger192,4, snefru, snefru256, gost, adler32, crc32, crc32b, salsa10(non supported in PHP 5.4.0 and later), salsa20(non supported in PHP 5.4.0 and later), 'haval128,3', 'haval160,3', 'haval192,3', 'haval224,3', 'haval256,3', 'haval128,4', 'haval160,4', 'haval192,4', 'haval224,4', 'haval256,4', 'haval128,5', 'haval160,5', 'haval192,5', 'haval224,5' and 'haval256,5'.

### SecureHash class

#### Methods

getValue, getAlgo, getAlgorithms, setValue, setAlgo, cifrate, cifrateFile, cifrateUrl, cifrateMultiple, cifrateMultipleFiles, cifrateMultipleUrls, compare, compareFiles, compareUrls

#### Examples

Simple string hashing.

```php
require 'Secure.php';
// 'as SH' is to set an alias of SecureHash class name
// and it is optional
use Secure\SecureHash as SH;

$a = new SH('sha512', 'Hello World!');
echo $a->cifrate();
```

Hashing a file.

```php
require 'Secure.php';
use Secure\SecureHash as SH;

$a = new SH();	// defaults algorithm md5 and default value ''
$a->setAlgo('crc32');
$a->setValue('css/normalize.css');	// change the value to be encrypted
echo $a->cifrateFile();
```

Print all algorithms implemented by PHP in your current version

```php
require 'Secure.php';
use Secure\SecureHash as SH;

$a = new SH();
var_dump($a->getAlgorithms());
```

Check if two urls contains the same data

```php
require 'Secure.php';
use Secure\SecureHash as SH;

$jqueryGoogle = 'http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js';
$jqueryjQuery = 'http://code.jquery.com/jquery-2.1.1.min.js';

$a = new SH('ripped160');
var_dump($a->compareUrls($jqueryGoogle, $jqueryjQuery));	// true if are equals, false if not
```

Encrypt the content of multiple urls

```php
require 'Secure.php';
use Secure\SecureHash as SH;

$urls = array('http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js', 'http://code.jquery.com/jquery-2.1.1.min.js');
$a = new SH('sha512')
$cifratedUrls = $a->cifrateMultipleUrls($urls);	// returns an array with the data encripted
var_dump($cifratedUrls);
```


### Static class version: methods and examples

#### Methods

getAlgorithms, cifrate, cifrateFile, cifrateUrl, cifrateMultiple, cifrateMultipleFiles, cifrateMultipleUrls, compare, compareFiles, compareUrls

#### Examples

Simple string hashing.

```php
require 'SecureStatic.php';
// 'as SH' is to set an alias of SecureHash class name
// and it is optional
use Secure\SecureStatic\SecureHash as SH;

echo SH::cifrate('sha512', 'Hello World!');
```

Hashing a file.

```php
require 'SecureStatic.php';
use Secure\SecureStatic\SecureHash as SH;

echo SH::cifrateFile('md4', 'route/to/file.extension');
```

Print all algorithms implemented by PHP in your current version

```php
require 'SecureStatic.php';
use Secure\SecureStatic\SecureHash as SH;

var_dump(SH::getAlgorithms());
```

Check if two urls contains the same data

```php
require 'SecureStatic.php';
use Secure\SecureStatic\SecureHash as SH;

$jqueryGoogle = 'http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js';
$jqueryjQuery = 'http://code.jquery.com/jquery-2.1.1.min.js';

// true if are equals, false if not
var_dump(SH::compareUrls('ripped160', $jqueryGoogle, $jqueryjQuery));
```

Encrypt the content of multiple urls

```php
require 'SecureStatic.php';
use Secure\SecureStatic\SecureHash as SH;

$urls = array('http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js', 'http://code.jquery.com/jquery-2.1.1.min.js');

$cifratedUrls = SH::cifrateMultipleUrls('sha512', $urls);	// returns an array with the data encripted
var_dump($cifratedUrls);
```

