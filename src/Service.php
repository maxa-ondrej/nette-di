<?php declare(strict_types=1);

namespace Maxa\Ondrej\Nette\DI;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Service {

    public function __construct(public ?string $name = null) {
    }

}