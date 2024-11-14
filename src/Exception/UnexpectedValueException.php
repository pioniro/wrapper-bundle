<?php

declare(strict_types=1);

namespace Pioniro\WrapperBundle\Exception;

class UnexpectedValueException extends \RuntimeException
{
    /**
     * @param string $proxyDirectory
     *
     * @return self
     */
    public static function proxyDirectoryNotWritable($proxyDirectory)
    {
        return new self(\sprintf('Your proxy directory "%s" must be writable', $proxyDirectory));
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param string $parameterName
     *
     * @psalm-param class-string $className
     *
     * @return self
     */
    public static function invalidParameterTypeHint(
        $className,
        $methodName,
        $parameterName,
        ?\Throwable $previous = null
    ) {
        return new self(
            \sprintf(
                'The type hint of parameter "%s" in method "%s" in class "%s" is invalid.',
                $parameterName,
                $methodName,
                $className
            ),
            [],
            $previous
        );
    }

    /**
     * @param string $className
     * @param string $methodName
     *
     * @psalm-param class-string $className
     *
     * @return self
     */
    public static function invalidReturnTypeHint($className, $methodName, ?\Throwable $previous = null)
    {
        return new self(
            \sprintf(
                'The return type of method "%s" in class "%s" is invalid.',
                $methodName,
                $className
            ),
            0,
            $previous
        );
    }
}
