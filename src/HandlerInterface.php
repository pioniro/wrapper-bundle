<?php

declare(strict_types=1);

namespace Pioniro\WrapperBundle;

interface HandlerInterface
{
    public function handle(callable $next, AnnotationInterface $annotation): callable;

    public static function handledClass(): string;
}
