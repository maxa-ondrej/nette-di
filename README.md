# [**Nette Framework**](https://github.com/nette/nette) DI Extension
![](https://img.shields.io/packagist/dm/maxa-ondrej/nette-di.svg)
![](https://img.shields.io/packagist/php-v/maxa-ondrej/nette-di.svg)
![](https://img.shields.io/packagist/v/maxa-ondrej/nette-di.svg)

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

use Maxa\Ondrej\Nette\DI\Parameter;
use Maxa\Ondrej\Nette\DI\Service;

#[Service]
class MyService {

    #[Parameter('app.url')]
    public string $url;
    
}

#[Service('my.epic.service')]
class NamedService {
    
}
```
