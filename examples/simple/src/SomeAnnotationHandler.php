<?php

declare(strict_types=1);

namespace App;

use Pioniro\WrapperBundle\AnnotationInterface;
use Pioniro\WrapperBundle\HandlerInterface;

class SomeAnnotationHandler implements HandlerInterface
{
    public function handle(callable $next, array $args, AnnotationInterface $annotation): callable
    {
        \assert($annotation instanceof SomeAnnotation);
        dump(__CLASS__ . ':: BEFORE');
        dump(__CLASS__ . ':: passed value:' . $annotation->value);

        return function () use ($next) {
            $result = $next();
            dump(__CLASS__ . ':: AFTER');

            return $result;
        };
    }

    public static function handledClass(): string
    {
        return SomeAnnotation::class;
    }
}
