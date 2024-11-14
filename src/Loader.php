<?php

declare(strict_types=1);

namespace Pioniro\WrapperBundle;

final class Loader
{
    private static ?self $instance = null;
    /**
     * @var array<string, string>
     */
    private array $map;

    private function __construct(string $cacheDir, string $filename)
    {
        $this->map = require $cacheDir . '/' . $filename;
        spl_autoload_register([$this, 'loadClass']);
    }

    public function loadClass(string $class): void
    {
        if (isset($this->map[$class])) {
            require $this->map[$class];
        }
    }

    public static function register(string $cacheDir, string $filename): void
    {
        if (null === self::$instance && file_exists($cacheDir . '/' . $filename)) {
            self::$instance = new self($cacheDir, $filename);
        }
    }
}
