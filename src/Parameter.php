<?php declare(strict_types=1);

namespace Maxa\Ondrej\Nette\DI;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER|Attribute::TARGET_PROPERTY)]
final class Parameter {

    public function __construct(public string $name) {
    }

}