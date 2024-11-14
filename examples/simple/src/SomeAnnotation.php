<?php

declare(strict_types=1);

namespace App;

use Pioniro\WrapperBundle\AnnotationInterface;

/**
 * @Annotation
 */
class SomeAnnotation implements AnnotationInterface
{
    public string $value;
}
