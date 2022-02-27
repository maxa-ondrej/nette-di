<?php declare(strict_types=1);

namespace Maxa\Ondrej\Nette\DI;

use Nette\DI\Container;
use Nette\PhpGenerator\Method;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

final class ServiceInjector {

    public function __construct(private ReflectionClass $reflection, private array $body = []) {
    }

    public static function setup(ReflectionClass $class, string $methodName, array $setup): Method {
        $injector = new ServiceInjector($class, $setup);
        $injector->injectParameters();

        $setupMethod = new Method($methodName);
        $setupMethod->setStatic();
        $setupMethod->addParameter('object')
            ->setType($class->getName());
        $setupMethod->addParameter('container')
            ->setType(Container::class);
        $setupMethod->setBody(implode("\n", $injector->body));

        return $setupMethod;
    }

    public function injectParameters(): void {
        foreach ($this->reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $this->handleProperty($property);
        }
        foreach ($this->reflection->getMethods(ReflectionProperty::IS_PUBLIC) as $method) {
            $this->handleMethod($method);
        }
    }

    private function handleMethod(ReflectionMethod $method): void {
        if (count($method->getAttributes(Parameter::class)) === 0) {
            return;
        }

        $parameters = $method->getParameters();
        if (count($parameters) !== 1) {
            return;
        }

        if (count($attributes = $method->getAttributes(Parameter::class)) === 1) {
            $this->body[] = sprintf('$object->%s(%s);', $method->name, $this->getParameter($attributes[0]->newInstance()->name));
        }
    }

    private function handleProperty(ReflectionProperty $property): void {
        if (count($attributes = $property->getAttributes(Parameter::class)) === 1) {
            $this->body[] = sprintf('$object->%s = %s;', $property->name, $this->getParameter($attributes[0]->newInstance()->name));
        }
    }

    private function getParameter(string $name): string {
        return "\$container->getParameters()['$name']";
    }

}