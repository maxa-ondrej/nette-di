<?php declare(strict_types=1);

namespace Maxa\Ondrej\Nette\DI;

use Nette\DI\Definitions\ServiceDefinition;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

final class ParameterInjector {

    private function __construct(private ServiceDefinition $definition) {
    }

    public static function inject(ServiceDefinition $definition, ReflectionClass $reflection): void {
        $injector = new ParameterInjector($definition);
        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $injector->handleProperty($property);
        }
        foreach ($reflection->getMethods(ReflectionProperty::IS_PUBLIC) as $method) {
            $injector->handleMethod($method);
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

        foreach ($parameters[0]->getAttributes(Parameter::class) as $attribute) {
            $instance = $attribute->newInstance();
            $this->definition->addSetup("$method->name(%$instance->name%)");
        }
    }

    private function handleProperty(ReflectionProperty $property): void {
        foreach ($property->getAttributes(Parameter::class) as $attribute) {
            $instance = $attribute->newInstance();
            $this->definition->addSetup("$$property->name = %$instance->name%");
        }
    }

}