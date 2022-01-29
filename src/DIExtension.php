<?php declare(strict_types = 1);

namespace Maxa\Ondrej\Nette\DI;

use Nette\DI\CompilerExtension;
use Nette\Loaders\RobotLoader;
use ReflectionClass;
use function count;

final class DIExtension extends CompilerExtension {

    public function beforeCompile(): void {
        /** @var array<string, mixed> $config */
        $config = $this->compiler->getConfig()['parameters'];

        $loader = new RobotLoader();
        $loader->setTempDirectory($config['tempDir']);
        $loader->addDirectory($config['appDir']);
        $loader->refresh();
        /** @var array<class-string<object>,string> $classes */
        $classes = $loader->getIndexedClasses();
        foreach ($classes as $class => $file) {
            $this->handleClass($class);
        }
    }

    /**
     * @param class-string<T> $class
     *
     * @template T
     */
    private function handleClass(string $class): void {
        $reflection = new ReflectionClass($class);
        $services = $reflection->getAttributes(Service::class);
        if (count($services) > 0) {
            $service = $services[0]->newInstance();
            $definition = $this->getContainerBuilder()
                ->addDefinition($service->name)
                ->setFactory($class);
            ParameterInjector::inject($definition, $reflection);
        }
    }

}