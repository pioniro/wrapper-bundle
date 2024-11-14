<?php

declare(strict_types=1);

namespace Pioniro\WrapperBundle\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use Doctrine\Common\Annotations\Reader;
use Pioniro\WrapperBundle\AnnotationInterface;

class AnnotationHelper
{
    private Reader $reader;

    public function __construct(?Reader $reader = null)
    {
        if (!$reader) {
            $parser = new DocParser();
            $parser->setIgnoreNotImportedAnnotations(true);
            $reader = new AnnotationReader($parser);
        }
        $this->reader = $reader;
    }

    /**
     * @param \ReflectionClass $class
     *
     * @return AnnotationInterface[][]
     */
    public function getAnnotations(\ReflectionClass $class): array
    {
        $methodsAnnotations = [];
        foreach ($this->getMethods($class) as $methodsList) {
            foreach ($methodsList as $method) {
                try {
                    $annotations = $this->reader->getMethodAnnotations($method);
                } catch (\Throwable $e) {
                    continue;
                }
                foreach ($annotations as $annotation) {
                    if ($annotation instanceof AnnotationInterface) {
                        $methodsAnnotations[$method->getName()][] = $annotation;
                    }
                }
            }
        }

        return $methodsAnnotations;
    }

    /**
     * @return \ReflectionMethod[][]
     */
    public function getMethods(\ReflectionClass $class): array
    {
        $methods = [];
        $uniq = [];
        foreach ($class->getMethods() as $method) {
            if ($this->isMethodCanBeWrapped($method)) {
                $uniq[$method->getName() . $method->getDeclaringClass()->getName()] = true;
                if (!isset($methods[$method->getName()])) {
                    $methods[$method->getName()] = [];
                }
                $methods[$method->getName()][] = $method;
            }
        }
        $parent = $class;
        $parents = [];
        while ($parent = $parent->getParentClass()) {
            $parents[] = $parent;
        }

        foreach ($parents + $class->getInterfaces() as $interface) {
            foreach ($interface->getMethods() as $method) {
                if ($this->isParentMethodCanBeWrapped($method)) {
                    if (isset($uniq[$method->getName() . $method->getDeclaringClass()->getName()])) {
                        continue;
                    }
                    $uniq[$method->getName() . $method->getDeclaringClass()->getName()] = true;
                    $methods[$method->getName()][] = $method;
                }
            }
        }

        return $methods;
    }

    private function isMethodCanBeWrapped(\ReflectionMethod $method): bool
    {
        return $this->isParentMethodCanBeWrapped($method) && !$method->isAbstract();
    }

    private function isParentMethodCanBeWrapped(\ReflectionMethod $method): bool
    {
        return ($method->isPublic() || $method->isProtected()) && !$method->isFinal() && !$method->isStatic();
    }
}
