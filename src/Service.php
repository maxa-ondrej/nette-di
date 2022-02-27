<?php declare(strict_types=1);

namespace Maxa\Ondrej\Nette\DI;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Service {

    public function __construct(
        public ?string $name = null,
        public string|array $tags = [],
        /**
         * @var string|array One or multiple setup calls. $object ($this service) and $container are available to you
         */
        public string|array $setup = [],
        public bool $autostart = false,
        public bool $autowired = true,
    ) {
    }

}