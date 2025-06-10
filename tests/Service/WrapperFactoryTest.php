<?php

declare(strict_types=1);

namespace Pioniro\WrapperBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Pioniro\WrapperBundle\AnnotationInterface;
use Pioniro\WrapperBundle\HandlerInterface;
use Pioniro\WrapperBundle\Service\WrapperFactory;
use Pioniro\WrapperBundle\Tests\Annotation1;
use Pioniro\WrapperBundle\Tests\B;

/**
 * @covers \Pioniro\WrapperBundle\Service\WrapperFactory
 */
class WrapperFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../Classes.php';
    }

    public function testWrap(): void
    {
        $dir = sys_get_temp_dir();
        $wrapper = new WrapperFactory($dir, 'Wrapper');
        [$class, $file] = $wrapper->wrap(new \ReflectionClass(B::class), ['t', 'a']);
        require_once $file;
        $this->assertTrue(class_exists($class));
        $this->assertEquals('Wrapper\\' . B::class, $class);
        $object = new $class();
        $a = null;
        $handler = new Handler(function ($result, $annotation) use (&$a) {
            $a = $annotation;

            return $result;
        });
        $object->__addHandler('t', $handler, serialize(new Annotation1()));
        $object->t();
        $this->assertInstanceOf(Annotation1::class, $a);
    }
}

class Handler implements HandlerInterface
{
    private \Closure $f;

    public function __construct(\Closure $f)
    {
        $this->f = $f;
    }

    public function handle(callable $next, string $method, array $args, AnnotationInterface $annotation): callable
    {
        return fn () => ($this->f)($next(), $annotation);
    }

    public static function handledClass(): string
    {
        return Annotation1::class;
    }
}
