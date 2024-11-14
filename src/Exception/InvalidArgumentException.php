<?php

declare(strict_types=1);

namespace Pioniro\WrapperBundle\Exception;

class InvalidArgumentException extends \RuntimeException
{
    public static function proxyDirectoryRequired(): self
    {
        return new self('You must configure a proxy directory. See docs for details');
    }

    public static function proxyNamespaceRequired(): self
    {
        return new self('You must configure a proxy namespace');
    }

    /**
     * @param string $className
     *
     * @psalm-param class-string $className
     */
    public static function classMustNotBeAbstract($className): self
    {
        return new self(\sprintf('Unable to create a proxy for an abstract class "%s".', $className));
    }

    /**
     * @param string $className
     *
     * @psalm-param class-string $className
     */
    public static function classMustNotBeFinal($className): self
    {
        return new self(\sprintf('Unable to create a proxy for a final class "%s".', $className));
    }

    /**
     * @param string $className
     *
     * @psalm-param class-string $className
     */
    public static function classMustNotBeReadOnly($className): self
    {
        return new self(\sprintf('Unable to create a proxy for a readonly class "%s".', $className));
    }
}
