WrapperBundle
=====
This is a wrapper bundle for the [Symfony](https://symfony.com) framework.

Main goal of this bundle is to provide a simple way to create a wrappers for the services.

Under the hood, this bundle performs code generation similar to how Doctrine does for entity proxying.

## Installation
```bash
composer require pioniro/wrapper-bundle
```

## Usage

You can see the example of usage in the [example](example) directory.

### Create an annotation
```php
<?php

/**
* @Annotation
 */
class LogException implements \Pioniro\WrapperBundle\AnnotationInterface
{
}
```

### Create a handler
```php
<?php
class LogExceptionHandler implements \Pioniro\WrapperBundle\HandlerInterface
{
    public function __construct(private \Psr\Log\LoggerInterface $logger) {}

    public function handle(callable $next, AnnotationInterface $annotation): callable
    {
        return function ($input) use ($next) {
            try{
                return $next($input);
            } catch (\Throwable $exception) {
                $this->logger->error($exception->getMessage(), compact('exception'));
                throw $exception;
        }
    }

    public static function handledClass(): string
    {
        return LogException::class;
    }
}
```

### Annotate a service
```php
<?php

class MyService
{
    #[LogException]
    public function doSomethingWithPHP8(): void
    {
        throw new \Exception('Something went wrong');
    }

    /**
    * @LogException
    */
    protected function doSomethingWithPHP7(): void
    {
        throw new \Exception('Something went wrong');
    }
}
```

### Register the handler
```yaml
services:
    App\Handler\LogException:
        tags:
            - { name: wrapper.handler }
```
OR
```yaml
services:
    _instanceof:
        Pioniro\WrapperBundle\HandlerInterface:
            tags: ['wrapper.handler']
```


### Enjoy

And now, when you call `doSomethingWithPHP8` or `doSomethingWithPHP7` method, the exception will be logged.

You can create as many handlers as you want and use them in your services.

## Limitations

- Handlers are not called for private, static or final methods.
- Handlers are not called for methods of the final classes.
- Handlers are not called for methods of the classes that are not in the container.
- May occur strange errors if you use `static` keyword