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

#[Service(
    name: 'my.epic.service',
    tags: 'cache',
    setup: '$object->setDebugMode($container->getParameters()["debugMode"]);',
    autostart: true,
    autowired: true
)]
class NamedService {

    private bool $debugMode = true;

    public function setDebugMode(bool $debugMode): void {
        $this->debugMode = $debugMode;
    }
    
}
```
