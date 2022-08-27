<?php declare(strict_types=1);

namespace Maxa\Ondrej\Nette\DI;

use Nette\DI\CompilerExtension;
use Nette\Loaders\RobotLoader;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use ReflectionClass;
use function count;

final class DIExtension extends CompilerExtension
{

    const CLASS_NAME = 'NetteDIContainerSetup';
    private int $number = 0;

    public function beforeCompile(): void
    {
        /** @var array<string, mixed> $config */
        $config = $this->compiler->getConfig()['parameters'];
        $container = $config['tempDir'] . '/NetteDIContainerSetup.php';
        $this->initialization->addBody("include_once '$container';");

        $loader = new RobotLoader();
        $loader->setTempDirectory($config['tempDir']);
        $loader->addDirectory($config['appDir']);
        $loader->refresh();

        $setupClass = new ClassType(self::CLASS_NAME);
        /** @var array<class-string<object>,string> $classes */
        $classes = $loader->getIndexedClasses();
        foreach ($classes as $class => $file) {
            $method = $this->setup($class);
            if ($method !== null) {
                $setupClass->addMember($method);
            }
        }
        file_put_contents($container, '<?php ' . $setupClass);
        include_once $container;
    }

    /**
     * @param class-string<T> $class
     *
     * @template T
     */
    private function setup(string $class): ?Method
    {
        $reflection = new ReflectionClass($class);
        $services = $reflection->getAttributes(Service::class);
        if (count($services) === 0) {
            return null;
        }
        $service = $services[0]->newInstance();
        assert($service instanceof Service);

        $methodName = 'setupService' . ++$this->number;
        $definition = $this->getContainerBuilder()
            ->addDefinition($service->name)
            ->setFactory($class)
            ->setAutowired($service->autowired)
            ->addSetup(self::CLASS_NAME . '::' . $methodName);

        if (is_array($service->tags)) {
            foreach ($service->tags as $tag => $attr) {
                if (is_int($tag)) {
                    $definition->addTag($attr);
                } else {
                    $definition->addTag($tag, $attr);
                }
            }
        } else {
            $definition->addTag($service->tags);
        }

        if ($service->autostart) {
            $this->initialization->addBody('$this->getService(?);', [$service->name ?? $class]);
        }

        return ServiceInjector::setup($reflection, $methodName, is_array($service->setup) ? $service->setup : [$service->setup]);
    }

}