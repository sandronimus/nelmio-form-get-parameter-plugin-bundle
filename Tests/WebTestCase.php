<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class WebTestCase extends BaseWebTestCase
{
    protected static function createKernel(array $options = []): KernelInterface
    {
        return new TestKernel();
    }

}
