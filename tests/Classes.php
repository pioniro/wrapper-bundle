<?php

declare(strict_types=1);

namespace Pioniro\WrapperBundle\Tests;

trait T
{
    /**
     * @Annotation1
     */
    public function t()
    {
    }
}

interface Inter
{
    /**
     * @Annotation2
     */
    public function a();
}

class A implements Inter
{
    use T;

    /**
     * @some
     *
     * @Annotation0
     *
     * @Annotation1
     */
    public function a()
    {
    }

    public function b()
    {
    }

    /**
     * @Annotation1
     */
    protected function c()
    {
    }

    /**
     * @Annotation1
     */
    public static function s()
    {
    }

    /**
     * @Annotation1
     */
    final public function f()
    {
    }

    /**
     * @Annotation1
     *
     * @Annotation0([)
     */
    public function fail()
    {
    }

    /**
     * @Annotation0
     */
    private function d()
    {
    }
}

class B extends A
{
    public function a()
    {
    }

    /**
     * @Annotation2
     */
    public function c()
    {
    }

    /**
     * @Annotation1
     */
    public function n()
    {
    }
}
