<?php

declare(strict_types=1);

namespace Pioniro\WrapperBundle\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use Pioniro\WrapperBundle\AnnotationInterface;
use Pioniro\WrapperBundle\HandlerInterface;
use Pioniro\WrapperBundle\Loader;
use Pioniro\WrapperBundle\Service\AnnotationHelper;
use Pioniro\WrapperBundle\Service\WrapperFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class WrapperCompilerPass implements CompilerPassInterface
{
    /**
     * @var string[][]
     */
    private ?array $handlers = null;

    public function process(ContainerBuilder $container): void
    {
        $cacheDir = $container->getParameter('kernel.cache_dir');
        \assert(\is_string($cacheDir));
        $wrappersEnabled = $container->getParameter('wrappers.enabled');
        if (!$wrappersEnabled) {
            return;
        }
        class_exists(AnnotationInterface::class);
        $parser = new DocParser();
        $parser->setIgnoreNotImportedAnnotations(true);
        $reader = new AnnotationReader($parser);
        $wrapperFactory = new WrapperFactory($cacheDir . '/wrappers', 'Wrapper');
        $annotationHelper = new AnnotationHelper($reader);
        $wrappers = [];
        $visited = [];
        foreach ($container->getDefinitions() as $definition) {
            $class = $definition->getClass();
            try {
                $classExists = null !== $class && class_exists($class);
            } catch (\Throwable $e) {
                continue;
            }
            if ($classExists) {
                $ref = new \ReflectionClass($class);
                if ($ref->isFinal() || $ref->isTrait()) {
                    continue;
                }
                if ($definition->isShared()) {
                    if (isset($visited[$class])) {
                        continue;
                    }
                    $visited[$class] = true;
                }
                $methodsAnnotations = $annotationHelper->getAnnotations($ref);
                if (\count($methodsAnnotations) > 0) {
                    [$class, $filename] = $wrapperFactory->wrap($ref, array_keys($methodsAnnotations));
                    $handlersExists = false;
                    foreach ($methodsAnnotations as $method => $methodsAnnotation) {
                        foreach ($methodsAnnotation as $annotation) {
                            $handlers = $this->getHandlers($container, $annotation);
                            $text = serialize($annotation);
                            foreach ($handlers as $handlerId) {
                                $definition->addMethodCall('__addHandler', [$method, new Reference($handlerId), $text]);
                                $handlersExists = true;
                            }
                        }
                    }
                    if ($handlersExists) {
                        $definition->setClass($class);
                        $wrappers[$class] = $filename;
                    }
                }
            }
        }
        file_put_contents($cacheDir . '/wrappers.php', '<?php return ' . var_export($wrappers, true) . ';');
        Loader::register($cacheDir, 'wrappers.php');
    }

    /**
     * @return iterable<string>
     */
    private function getHandlers(ContainerBuilder $builder, AnnotationInterface $annotation): iterable
    {
        $annotationClass = \get_class($annotation);
        if (null === $this->handlers) {
            $this->handlers = [];
            foreach ($builder->findTaggedServiceIds('wrapper.handler') as $id => $tags) {
                $definition = $builder->getDefinition($id);
                $class = $definition->getClass();
                if (!$class || !class_exists($class) || !is_subclass_of($class, HandlerInterface::class)) {
                    continue;
                }
                $annotationClass = $class::handledClass();
                if (!class_exists($annotationClass)) {
                    // throw an error
                    continue;
                }
                $this->handlers[$annotationClass][] = $id;
            }
        }
        foreach ($this->handlers as $handledClass => $handlersId) {
            if (is_a($annotationClass, $handledClass, true)) {
                foreach ($handlersId as $handlerId) {
                    yield $handlerId;
                }
            }
        }
    }
}
