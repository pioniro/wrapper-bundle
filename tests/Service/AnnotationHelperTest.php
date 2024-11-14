<?php

declare(strict_types=1);

namespace Pioniro\WrapperBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Pioniro\WrapperBundle\Service\AnnotationHelper;
use Pioniro\WrapperBundle\Tests\Annotation1;
use Pioniro\WrapperBundle\Tests\Annotation2;
use Pioniro\WrapperBundle\Tests\B;

/**
 * @covers \Pioniro\WrapperBundle\Service\AnnotationHelper
 */
class AnnotationHelperTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../Classes.php';
    }

    public function testGetAnnotations(): void
    {
        $helper = new AnnotationHelper();
        $annotations = $helper->getAnnotations(new \ReflectionClass(B::class));
        $this->assertEquals([
            'a' => [
                new Annotation1(),
                new Annotation2(),
            ],
            'c' => [
                new Annotation2(),
                new Annotation1(),
            ],
            't' => [
                new Annotation1(),
            ],
            'n' => [
                new Annotation1(),
            ],
        ], $annotations);
    }
}
