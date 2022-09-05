<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Controller;

use OpenApi\Attributes as OA;
use Sandronimus\NelmioFormGetParameterPluginBundle\Attribute\FormGetParameter;
use Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Form\Test2FormType;
use Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Form\TestFormType;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    #[OA\Get(summary: "test")]
    #[FormGetParameter(TestFormType::class)]
    #[FormGetParameter(Test2FormType::class)]
    #[Route("/api/test", name: 'test', methods: ['GET'])]
    public function testAction()
    {

    }
}
