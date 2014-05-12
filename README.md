# SecureHash
==========

Class to encrypt data easily with PHP

requires PHP 5 >= 5.1.2 or PECL hash >= 1.1

## Quick Start and Examples

### Static class version

Simple string hashing.

```php
require 'SessionStatic.php';
// 'as SH' is to set an alias of SecureHash class name
// and it is optional
use Secure\SecureStatic\SecureHash as SH;

echo SH::cifrate('sha512', 'Hello World!');
```

Hashing a file.

```php
require 'SessionStatic.php';
use Secure\SecureStatic\SecureHash as SH;

echo SH::cifrateFile('md4', 'route/to/file.extension');
```

print all algorithms implemented by PHP

```php
require 'SessionStatic.php';
use Secure\SecureStatic\SecureHash as SH;

var_dump(SH::getAlgorithms());
```
