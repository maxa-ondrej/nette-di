# [**Nette Framework**](https://github.com/nette/nette) DI Extension
[![Downloads](https://img.shields.io/packagist/dt/maxa-ondrej/nette-di.svg?style=flat-square)](https://packagist.org/packages/maxa-ondrej)
[![Build Status](https://img.shields.io/travis/maxa-ondrej/nette-di.svg?style=flat-square)](https://travis-ci.org/maxa-ondrej)
[![Coverage Status](https://img.shields.io/coveralls/github/maxa-ondrej/coding-standard.svg?style=flat-square)](https://coveralls.io/github/maxa-ondrej)
[![Latest Stable Version](https://img.shields.io/github/release/maxa-ondrej/nette-di.svg?style=flat-square)](https://github.com/maxa-ondrej/releases)

**Usage**
```
composer require maxa-ondrej/nette-di
```

**Nette Framework Usage**

***config.neon***
```yml
extensions:
    better-di: Maxa\Ondrej\Nette\DI\DIExtension
```

```php
<?php declare(strict_types=1);

use Maxa\Ondrej\Nette\DI\Service;

 #[Service]
class MyService {
    
}

 #[Service('my.epic.service')]
class NamedService {
    
}
```
